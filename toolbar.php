<?php
global $user_ID;
$sitename = get_bloginfo('name', 'display');
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <?php if (has_custom_logo()) : ?>
            <?php echo get_custom_logo() ?>
        <?php else : ?>
            <a class="navbar-brand fw-bold text-wrap" href="<?php echo esc_url(home_url()); ?>"><?php echo $sitename; ?></a>
        <?php endif; ?>
        <button class="navbar-toggler" type="button" aria-label="<?php _e('打开菜单', 'dalewenzhang') ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title text-wrap" id="offcanvasNavbarLabel"><?php echo $sitename ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php _e('关闭菜单', 'dalewenzhang') ?>"></button>
            </div>
            <div class="offcanvas-body d-flex">
                <ul class="navbar-nav itemlist flex-grow-1 flex-wrap">
                    <?php
                    wp_nav_menu(array(
                        'theme_location'  => 'head_nav',
                        'container'       => false,
                        'items_wrap'      => '%3$s',
                        'fallback_cb'     => 'dalewenzhang_to_zhuti_bianji_link',
                    ));
                    ?>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item ps-2">
                        <?php get_template_part('tools/sousuo_form'); ?>
                    </li>

                    <?php if (!$user_ID) : ?>
                        <li class="nav-item ps-2 pt-lg-0 pt-2">
                            <button type="button" class="btn btn-outline-dark dsw-60" data-bs-toggle="modal" data-bs-target="#exampleModal"><?php echo __('登录', 'dalewenzhang') ?></span></button>
                        </li>
                    <?php else : ?>
                        <li class="nav-item ps-2 pt-lg-0 pt-2 dropdown">
                            <?php global $user_identity; ?>
                            <button type="button" class="btn btn-outline-dark dropdown-toggle d-flex" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2 dalewenzhang_user_ico">
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($user_ID, array('size' => 24)) ?>" width="24" height="24" alt="<?php echo $user_identity; ?>" role="img">
                                </span>
                                <?php echo $user_identity ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li class="nav-item">
                                    <a class="nav-link px-2" href="<?php echo esc_url(admin_url()); ?> ">
                                        <?php if (current_user_can('manage_options')) : ?>
                                            <?php _e('管理页面', 'dalewenzhang') ?>
                                        <?php else : ?>
                                            <?php _e('个人资料', 'dalewenzhang') ?>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-2" href="<?php echo esc_url(wp_logout_url(dalewenzhang_this_url())) ?>"><?php _e('退出', 'dalewenzhang') ?></a>
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