<div class="wrap kalender-wrap">
    <h1><?php esc_html_e( 'Kalender Akademik', 'educampus' ); ?></h1>
    <p class="description"><?php esc_html_e( 'Kelola jadwal akademik: semester, kuliah, UTS, UAS, wisuda, libur, dan hari besar nasional.', 'educampus' ); ?></p>

    <hr class="wp-header-end">

    <div id="kalender-notice"></div>

    <!-- STATUS BAR -->
    <div class="kalender-status-bar">
        <strong><?php esc_html_e( 'Tahun Akademik:', 'educampus' ); ?></strong>
        <span id="kalender-current-year"><?php echo esc_html( $settings['academic_year'] ); ?></span>
        <span class="separator">|</span>
        <strong><?php esc_html_e( 'Semester:', 'educampus' ); ?></strong>
        <span id="kalender-current-semester"><?php echo esc_html( $settings['semester'] ); ?></span>
        <span class="separator">|</span>
        <strong><?php esc_html_e( 'Total Event:', 'educampus' ); ?></strong>
        <span id="kalender-total-events"><?php echo count( $events ); ?></span>
    </div>

    <!-- TOOLBAR -->
    <div class="kalender-toolbar">
        <div class="kalender-toolbar-left">
            <button type="button" id="btn-add-event" class="button button-primary">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align:middle;"></span>
                <?php esc_html_e( 'Tambah Event', 'educampus' ); ?>
            </button>
            <button type="button" id="btn-import-sample" class="button button-secondary">
                <span class="dashicons dashicons-download" style="vertical-align:middle;"></span>
                <?php esc_html_e( 'Import Contoh', 'educampus' ); ?>
            </button>
            <button type="button" id="btn-save-all" class="button button-secondary" style="display:none;">
                <span class="dashicons dashicons-saved" style="vertical-align:middle;"></span>
                <?php esc_html_e( 'Simpan Semua Perubahan', 'educampus' ); ?>
            </button>
        </div>
        <div class="kalender-toolbar-right">
            <select id="filter-type">
                <option value=""><?php esc_html_e( 'Semua Tipe', 'educampus' ); ?></option>
                <?php
                $types = educampus_kalender_event_types();
                foreach ( $types as $key => $type ) :
                ?>
                    <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $type['label'] ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- PENGATURAN AKADEMIK -->
    <div class="kalender-settings-card" id="kalender-settings-card">
        <h2>
            <span class="dashicons dashicons-admin-generic"></span>
            <?php esc_html_e( 'Pengaturan Akademik', 'educampus' ); ?>
            <button type="button" class="button button-small kalender-toggle-settings" id="btn-toggle-settings">
                <span class="dashicons dashicons-arrow-up-alt2"></span>
            </button>
        </h2>
        <div class="kalender-settings-body" id="kalender-settings-body">
            <form id="kalender-settings-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="academic_year"><?php esc_html_e( 'Tahun Akademik', 'educampus' ); ?></label></th>
                        <td>
                            <input type="text" name="academic_year" id="academic_year" class="regular-text"
                                value="<?php echo esc_attr( $settings['academic_year'] ); ?>"
                                placeholder="2025/2026" required>
                            <p class="description"><?php esc_html_e( 'Format: YYYY/YYYY', 'educampus' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="semester"><?php esc_html_e( 'Semester Aktif', 'educampus' ); ?></label></th>
                        <td>
                            <select name="semester" id="semester">
                                <option value="Ganjil" <?php selected( $settings['semester'], 'Ganjil' ); ?>><?php esc_html_e( 'Ganjil (Gasal)', 'educampus' ); ?></option>
                                <option value="Genap" <?php selected( $settings['semester'], 'Genap' ); ?>><?php esc_html_e( 'Genap', 'educampus' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="semester_start"><?php esc_html_e( 'Awal Perkuliahan', 'educampus' ); ?></label></th>
                        <td>
                            <input type="date" name="semester_start" id="semester_start"
                                value="<?php echo esc_attr( $settings['semester_start'] ); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="semester_end"><?php esc_html_e( 'Akhir Perkuliahan', 'educampus' ); ?></label></th>
                        <td>
                            <input type="date" name="semester_end" id="semester_end"
                                value="<?php echo esc_attr( $settings['semester_end'] ); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="front_page_slug"><?php esc_html_e( 'Slug Halaman', 'educampus' ); ?></label></th>
                        <td>
                            <div style="display:flex;align-items:center;gap:4px;">
                                <span class="description"><?php echo esc_url( home_url( '/' ) ); ?></span>
                                <input type="text" name="front_page_slug" id="front_page_slug" class="regular-text"
                                    value="<?php echo esc_attr( $settings['front_page_slug'] ); ?>"
                                    style="width:200px;">
                            </div>
                        </td>
                    </tr>
                </table>
                <div style="padding:0 12px;">
                    <button type="submit" id="btn-save-settings" class="button button-primary">
                        <?php esc_html_e( 'Simpan Pengaturan', 'educampus' ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EVENT LIST -->
    <div class="kalender-events-list" id="kalender-events-list">
        <table class="wp-list-table widefat fixed striped" id="kalender-table">
            <thead>
                <tr>
                    <th class="column-color" style="width:50px;"><?php esc_html_e( 'Warna', 'educampus' ); ?></th>
                    <th class="column-title"><?php esc_html_e( 'Judul Event', 'educampus' ); ?></th>
                    <th class="column-type" style="width:120px;"><?php esc_html_e( 'Tipe', 'educampus' ); ?></th>
                    <th class="column-start" style="width:140px;"><?php esc_html_e( 'Tanggal Mulai', 'educampus' ); ?></th>
                    <th class="column-end" style="width:140px;"><?php esc_html_e( 'Tanggal Akhir', 'educampus' ); ?></th>
                    <th class="column-desc"><?php esc_html_e( 'Deskripsi', 'educampus' ); ?></th>
                    <th class="column-actions" style="width:100px;"><?php esc_html_e( 'Aksi', 'educampus' ); ?></th>
                </tr>
            </thead>
            <tbody id="kalender-events-tbody">
                <?php if ( empty( $events ) ) : ?>
                    <tr id="kalender-empty-row">
                        <td colspan="7" style="text-align:center;padding:40px;color:#999;">
                            <span class="dashicons dashicons-calendar" style="font-size:48px;width:48px;height:48px;display:block;margin:0 auto 12px;color:#ccc;"></span>
                            <?php esc_html_e( 'Belum ada event. Klik "Tambah Event" atau "Import Contoh" untuk memulai.', 'educampus' ); ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php
                    $type_labels = educampus_kalender_event_types();
                    foreach ( $events as $event ) :
                        $type_key  = $event['type'] ?? 'other';
                        $type_info = $type_labels[ $type_key ] ?? $type_labels['other'];
                    ?>
                    <tr data-id="<?php echo esc_attr( $event['id'] ); ?>">
                        <td>
                            <span class="kalender-color-dot" style="background:<?php echo esc_attr( $event['color'] ?: $type_info['color'] ); ?>;"></span>
                        </td>
                        <td><strong><?php echo esc_html( $event['title'] ); ?></strong></td>
                        <td><span class="kalender-type-badge" style="background:<?php echo esc_attr( $event['color'] ?: $type_info['color'] ); ?>20;color:<?php echo esc_attr( $event['color'] ?: $type_info['color'] ); ?>;"><?php echo esc_html( $type_info['label'] ); ?></span></td>
                        <td><?php echo esc_html( $event['start'] ); ?></td>
                        <td><?php echo esc_html( $event['end'] ); ?></td>
                        <td class="kalender-desc-cell"><?php echo esc_html( wp_trim_words( $event['description'] ?? '', 10 ) ); ?></td>
                        <td>
                            <button type="button" class="button button-small btn-edit-event" data-id="<?php echo esc_attr( $event['id'] ); ?>" title="Edit">
                                <span class="dashicons dashicons-edit"></span>
                            </button>
                            <button type="button" class="button button-small btn-delete-event" data-id="<?php echo esc_attr( $event['id'] ); ?>" title="Hapus" style="color:#b32d2e;">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- MODAL: Add/Edit Event -->
    <div id="kalender-modal" class="kalender-modal" style="display:none;">
        <div class="kalender-modal-overlay"></div>
        <div class="kalender-modal-content">
            <div class="kalender-modal-header">
                <h2 id="kalender-modal-title"><?php esc_html_e( 'Tambah Event Baru', 'educampus' ); ?></h2>
                <button type="button" class="kalender-modal-close" id="btn-close-modal">&times;</button>
            </div>
            <form id="kalender-event-form">
                <input type="hidden" name="event_id" id="event_id" value="">
                <div class="kalender-modal-body">
                    <div class="kalender-form-group">
                        <label for="event_title"><?php esc_html_e( 'Judul Event', 'educampus' ); ?> <span style="color:#b32d2e;">*</span></label>
                        <input type="text" name="title" id="event_title" class="regular-text" required placeholder="Contoh: Kuliah Semester Ganjil" style="width:100%;">
                    </div>

                    <div class="kalender-form-row">
                        <div class="kalender-form-group kalender-half">
                            <label for="event_start"><?php esc_html_e( 'Tanggal Mulai', 'educampus' ); ?> <span style="color:#b32d2e;">*</span></label>
                            <input type="date" name="start" id="event_start" required>
                        </div>
                        <div class="kalender-form-group kalender-half">
                            <label for="event_end"><?php esc_html_e( 'Tanggal Akhir', 'educampus' ); ?> <span style="color:#b32d2e;">*</span></label>
                            <input type="date" name="end" id="event_end" required>
                        </div>
                    </div>

                    <div class="kalender-form-row">
                        <div class="kalender-form-group kalender-half">
                            <label for="event_type"><?php esc_html_e( 'Tipe Event', 'educampus' ); ?></label>
                            <select name="type" id="event_type" style="width:100%;">
                                <?php foreach ( $types as $key => $type ) : ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" data-color="<?php echo esc_attr( $type['color'] ); ?>">
                                        <?php echo esc_html( $type['label'] ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="kalender-form-group kalender-half">
                            <label for="event_color"><?php esc_html_e( 'Warna', 'educampus' ); ?></label>
                            <input type="color" name="color" id="event_color" value="#2271b1" style="width:60px;height:36px;padding:2px;cursor:pointer;">
                        </div>
                    </div>

                    <div class="kalender-form-group">
                        <label for="event_description"><?php esc_html_e( 'Deskripsi', 'educampus' ); ?></label>
                        <textarea name="description" id="event_description" rows="3" class="large-text" placeholder="Deskripsi singkat event..." style="width:100%;"></textarea>
                    </div>
                </div>
                <div class="kalender-modal-footer">
                    <button type="button" class="button" id="btn-cancel-modal"><?php esc_html_e( 'Batal', 'educampus' ); ?></button>
                    <button type="submit" class="button button-primary" id="btn-save-event">
                        <?php esc_html_e( 'Simpan Event', 'educampus' ); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
