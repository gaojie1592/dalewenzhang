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
    /** HTML5 support **/
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
    // 自定义页头
    // add_theme_support('custom-header');
    // 自定义logo
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'dalewenzhang_theme_setup');

// 添加JS到首页 
if (!function_exists('dalewenzhang_script')) {
    function dalewenzhang_script()
    {
        wp_enqueue_style('dalewenzhang_css_bootstrap', esc_url(get_template_directory_uri()) . "/css/bootstrap.min.css");
        wp_enqueue_style('dalewenzhang_css_bootstrap_icon', esc_url(get_template_directory_uri()) . "/css/bootstrap-icons.min.css");
        wp_enqueue_style('dalewenzhang_css_dale6', esc_url(get_template_directory_uri()) . "/css/dalewenzhang_styles_index.css");
        wp_enqueue_script('dalewenzhang_js_bootstrap', esc_url(get_template_directory_uri()) . '/js/bootstrap.bundle.min.js');
        wp_enqueue_script('dalewenzhang_js_index', esc_url(get_template_directory_uri()) . '/js/dalewenzhang_index.js');
    }
}
add_action('wp_enqueue_scripts', 'dalewenzhang_script');

/**
 * 去除评论所有HTML标记,只保留code标签，再加上pre标签用来代码美化
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param string    $comment_text    评论
 * @param bool      $deltags         是否删除html标签
 * @return string   $comment_text
 */
function dalewenzhang_pinglun_guolv($comment_text, $deltags = true)
{
    if ($deltags) $comment_text = strip_tags($comment_text, array('<code>'));
    // 正则获取 <code> 之内的数据，在外围添加<pre></pre>
    $code_zz = "/<code>(.*?)<\/code>/is";
    if (preg_match($code_zz, $comment_text)) {
        preg_match_all($code_zz, $comment_text, $jg);
        // 给代码内容添加code pre
        if (count($jg[0]) > 0 && !empty($jg[0][0])) {
            // 先处理相同数据
            $jg[0] = array_unique($jg[0]);
            $jg[1] = array_unique($jg[1]);
            $un = array();
            foreach ($jg[0] as $k => $v) {
                // 唯一字符
                $un[$k] = '[' . md5(microtime(true) . $k) . ']';
                // 先替换为唯一字符
                $comment_text = str_ireplace($v, $un[$k], $comment_text);
            }
            if ($deltags) $comment_text = strip_tags($comment_text);
            $comment_text = dalewenzhang_text_add_tags($comment_text, $un);
            foreach ($jg[0] as $k => $v) {
                $t = htmlentities(trim($jg[1][$k]));
                $comment_text = str_ireplace($un[$k], '<pre><code>' . $t . '</code></pre>', $comment_text);
            }
        }
    } else {
        $comment_text = dalewenzhang_text_add_tags($comment_text);
    }
    return $comment_text;
}
/**
 * 给文本添加p标签,有排除字符或数组
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param string $text 待处理的文本字符串
 * @param string[]|string $paichu 需要排除的字符串或字符串数组
 * @return string 处理后的字符串
 */
function dalewenzhang_text_add_tags($text, $paichu = '')
{
    $tbj = '<rn|n>';
    $text = str_ireplace(array("\r\n", "\n"), $tbj, trim($text));
    if (stripos($text, $tbj) !== false) {
        $tarr = array();
        $tjg = explode($tbj, $text);
        $tjg = array_unique(array_filter($tjg));
        foreach ($tjg as $k => $v) {
            if (is_array($paichu) && count($paichu) > 0) {
                foreach ($paichu as $v1) {
                    if (stripos($v, $v1) !== false) {
                        $v = str_replace($v1, $tbj . $v1 . $tbj, $v);
                    }
                }
            }
            if (is_string($paichu) && !empty($paichu)) {
                if (stripos($v, $paichu) !== false) {
                    $v = str_replace($paichu, $tbj . $paichu . $tbj, $v);
                }
            }
            if (stripos($v, $tbj) !== false) {
                $tarr = array_merge($tarr, explode($tbj, $v));
            } else {
                $tarr[] = $v;
            }
        }
        $tjg = array();
        $tarr = array_unique(array_filter($tarr));
        foreach ($tarr as $k => $v) {
            if (is_array($paichu) && count($paichu) > 0) {
                foreach ($paichu as $v1) {
                    if ($v == $v1) {
                        $tjg[] = $v;
                        continue 2;
                    }
                }
            }
            if (is_string($paichu) && !empty($paichu)) {
                if ($v == $paichu) {
                    $tjg[] = $v;
                    continue;
                }
            }
            $tjg[] =  '<p>' . $v . '</p>';
        }
        $text = implode('', $tjg);
    }
    return $text;
}
/**
 * 评论文字长度警告
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param string         $comment_text    评论
 * @return string|void   $comment_text
 */
