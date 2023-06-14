<?php global $user_ID; ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <?php if (header_image()) : ?>
            <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" />
        <?php endif ?>
        <a class="navbar-brand fw-bold" href="<?php bloginfo('url'); ?>"><?php bloginfo('name') ?></a><span><?php bloginfo('description') ?></span>
        <button class="navbar-toggler" type="button" aria-label="<?php _e('打开菜单', 'dale6_com') ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><?php bloginfo('name') ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php _e('关闭菜单', 'dale6_com') ?>"></button>
            </div>
            <div class="offcanvas-body d-flex">
                <ul class="navbar-nav itemlist flex-grow-1 flex-wrap">
                    <?php
                    // 输出自定义菜单,其他设置在functions内,搜索 nav_menu_link_attributes
                    // 插件CDN无法自动存储设置
                    wp_nav_menu(array(
                        'theme_location'  => 'head_nav',
                        'container'       => false,
                        'items_wrap'      => '%3$s',
                        'fallback_cb'     => 'to_zhuti_bianji_link',
                    ));
                    ?>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item ps-2">
                        <?php get_template_part('tools/sousuo_form'); ?>
                    </li>

                    <?php if (!$user_ID) : ?>
                        <li class="nav-item ps-2 pt-lg-0 pt-2">
                            <button type="button" class="btn btn-outline-dark lonin" data-bs-toggle="modal" data-bs-target="#exampleModal"><?php echo __('登录', 'dale6_com') ?></span></button>
                        </li>
                    <?php else : ?>
                        <li class="nav-item ps-2 pt-lg-0 pt-2 dropdown">
                            <?php global $user_identity; ?>
                            <button type="button" class="btn btn-outline-dark dropdown-toggle d-flex" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 dale6_com_user_ico">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($user_ID, array('size' => 24)) ?>" width="24" height="24" alt="<?php echo $user_identity; ?>">
                                </span>
                                <?php echo $user_identity ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a class="nav-link px-2" href="<?php echo admin_url(); ?> ">
                                        <?php if (current_user_can('manage_options')) : ?>
                                            <?php _e('管理页面', 'dale6_com') ?>
                                        <?php else : ?>
                                            <?php _e('个人资料', 'dale6_com') ?>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2" href="<?php echo wp_logout_url(this_url()) ?>"><?php echo __('退出', 'dale6_com') ?></a>
                                </li>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </div>
</nav>
<?php get_template_part('includes/login'); ?>