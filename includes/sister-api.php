<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EduCampus_Sister_API {

    private $api_url;
    private $token;
    private $token_expiry;
    private $username;
    private $password;
    private $id_pengguna;

    public function __construct() {
        $this->load_config();
    }

    private function load_config() {
        $config = get_option( 'educampus_sister_config', array() );
        $this->api_url     = ! empty( $config['api_url'] ) ? rtrim( $config['api_url'], '/' ) : '';
        $this->username    = ! empty( $config['username'] ) ? $config['username'] : '';
        $this->password    = ! empty( $config['password'] ) ? $this->decrypt( $config['password'] ) : '';
        $this->id_pengguna = ! empty( $config['id_pengguna'] ) ? $config['id_pengguna'] : '';
        $this->token       = ! empty( $config['token'] ) ? $config['token'] : '';
        $this->token_expiry = ! empty( $config['token_expiry'] ) ? $config['token_expiry'] : 0;
    }

    public function save_config( $data ) {
        $config = get_option( 'educampus_sister_config', array() );
        $config['api_url']     = rtrim( $data['api_url'], '/' );
        $config['username']    = sanitize_text_field( $data['username'] );
        $config['id_pengguna'] = sanitize_text_field( $data['id_pengguna'] );

        if ( ! empty( $data['password'] ) ) {
            $config['password'] = $this->encrypt( $data['password'] );
        }

        if ( isset( $data['token'] ) ) {
            $config['token'] = $data['token'];
        }
        if ( isset( $data['token_expiry'] ) ) {
            $config['token_expiry'] = $data['token_expiry'];
        }

        $config['is_active'] = true;
        update_option( 'educampus_sister_config', $config );
        $this->load_config();
    }

    public function get_config() {
        return get_option( 'educampus_sister_config', array() );
    }

    public function is_configured() {
        return ! empty( $this->api_url ) && ! empty( $this->username ) && ! empty( $this->password );
    }

    public function login() {
        if ( ! $this->is_configured() ) {
            return array( 'success' => false, 'message' => 'Konfigurasi SISTER belum lengkap.' );
        }

        if ( $this->token && $this->token_expiry > time() ) {
            return array( 'success' => true, 'message' => 'Menggunakan token tersimpan (valid).' );
        }

        $url = $this->api_url . '/authorize';

        $body = wp_json_encode( array(
            'username'    => $this->username,
            'password'    => $this->password,
            'id_pengguna' => $this->id_pengguna,
        ) );

        $response = wp_remote_post( $url, array(
            'headers' => array(
                'Content-Type'   => 'application/json',
                'Accept'         => 'application/json',
            ),
            'body'    => $body,
            'timeout' => 30,
            'sslverify' => false,
        ) );

        if ( is_wp_error( $response ) ) {
            $err_msg = $response->get_error_message();
            $this->log_error( 'Login HTTP error', $err_msg );
            return array( 'success' => false, 'message' => 'Gagal koneksi: ' . $err_msg );
        }

        $code = wp_remote_retrieve_response_code( $response );
        $resp_body = wp_remote_retrieve_body( $response );
        $data = json_decode( $resp_body, true );

        if ( $code === 200 && is_array( $data ) && isset( $data['token'] ) ) {
            $this->token = $data['token'];
            $expiry = time() + 3300;

            $config = get_option( 'educampus_sister_config', array() );
            $config['token'] = $data['token'];
            $config['token_expiry'] = $expiry;
            update_option( 'educampus_sister_config', $config );

            return array( 'success' => true, 'message' => 'Koneksi & Login Berhasil.' );
        }

        $error_msg = isset( $data['message'] ) ? $data['message'] : ( isset( $data['error'] ) ? $data['error'] : '' );
        if ( empty( $error_msg ) ) {
            $error_msg = 'HTTP ' . $code . ': ' . substr( $resp_body, 0, 200 );
        }
        $this->log_error( 'Login failed', "HTTP {$code}: {$error_msg}" );
        return array( 'success' => false, 'message' => 'Gagal login SISTER: ' . $error_msg );
    }

    public function request( $method, $endpoint, $data = array(), $retry = true ) {
        if ( ! $this->token ) {
            $login = $this->login();
            if ( ! $login['success'] ) {
                return null;
            }
        }

        $url = $this->api_url . '/' . ltrim( $endpoint, '/' );

        $args = array(
            'headers' => array(
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . $this->token,
            ),
            'timeout'   => 30,
            'sslverify' => false,
        );

        if ( $method === 'GET' && ! empty( $data ) ) {
            $url = add_query_arg( $data, $url );
        } elseif ( $method !== 'GET' ) {
            $args['body'] = wp_json_encode( $data );
        }

        $response = $method === 'GET' ? wp_remote_get( $url, $args ) : wp_remote_post( $url, $args );

        if ( is_wp_error( $response ) ) {
            $this->log_error( "Request failed [{$endpoint}]", $response->get_error_message() );
            return null;
        }

        $code = wp_remote_retrieve_response_code( $response );

        if ( $code === 401 && $retry ) {
            $this->token = '';
            $config = get_option( 'educampus_sister_config', array() );
            $config['token_expiry'] = 0;
            update_option( 'educampus_sister_config', $config );
            return $this->request( $method, $endpoint, $data, false );
        }

        if ( $code === 200 ) {
            $body = wp_remote_retrieve_body( $response );
            return json_decode( $body, true );
        }

        $this->log_error( "Request failed [{$endpoint}]", "HTTP {$code}" );
        return null;
    }

    public function request_raw( $endpoint ) {
        if ( ! $this->token ) {
            $this->login();
        }

        $url = $this->api_url . '/' . ltrim( $endpoint, '/' );

        $args = array(
            'headers'   => array( 'Authorization' => 'Bearer ' . $this->token ),
            'timeout'   => 30,
            'sslverify' => false,
        );

        $response = wp_remote_get( $url, $args );

        if ( is_wp_error( $response ) ) {
            $this->log_error( 'request_raw failed', $response->get_error_message() );
            return null;
        }

        $code = wp_remote_retrieve_response_code( $response );

        if ( $code === 401 ) {
            $this->token = '';
            $config = get_option( 'educampus_sister_config', array() );
            $config['token_expiry'] = 0;
            update_option( 'educampus_sister_config', $config );
            $this->login();

            $args['headers']['Authorization'] = 'Bearer ' . $this->token;
            $response = wp_remote_get( $url, $args );

            if ( is_wp_error( $response ) ) {
                return null;
            }

            $code = wp_remote_retrieve_response_code( $response );
        }

        if ( $code !== 200 ) {
            $this->log_error( 'request_raw non-200', "HTTP {$code} for {$endpoint}" );
            return null;
        }

        $content_type = wp_remote_retrieve_header( $response, 'content-type' );
        if ( $content_type && strpos( $content_type, 'image/' ) === false && strpos( $content_type, 'application/octet-stream' ) === false ) {
            $this->log_error( 'request_raw wrong content-type', $content_type . ' for ' . $endpoint );
            return null;
        }

        return wp_remote_retrieve_body( $response );
    }

    public function get_sdm_list( $limit = 100, $offset = 0 ) {
        return $this->request( 'GET', '/referensi/sdm', array(
            'limit'  => $limit,
            'offset' => $offset,
        ) );
    }

    public function get_profile( $id_sdm ) {
        return $this->request( 'GET', "/data_pribadi/profil/{$id_sdm}" );
    }

    public function get_kepegawaian( $id_sdm ) {
        return $this->request( 'GET', "/data_pribadi/kepegawaian/{$id_sdm}" );
    }

    public function get_kependudukan( $id_sdm ) {
        return $this->request( 'GET', "/data_pribadi/kependudukan/{$id_sdm}" );
    }

    public function get_foto( $id_sdm ) {
        return $this->request_raw( "/data_pribadi/foto/{$id_sdm}" );
    }

    public function get_alamat( $id_sdm ) {
        return $this->request( 'GET', "/data_pribadi/alamat/{$id_sdm}" );
    }

    public function get_bidang_ilmu( $id_sdm ) {
        return $this->request( 'GET', "/data_pribadi/bidang_ilmu/{$id_sdm}" );
    }

    public function get_riwayat_pendidikan( $id_sdm ) {
        return $this->request( 'GET', '/pendidikan_formal', array( 'id_sdm' => $id_sdm ) );
    }

    public function get_riwayat_pangkat( $id_sdm ) {
        return $this->request( 'GET', "/riwayat_pangkat/{$id_sdm}" );
    }

    public function get_dokumen( $id_sdm ) {
        return $this->request( 'GET', '/dokumen', array( 'id_sdm' => $id_sdm ) );
    }

    public function get_penelitian( $id_sdm, $page = 1, $per_page = 100 ) {
        return $this->request( 'GET', '/penelitian', array(
            'id_sdm'   => $id_sdm,
            'page'     => $page,
            'per_page' => $per_page,
        ) );
    }

    public function get_publikasi( $id_sdm, $page = 1, $per_page = 100 ) {
        return $this->request( 'GET', '/publikasi', array(
            'id_sdm'   => $id_sdm,
            'page'     => $page,
            'per_page' => $per_page,
        ) );
    }

    public function get_pengabdian( $id_sdm, $page = 1, $per_page = 100 ) {
        return $this->request( 'GET', '/pengabdian', array(
            'id_sdm'   => $id_sdm,
            'page'     => $page,
            'per_page' => $per_page,
        ) );
    }

    public function get_jabatan_fungsional( $id_sdm ) {
        return $this->request( 'GET', '/jabatan_fungsional', array( 'id_sdm' => $id_sdm ) );
    }

    public function get_penugasan( $id_sdm ) {
        return $this->request( 'GET', '/penugasan', array( 'id_sdm' => $id_sdm ) );
    }

    private function encrypt( $value ) {
        if ( empty( $value ) ) {
            return '';
        }
        $key = $this->get_encryption_key();

        if ( function_exists( 'openssl_encrypt' ) ) {
            $iv_length = openssl_cipher_iv_length( 'aes-256-cbc' );
            $iv = openssl_random_pseudo_bytes( $iv_length );
            $encrypted = openssl_encrypt( $value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv );
            if ( $encrypted !== false ) {
                return 'openssl:' . base64_encode( $iv . $encrypted );
            }
        }

        return 'base64:' . base64_encode( $value );
    }

    private function decrypt( $value ) {
        if ( empty( $value ) ) {
            return '';
        }

        if ( strpos( $value, 'openssl:' ) === 0 ) {
            $key = $this->get_encryption_key();
            $raw = base64_decode( substr( $value, 8 ) );
            $iv_length = openssl_cipher_iv_length( 'aes-256-cbc' );
            $iv = substr( $raw, 0, $iv_length );
            $encrypted = substr( $raw, $iv_length );
            $result = @openssl_decrypt( $encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv );
            if ( $result !== false ) {
                return $result;
            }
        }

        if ( strpos( $value, 'base64:' ) === 0 ) {
            return base64_decode( substr( $value, 7 ) );
        }

        return base64_decode( $value );
    }

    private function get_encryption_key() {
        $salt = defined( 'NONCE_KEY' ) ? NONCE_KEY : ( defined( 'LOGGED_IN_KEY' ) ? LOGGED_IN_KEY : 'educampus-sister-key' );
        return hash( 'sha256', $salt, true );
    }

    private function log_error( $context, $message ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( "[EduCampus SISTER] {$context}: {$message}" );
        }
    }
}
