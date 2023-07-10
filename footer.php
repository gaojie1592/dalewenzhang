<div class="container py-3 mt-4 border-top d-flex flex-column">
    <div class="footerlist">
        <ul class="navbar-nav itemlist align-items-start flex-wrap justify-content-center">
            <?php
            wp_nav_menu(array(
                'theme_location'  => 'footer_nav',
                'container'       => false,
                'items_wrap'      => '%3$s',
                'fallback_cb'     => 'dalewenzhang_to_zhuti_bianji_link',
            ));
            ?>
        </ul>
    </div>
    <div class="p-2 fw-light dl_a text-center">
        <span class="text-muted">Copyright © <?php echo date("Y") ?></span>
        <a class="text-decoration-none" href="<?php echo esc_url(home_url()) ?>" target="_blank"><?php echo get_bloginfo("name", 'display') ?></a>
        <span class="text-muted"> · 基于 </span><a href="https://wordpress.org" target="_blank">WordPress</a>
        <span class="text-muted"> · </span><a class="text-decoration-none" href="https://www.dale6.com" target="_blank">使用大乐主题</a>
    </div>
</div>
<?php wp_footer() ?>