function dalewenzhang_pinglun_text_post($comment_text)
{
    if (strlen($comment_text) < 20) {
        wp_die(__('评论过短，请控制在超过20字符上！', 'dalewenzhang'));
    }
    if (strlen($comment_text) > 5000) {
        wp_die(__('评论过长，请控制在不超过5000字符内！', 'dalewenzhang'));
    }
    return dalewenzhang_pinglun_guolv($comment_text);
}
/**
 * 评论文字长度限制
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param string    $comment_text    评论
 * @return string   $comment_text
 */
function dalewenzhang_pinglun_text($comment_text)
{
    if (strlen($comment_text) > 5000) {
        $comment_text = mb_strimwidth($comment_text, 0, 5000, '...');
    }
    return $comment_text;
}

// 文章内容输出简介
add_filter('dalewenzhang_the_excerpt', function ($content) {
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = mb_strimwidth(strip_tags($content), 0, 100, "...");
    return $content;
});

// 在评论数据被清理并插入数据库之前过滤它
add_filter('preprocess_comment', function ($comment) {
    $comment['comment_content'] = dalewenzhang_pinglun_text_post($comment['comment_content']);
    return $comment;
});
// 更新评论
add_filter('comment_save_pre', 'dalewenzhang_pinglun_text_post');
// 输出评论内容 
add_filter('comment_text', 'dalewenzhang_pinglun_text');
// 显示在提要中使用的当前评论内容。 
add_filter('comment_text_rss', 'dalewenzhang_pinglun_text');
// 过滤评论摘录
add_filter('comment_excerpt', 'dalewenzhang_pinglun_text');

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
    if (!empty($comment->comment_parent) && $user_info = get_userdata($comment->comment_parent)) {
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
 * 编辑评论回复link
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return string 编辑后的链接
 */
function dalewenzhang_edit_reply_link($link, $args)
{
    if (get_option('comment_registration') && !is_user_logged_in()) {

        $link = $args['before'] . sprintf(
            '<a rel="nofollow" class="comment-reply-login" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModal">%s</a>',
            $args['login_text']
        ) . $args['after'];
    }
    return $link;
}
add_filter('comment_reply_link', 'dalewenzhang_edit_reply_link', 10, 2);
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

add_filter('nav_menu_css_class', 'dalewenzhang_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'dalewenzhang_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'dalewenzhang_css_attributes_filter', 100, 1);
add_filter('nav_menu_link_attributes', 'dalewenzhang_sonliss_menu_link_atts');

/**
 * 移除菜单的多余CSS选择器,给菜单a标签添加class
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param array|null 参数
 * @return array|null|string
 */
function dalewenzhang_css_attributes_filter($var)
{
    return is_array($var) ? array('nav-item', 'text-center') : '';
}

/**
 * 修改菜单class样式
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param array 参数
 * @return array
 */
function dalewenzhang_sonliss_menu_link_atts($atts)
{
    $atts['class'] = 'nav-link';
    return $atts;
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


/**
 * 限制博客信息长度
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param  string 将要输出的字符串
 * @param  string 将要输出的类型
 * @return string 限制长度后的字符串 
 */
function dalewenzhang_xianzhi_info($output, $show)
{
    // 限制网站名称长度为20字符
    // if ($show == 'name') $output = mb_substr($output, 0, 20, 'utf8');
    // 限制网站介绍长度为200字符
    if ($show == 'description') $output = mb_substr($output, 0, 200, 'utf8');
    return $output;
}
add_filter('bloginfo', 'dalewenzhang_xianzhi_info', 10, 2);

/**
 * 修改登录表单CLASS样式
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return string
 */
function dalewenzhang_login_form($loginformstr)
{
    $loginformstr = preg_replace(array(
        '/<p class="login-username">/is',
        '/<p class="login-username(.*?)class="input"(.*?)<\/p>/is',
        '/<p class="login-password">/is',
        '/<p class="login-password(.*?)class="input"(.*?)<\/p>/is',
        '/<p class="login-submit">/is',
        '/<p class="login-submit(.*?)class="button button-primary"(.*?)<\/p>/is',
        '/<p class="login-username(.*?)">(.*?)<label(.*?)<\/label>(.*?)<\/p>/is',
        '/<p class="login-password(.*?)">(.*?)<label(.*?)<\/label>(.*?)<\/p>/is',
        '/<p class="login-username(.*?)<input(.*?)class="(.*?)"(.*?)<\/p>/is',
        '/<p class="login-password(.*?)<input(.*?)class="(.*?)"(.*?)<\/p>/is',
    ), array(
        '<p class="login-username form-floating mb-4">',
        '<p class="login-username$1class="input form-control"$2</p>',
        '<p class="login-password form-floating mb-4">',
        '<p class="login-password$1class="input form-control"$2</p>',
        '<p class="login-submit mb-4 d-grid">',
        '<p class="login-submit$1class="btn btn-success"$2</p>',
        '<p class="login-username$1">$2$4<label$3</label></p>',
        '<p class="login-password$1">$2$4<label$3</label></p>',
        '<p class="login-username$1<input$2 placeholder="username" class="$3"$4</p>',
        '<p class="login-password$1<input$2 placeholder="password" class="$3"$4</p>',
    ), $loginformstr);
    return $loginformstr;
}
add_filter('dalewenzhang_login_form', 'dalewenzhang_login_form');
/**
 * 追加注册链接
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return string
 */
function dalewenzhang_login_form_bottom($str)
{
    return wp_register('', '', false);
}
add_filter('login_form_bottom', 'dalewenzhang_login_form_bottom');
/**
 * 获取当前页面url
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return string
 */
function dalewenzhang_this_url()
{
    $current_url = home_url(add_query_arg(array()));
    if (is_single()) {
        $current_url = preg_replace('/(\/comment|page|#).*$/', '', $current_url);
    } else {
        $current_url = preg_replace('/(comment|page|#).*$/', '', $current_url);
    }
    return $current_url;
}
add_action('login_head', function () {
    echo '<style type="text/css">#login h1 a {background-image:unset;display:contents;}#login form{background:unset;border:unset;box-shadow:unset;}</style>';
});

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

add_filter('login_headerurl', function () {
    return home_url();
});


add_filter('dalewenzhang_the_title', function ($title) {
    $title = trim($title);
    if (empty($title)) return __('未知标题', 'dalewenzhang');
    return $title;
});

/**
 * 修改 pingback 自动评论内容格式
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @param string $comment 评论内容
 * @return string
 */
function dalewenzhang_pingback_or_trackback_comment_content($comment)
{
    $comment_content = '<a href="' . $comment->comment_author_url . '" target="_blank" rel="noopener noreferrer nofollow">' . __('很荣幸转载了你的文章!感谢!', 'dalewenzhang') . '</a>';
    return $comment_content;
}
add_filter('dalewenzhang_pingback_or_trackback_comment_content', 'dalewenzhang_pingback_or_trackback_comment_content');

/**
 * 修改 pingback 自动评论显示名称
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return string
 */
function dalewenzhang_pingback_or_trackback_display_name($comment)
{
    $display_name = parse_url($comment->comment_author_url, PHP_URL_HOST);
    return $display_name;
}
add_filter('dalewenzhang_pingback_or_trackback_display_name', 'dalewenzhang_pingback_or_trackback_display_name');


/**
 * 角色注销后跳转地址
 * @author dale6.com <gaojie11@163.com>
 * @since 1.0.0
 * @return string 返回结果
 */
function dalewenzhang_logout_redirect($logouturl)
{
    $outurl = dalewenzhang_this_url();
    return $logouturl . '&redirect_to=' . urlencode($outurl);
}
add_filter('logout_url', 'dalewenzhang_logout_redirect');
