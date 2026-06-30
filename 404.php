<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>

<style>
    .error-page {
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--color-bg-light);
        padding: 40px 0;
    }

    .error-content {
        text-align: center;
        max-width: 520px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .error-illustration {
        margin-bottom: 1.5rem;
    }

    .error-illustration svg {
        width: 120px;
        height: 120px;
        filter: drop-shadow(0 8px 20px rgba(10,37,64,0.1));
    }

    .error-number {
        font-family: var(--font-heading);
        font-size: clamp(4rem, 12vw, 6rem);
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .error-title {
        font-family: var(--font-heading);
        font-size: clamp(1.2rem, 3vw, 1.5rem);
        font-weight: 700;
        color: var(--color-primary);
        margin-bottom: 0.5rem;
    }

    .error-description {
        font-family: var(--font-body);
        font-size: 0.95rem;
        color: var(--color-text-muted);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .error-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .error-actions .btn {
        padding: 10px 24px;
        font-family: var(--font-heading);
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 50px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .error-actions .btn-primary-custom {
        background: var(--color-primary);
        color: white;
        border: 2px solid var(--color-primary);
    }

    .error-actions .btn-primary-custom:hover {
        background: var(--color-primary-light);
        border-color: var(--color-primary-light);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(10,37,64,0.25);
        color: white;
    }

    .error-actions .btn-outline-custom {
        background: transparent;
        color: var(--color-primary);
        border: 2px solid var(--color-primary);
    }

    .error-actions .btn-outline-custom:hover {
        background: var(--color-primary);
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 576px) {
        .error-page {
            padding: 30px 0;
        }

        .error-illustration svg {
            width: 100px;
            height: 100px;
        }

        .error-actions {
            flex-direction: column;
            align-items: center;
        }

        .error-actions .btn {
            width: 100%;
            max-width: 240px;
            justify-content: center;
        }
    }
</style>

<div class="error-page">
    <div class="error-content">
        <div class="error-illustration">
            <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="40" y="60" width="120" height="100" rx="4" fill="#f0f4f8" stroke="#0a2540" stroke-width="2"/>
                <rect x="40" y="60" width="8" height="100" fill="#0a2540"/>
                <rect x="52" y="66" width="104" height="88" rx="2" fill="white"/>
                <line x1="60" y1="80" x2="148" y2="80" stroke="#e5e7eb" stroke-width="2"/>
                <line x1="60" y1="92" x2="140" y2="92" stroke="#e5e7eb" stroke-width="2"/>
                <line x1="60" y1="104" x2="132" y2="104" stroke="#e5e7eb" stroke-width="2"/>
                <circle cx="100" cy="140" r="28" fill="#c5a059" opacity="0.15"/>
                <text x="100" y="150" text-anchor="middle" fill="#0a2540" font-family="Poppins, sans-serif" font-size="36" font-weight="700">?</text>
                <circle cx="30" cy="45" r="4" fill="#c5a059" opacity="0.4"/>
                <circle cx="170" cy="55" r="3" fill="#0a2540" opacity="0.3"/>
            </svg>
        </div>

        <div class="error-number">404</div>
        <h1 class="error-title"><?php esc_html_e( 'Halaman Tidak Ditemukan', 'educampus' ); ?></h1>
        <p class="error-description">
            <?php esc_html_e( 'Halaman yang Anda cari tidak tersedia atau telah dipindahkan.', 'educampus' ); ?>
        </p>

        <div class="error-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary-custom">
                <i class="bi bi-house-door-fill"></i>
                <?php esc_html_e( 'Beranda', 'educampus' ); ?>
            </a>
            <button onclick="history.back()" class="btn btn-outline-custom">
                <i class="bi bi-arrow-left"></i>
                <?php esc_html_e( 'Kembali', 'educampus' ); ?>
            </button>
        </div>


    </div>
</div>

<?php
get_footer();