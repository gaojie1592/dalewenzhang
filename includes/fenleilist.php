<nav class="bg-white vstack sticky-top d-flex">
    <ul class="navbar-nav leftlist itemlist flex-column">
        <?php
        wp_nav_menu(array(
            'theme_location'  => 'left_nav',
            'container'       => false,
            'items_wrap'      => '%3$s',
            'fallback_cb'     => 'to_zhuti_bianji_link',
        )); ?>
    </ul>
</nav>