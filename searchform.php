<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<form role="search" method="get" class="search-form d-flex align-items-center" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="input-group w-100">
        <span class="input-group-text bg-transparent border-0 text-campus-muted px-2">
            <i class="bi bi-search"></i>
        </span>
        <input type="search" class="form-control border-0 shadow-none bg-transparent px-0" placeholder="<?php esc_attr_e( 'Cari...', 'educampus' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
        <button type="submit" class="search-submit btn btn-campus-primary btn-sm px-3 border-0 rounded-2"><?php esc_html_e( 'Cari', 'educampus' ); ?></button>
    </div>
</form>
