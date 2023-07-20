<?php

/**
 * 安装主题
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return void
 */
function dalewenzhang_theme_setup()
{
    // 加载多语言
    load_theme_textdomain('dalewenzhang', get_template_directory() . '/languages');
    // 该功能在HTML <head>中增加了RSS提要链接
    add_theme_support('automatic-feed-links');
    // 在自定义主题里可定义项上显示标签
    add_theme_support('title-tag');
    // 添加文章格式支持   get_post_format( $post->ID )就能确定文章所属格式    has_post_format( 'video' )
    // $post_formats = array('aside', 'image', 'gallery', 'video', 'audio', 'link', 'quote', 'status');
    // add_theme_support('post-formats', $post_formats);
    // 开启文章特色图
    add_theme_support('post-thumbnails');
    // HTML5 特性
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'script', 'style'));
    // 这个功能可以让在定制器中管理的小部件有选择地刷新。这个功能在WordPress 4.5中是可用的。
    // add_theme_support('customize-selective-refresh-widgets');
    // 自定义背景
    $bg_defaults = array(
        'default-image'          => '',
        'default-preset'         => 'default',
        'default-size'           => 'cover',
        'default-repeat'         => 'no-repeat',
        'default-attachment'     => 'scroll',
    );
    add_theme_support('custom-background', $bg_defaults);

    // 自定义logo
    add_theme_support('custom-logo');
    // 自定义页头
    add_theme_support('custom-header');
    // 启用编辑器响应式嵌入功能
    add_theme_support('responsive-embeds');
    // 启用编辑器宽对齐功能,css样式表设置 .alignwide样式
    add_theme_support('align-wide');
    // 启用区块样式支持
    // add_theme_support('wp-block-styles');
    // 添加小工具
    add_theme_support('widgets');
    // 添加编辑小工具
    add_theme_support('widgets-block-editor');

    // register_block_pattern()
    // register_block_style();
    // add_editor_style();
}
add_action('after_setup_theme', 'dalewenzhang_theme_setup');
/**
 * 注册边栏
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return void
 */
function dalewenzhang_zhucebianlan()
{
    include_once __DIR__ . '/widget/remenbiaoqian.php';
    register_widget('remenbiaoqian');
    include_once __DIR__ . '/widget/zuixinpinglun.php';
    register_widget('zuixinpinglun');
    include_once __DIR__ . '/widget/zuixinwenzhang.php';
    register_widget('zuixinwenzhang');
    // 注册边栏
    register_sidebar(array(
        'name'          => __('右边栏', 'dalewenzhang'),
        'id'            => 'youbianlan',
        'description'   => __('该区域的小工具将显示在所有帖子和页面上。', 'dalewenzhang'),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ));
    register_sidebar(array(
        'name'          => __('下边栏', 'dalewenzhang'),
        'id'            => 'xiabianlan',
        'description'   => __('该区域的小工具将显示在所有帖子和页面上。', 'dalewenzhang'),
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ));
}
add_action('widgets_init', 'dalewenzhang_zhucebianlan');
/**
 * 在自定义主题自定义选项中添加自定义头部设置选项
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return void
 */
function dalewenzhang_customize_register($wp_customize)
{
    // 页头设置选项
    $wp_customize->add_section('header_image', array(
        'title'    => __('页头设置', 'dalewenzhang'),
        'priority' => 20,
    ));

    $wp_customize->add_setting('header_image_placement', array(
        'default'           => 'no',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('header_image_placement', array(
        'label'    => __('选择图像位置', 'dalewenzhang'),
        'section'  => 'header_image',
        'type'     => 'select',
        'choices'  => array(
            'no'      => __('关闭图像', 'dalewenzhang'),
            'above'   => __('头部上方', 'dalewenzhang'),
            'below'   => __('头部下方', 'dalewenzhang'),
            'beijing' => __('头部背景', 'dalewenzhang'),
        ),
    ));

    $wp_customize->add_setting('header_image_link', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_link_field',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_image_link', array(
        'label'    => __('图像链接', 'dalewenzhang'),
        'section' => 'header_image',
        'settings' => 'header_image_link',
        'type' => 'text',
    )));

    $wp_customize->add_setting('header_beijing_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_beijing_color', array(
        'label'    => __('背景颜色', 'dalewenzhang'),
        'section' => 'header_image',
        'settings' => 'header_beijing_color',
        'type' => 'color',
    )));

    $wp_customize->add_setting('header_height', array(
        'default'           => 80,
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('header_height', array(
        'label'    => __('调整头部高度(px)', 'dalewenzhang'),
        'section'  => 'header_image',
        'settings' => 'header_height',
        'type'     => 'number',
    ));

    $wp_customize->add_setting('displa_description', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('displa_description', array(
        'label'    => __('显示副标题', 'dalewenzhang'),
        'section'  => 'header_image',
        'settings' => 'displa_description',
        'type'     => 'checkbox',
    ));
}
add_action('customize_register', 'dalewenzhang_customize_register');

/**
 * 自定义头部
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return void
 */
function dalewenzhang_custom_header_fun()
{
    $custom_header = get_custom_header();
    if ($custom_header) {
        $header_image_url = $custom_header->url;
    } else {
        $header_image_url = get_theme_support('custom-header');
    }

    $header_image_placement = get_theme_mod('header_image_placement');
    $beijingstyle = '';
    if ($header_image_placement === 'beijing') {
        $beijingstyle = $header_image_url ? 'background-image:url(' . esc_url($header_image_url) . ');background-size:cover;background-repeat:no-repeat;background-position:center center;' : '';
    }
    $header_height = get_theme_mod('header_height');

    $header_beijing_color = get_theme_mod('header_beijing_color');
    if ($header_beijing_color) {
        $beijingstyle = 'background-color:' . $header_beijing_color . '!important;';
    }

    $header_image_link = get_theme_mod('header_image_link');
    if ($header_image_link) {
        $divimghtml = $header_image_url ? '<a href="' . esc_url($header_image_link) . '" target="_blank"><img src="' . esc_url($header_image_url) . '" class="img-fluid w-100" alt="head_img"></a>' : '';
    } else {
        $divimghtml = $header_image_url ? '<img src="' . esc_url($header_image_url) . '" class="img-fluid w-100" alt="head_img">' : '';
    }

    $head_text_color = get_header_textcolor();
    if ($head_text_color) {
        echo '<style>.yetou a,.yetou button,.yetou h1,.yetou h5{color:#' . $head_text_color . '!important;}</style>';
    }

    if ($header_image_placement === 'above') echo $divimghtml;
?>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-0 yetou" style="height:<?php echo $header_height; ?>px;<?php echo $beijingstyle; ?>">
        <div class="container">
            <?php if (has_custom_logo()) : ?>
                <?php echo get_custom_logo(); ?>
            <?php endif; ?>
            <?php if (display_header_text()) : ?>
                <a class="navbar-brand" href="<?php echo esc_url(home_url()); ?>" aria-label="<?php _e('跳转到首页', 'dalewenzhang') ?>">
                    <h1 class="fs-3 fw-bold text-center text-wrap mb-0"><?php echo get_bloginfo('name', 'display') ?></h1>
                    <?php if (get_theme_mod('displa_description')) : ?>
                        <h5 class="fs-6 text-muted text-wrap mb-0"><?php echo get_bloginfo('description', 'display') ?></h5>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            <button class="navbar-toggler" type="button" aria-label="<?php _e('打开菜单', 'dalewenzhang') ?>" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title text-wrap" id="offcanvasNavbarLabel"><?php echo get_bloginfo('name', 'display') ?></h5>
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
                            <button aria-label="<?php _e('搜索按钮', 'dalewenzhang') ?>" class="btn btn-outline-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop"><i class="bi bi-search"></i></button>
                        </li>
                        <?php if (!is_user_logged_in()) : ?>
                            <li class="nav-item ps-2 pt-lg-0 pt-2">
                                <a class="btn btn-outline-dark dsw-60" href="<?php echo esc_url(wp_login_url(get_site_url() . $_SERVER['REQUEST_URI'])); ?>">
                                    <?php echo __('登录', 'dalewenzhang') ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
<?php
    if ($header_image_placement === 'below') echo $divimghtml;
}
add_action('dalewenzhang_custom_header', 'dalewenzhang_custom_header_fun');
/**
 * 自定义块
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return void
 */
function dalewenzhang_register_my_patterns()
{
    $login_url = wp_login_url();
    register_block_pattern(
        'dalewenzhang/loginbutton',
        array(
            'title'       => __('登录', 'dalewenzhang'),
            'description' => __('一个登录链接按钮', 'dalewenzhang'),
            'categories'  => array('text'),
            'content'     =>  '<!-- wp:paragraph -->
            <a href="' . $login_url . '">登录</a>
            <!-- /wp:paragraph -->',
        )
    );
}
add_action('init', 'dalewenzhang_register_my_patterns');
// 添加JS到首页 
if (!function_exists('dalewenzhang_script')) {
    function dalewenzhang_script()
    {
        wp_enqueue_style('dalewenzhang_css_bootstrap', esc_url(get_template_directory_uri()) . "/css/bootstrap.min.css");
        wp_enqueue_style('dalewenzhang_css_bootstrap_icon', esc_url(get_template_directory_uri()) . "/css/bootstrap-icons.min.css");
        wp_enqueue_style('dalewenzhang_css_dale6', esc_url(get_template_directory_uri()) . "/css/dalewenzhang_styles_index.css");
        wp_enqueue_script('dalewenzhang_js_bootstrap', esc_url(get_template_directory_uri()) . '/js/bootstrap.bundle.min.js');
        wp_enqueue_script('dalewenzhang_js_index', esc_url(get_template_directory_uri()) . '/js/dalewenzhang_index.js');
        // 在回复下面直接评论
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
add_action('wp_enqueue_scripts', 'dalewenzhang_script');

// 限制文章内容输出简介
add_filter('dalewenzhang_the_excerpt', function ($content) {
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = mb_strimwidth(strip_tags($content), 0, 100, "...");
    return $content;
});


/**
 * 递归添加子评论到父评论
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param array    $comments    父评论
 * @param array    $comment    子评论
 * @return array   $comments
 */
function dalewenzhang_add_comment_children($comments, $comment)
{
    foreach ($comments as &$v) {
        if ($v->comment_ID == $comment->comment_parent) {
            $v->children[$comment->comment_ID] = $comment;
            break;
        }
        if (!empty($v->children) && count($v->children) > 0) {
            $v->children = dalewenzhang_add_comment_children($v->children, $comment);
        }
    }
    return $comments;
}

/**
 * 输出评论
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param object   $comment  当前输出评论对象
 * @param array    $args     参数数组
 * @param int      $depth    当前深度层数
 * @return void    输出结果
 */
function dalewenzhang_echo_comment($comment, $args, $depth)
{
    global $post;
    $author = $comment->user_id == $post->post_author ? true : false;
    if ('div' === $args['style']) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    $imgh = !empty($comment->comment_parent) ? 24 : 40;
    if (!empty($comment->comment_parent) && $user_info = get_userdata(get_comment($comment->comment_parent)->user_id)) {
        $patent = true;
    } else {
        $patent = false;
    }
    echo '<' . $tag . ' ' . comment_class((empty($args['has_children']) ? '' : 'parent') . ' pt-3 border-top', null, null, false) . ' id="comment-' . get_comment_ID() . '">';
?>
    <?php if ('div' != $args['style']) echo '<div id="div-comment-' . get_comment_ID() . '" class="comment-body">'; ?>

    <div class="d-flex align-items-center">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0 dalewenzhang_user_ico">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($comment->user_id, array('size' => $imgh)) ?>" width="<?php echo $imgh ?>" height="<?php echo $imgh ?>" alt="<?php echo dalewenzhang_get_display_name($comment) ?>" role="img">
            </div>
            <div class="flex-grow-1 d-flex flex-column ms-3">
                <div class="d-flex align-items-center">
                    <div class="d-inline-block text-truncate w-150px">
                        <a href="<?php echo esc_url(get_author_posts_url($comment->user_id)); ?>" title="<?php echo esc_attr(dalewenzhang_get_display_name($comment->comment_author)); ?>"><?php echo dalewenzhang_get_display_name($comment); ?></a>
                    </div>
                    <?php if ($author) : ?>
                        <span class="badge rounded-pill text-bg-dark"><?php _e('作者', 'dalewenzhang') ?></span>
                    <?php endif; ?>
                </div>
                <?php if (empty($comment->comment_parent)) : ?>
                    <div class="text-muted lh-1">
                        <?php echo dalewenzhang_jiange_time(__('评论于', 'dalewenzhang'), $comment->comment_date, __('前', 'dalewenzhang')) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($patent) : ?>
            <div class="px-3">
                <?php _e('回复', 'dalewenzhang'); ?>
            </div>
            <div class="d-flex align-items-center">
                <div class="dalewenzhang_user_ico">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($user_info->ID, array('size' => 24)) ?>" width="24" height="24" alt="<?php echo dalewenzhang_get_display_name($user_info) ?>" role="img">
                </div>
                <div class="d-flex align-items-center ms-3">
                    <div class="d-inline-block text-truncate w-150px">
                        <a href="<?php echo esc_url(get_author_posts_url($user_info->ID)); ?>" title="<?php echo dalewenzhang_get_display_name($user_info) ?>"><?php echo dalewenzhang_get_display_name($user_info) ?></a>
                    </div>
                    <?php if ($user_info->ID == $post->post_author) : ?>
                        <span class="badge rounded-pill text-bg-dark"><?php _e('作者', 'dalewenzhang') ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($comment->comment_parent)) : ?>
            <div class="text-muted lh-1 ps-3">
                <?php echo dalewenzhang_jiange_time(__('评论于', 'dalewenzhang'), $comment->comment_date, __('前', 'dalewenzhang')) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="d-flex flex-column">
        <?php if ('0' == $comment->comment_approved) : ?>
            <div class="pt-3"><?php _e('您的评论正在等待审核.', 'dalewenzhang'); ?></div>
        <?php else : ?>
            <div class="pt-3 overflow-hidden comment-text">
                <?php comment_text(); ?>
            </div>
            <div class="d-flex align-items-center">
                <?php edit_comment_link(__('编辑', 'dalewenzhang'), '<span class="text-muted"><i class="bi bi-pencil-square pe-2"></i>', '</span>'); ?>
                <?php comment_reply_link(array_merge($args, array(
                    'add_below' => $add_below,
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="ps-2 text-muted"><i class="bi bi-chat-square-dots pe-2"></i>',
                    'after'     => '</span>',
                ))); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ('div' != $args['style']) echo '</div>' ?>
    <?php
}
/**
 * 跳转到主题编辑link
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return void
 */
function dalewenzhang_to_zhuti_bianji_link()
{
    // global $dalewenzhang_admin;
    if (current_user_can('manage_options')) :
    ?>
        <li class="nav-item text-center">
            <a class="nav-link dl_a" href="<?php echo wp_customize_url() ?>">
                <?php _e('跳转到主题编辑添加菜单！', 'dalewenzhang'); ?>
            </a>
        </li>
<?php
    endif;
}

/**
 * 返回详细距离时间 get_option('gmt_offset')获取时区间隔int
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param string $qianzui 结果前缀显示字符串
 * @param string $date    时间戳
 * @param string $houzui  结果前缀显示字符串
 * @return string
 */
function dalewenzhang_jiange_time($qianzui, $date, $houzui)
{
    $format = get_option('date_format');
    $time = strtotime(wp_date('Y-m-d H:i:s')) - strtotime($date);
    if ($time <= 0) return $qianzui . $time . __('秒', 'dalewenzhang') . $houzui;
    switch ($time) {
        case $time > 0 && $time < 60:
            return $qianzui . $time . __('秒', 'dalewenzhang') . $houzui;
        case $time > 59 && $time < 3600:
            return $qianzui . ceil($time / 60) . __('分钟', 'dalewenzhang') . $houzui;
        case $time > 3600 && $time < 86400:
            return $qianzui . ceil($time / 3600) . __('小时', 'dalewenzhang') . $houzui;
        case $time > 86400 && $time < 604800:
            return $qianzui . ceil($time / 86400) . __('天', 'dalewenzhang') . $houzui;
        case $time > 604800 && $time < 2592000:
            return $qianzui . ceil($time / 604800) . __('星期', 'dalewenzhang') . $houzui;
        case $time > 2592000 && $time < 31536000:
            return $qianzui . ceil($time / 2592000) . __('个月', 'dalewenzhang') . $houzui;
        case $time > 31536000 && $time < 94608000:
            return $qianzui . ceil($time / 31536000) . __('年', 'dalewenzhang') . $houzui;
        default: //超过3年返回具体时间
            return date($format, strtotime($date));
    }
}

// 注册菜单
register_nav_menus(array(
    'head_nav'   => __('页头', 'dalewenzhang'),
    'left_nav'   => __('首页左', 'dalewenzhang'),
    'footer_nav' => __('页脚', 'dalewenzhang'),
));

/**
 * 返回可用用户名
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param array|object 参数
 * @return string
 */
function dalewenzhang_get_display_name($object)
{
    if (!empty($object->comment_author)) return $object->comment_author;
    if (!empty($object->display_name)) return $object->display_name;
    if (!empty($object->user_nickname)) return $object->user_nickname;
    if (!empty($object->user_login)) return $object->user_login;
    $userid = 0;
    if (!empty($object->post_author)) $userid = $object->post_author;
    if (!empty($object->user_id)) $userid = $object->user_id;
    $object = get_userdata($userid);
    if (!empty($object->display_name)) return $object->display_name;
    if (!empty($object->user_nickname)) return $object->user_nickname;
    if (!empty($object->user_login)) return $object->user_login;
    return __('未知用户', 'dalewenzhang');
}
// 空标题处理
add_filter('dalewenzhang_the_title', function ($title) {
    $title = trim($title);
    if (empty($title)) return __('未知标题', 'dalewenzhang');
    return $title;
});
