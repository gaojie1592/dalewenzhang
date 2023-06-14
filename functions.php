<?php
if (!session_id()) {
    session_start();
} else {
    session_write_close();
}
// 管理权限
$admin = current_user_can('manage_options');
// 管理页面
$is_admin = is_admin();
// 全局变量,一次拿到所有值保存到变量
$g_dale6_com = get_theme_mods();
// 默认头像背景颜色
define('DALE6_COM_PINGLUN_TX_COLOR', array("#778ca3", "#F57F17", "#5ec162", "#9575CD", "#999", "#00BCD4", "#c57c3b", "#6D4C41", "#5C6BC0", "#FBC02D", "#45aaf2", "#757575", "#EF5350", "#7986CB", "#2bcbba", "#37474F", "#546E7A", "#00838F", "#FFD54F", "#607D8B"));
$options = get_option('dale6_com_setting');

// 加载多语言
// add_action('after_setup_theme', 'wpdocs_theme_setup'); 
// function wpdocs_theme_setup(){
//     load_theme_textdomain('wpdocs_theme', get_template_directory() . '/languages');
// }

// 关闭顶部动作条
show_admin_bar(false);
// 添加JS到首页 
if (!function_exists('dale6_com_script')) {
    function dale6_com_script()
    {
        wp_enqueue_style('dale6_com_css_bootstrap', get_template_directory_uri() . "/css/bootstrap.min.css");
        wp_enqueue_style('dale6_com_css_dale6', get_template_directory_uri() . "/css/dale6_com_styles_index.css");
        wp_enqueue_script('dale6_com_js_bootstrap', get_template_directory_uri() . '/js/bootstrap.bundle.min.js');
        wp_enqueue_script('dale6_com_js_index', get_template_directory_uri() . '/js/dale6_com_index.js');
    }
}
add_action('wp_enqueue_scripts', 'dale6_com_script');

// 去除评论所有HTML标记,只保留code标签，再加上pre标签用来代码美化
function pinglun_guolv($comment_text, $deltags = true)
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
            $comment_text = text_add_tags($comment_text, $un);
            foreach ($jg[0] as $k => $v) {
                $t = htmlentities(trim($jg[1][$k]));
                $comment_text = str_ireplace($un[$k], '<pre><code>' . $t . '</code></pre>', $comment_text);
            }
        }
    } else {
        $comment_text = text_add_tags($comment_text);
    }
    return $comment_text;
}
/**
 * 给文本添加p标签,有排除字符或数组
 * @param string $text 待处理的文本字符串
 * @param string[]|string $paichu 需要排除的字符串或字符串数组
 * @return string 处理后的字符串
 */
function text_add_tags($text, $paichu = '')
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
function pinglun_text_post($comment_text)
{
    if (strlen($comment_text) < 20) {
        wp_die(__('评论过短，请控制在超过20字符上！', 'dale6_com'));
    }
    if (strlen($comment_text) > 5000) {
        wp_die(__('评论过长，请控制在不超过5000字符内！', 'dale6_com'));
    }
    return pinglun_guolv($comment_text);
}
function pinglun_text($comment_text)
{
    if (strlen($comment_text) > 5000) {
        $comment_text = mb_strimwidth($comment_text, 0, 5000, '...');
    }
    return $comment_text;
}
// function pinglun_text($comment_text, $comment = null)
// {
//     // 评论存入数据库
//     // if (null === $comment) file_put_contents(__DIR__ . '/CS', 'SAVE' . PHP_EOL . $comment_text . PHP_EOL, FILE_APPEND);
//     // if (null !== $comment) file_put_contents(__DIR__ . '/CS', 'SHOW' . PHP_EOL . $comment_text . PHP_EOL, FILE_APPEND);
//     // $comment_text = pinglun_guolv($comment_text);
//     $comment_text = str_ireplace(array("\r\n", "\n"), '<br>', $comment_text);
//     return $comment_text;
// }
// function content_text_guolv($content)
// {
//     return pinglun_guolv($content, false);
// }

// 文章ID输出文章top
add_filter('dale6_com_post_top', function ($postid) {
    $tmp = get_post_meta($postid, 'dale6_com_post_top', true);
    if (!is_array($tmp)) $tmp = array();
    $post_top_sum = $tmp ? array_sum($tmp) : 0;
    return $post_top_sum;
});
// 文章内容输出简介
add_filter('dale6_com_the_excerpt', function ($content) {
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $content = mb_strimwidth(strip_tags($content), 0, 100, "...");
    return $content;
});

// 在评论数据被清理并插入数据库之前过滤它
add_filter('preprocess_comment', function ($comment) {
    $comment['comment_content'] = pinglun_text_post($comment['comment_content']);
    return $comment;
});
// 保存评论 comment_post
// 更新评论
add_filter('comment_save_pre', 'pinglun_text_post');

// 输出评论内容 
add_filter('comment_text', 'pinglun_text');
// 显示在提要中使用的当前评论内容。 
add_filter('comment_text_rss', 'pinglun_text');
// 过滤评论摘录
add_filter('comment_excerpt', 'pinglun_text');
//修改默认头像
add_filter('avatar_defaults', 'default_avatar');
function default_avatar($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/beiantubiao_j.png'; //图文url路径
    $avatar_defaults[$myavatar] = "大乐主题默认图标"; //图片的描述名称
    return $avatar_defaults;
}
/**
 * 添加子评论到父评论
 */
function dale6_com_add_comment_children($comments, $comment)
{
    foreach ($comments as &$v) {
        if ($v->comment_ID == $comment->comment_parent) {
            $v->children[$comment->comment_ID] = $comment;
            break;
        }
        if (!empty($v->children) && count($v->children) > 0) {
            $v->children = dale6_com_add_comment_children($v->children, $comment);
        }
    }
    return $comments;
}
function dale6_com_echo_comment($comments, $post, $user, $is_children = false)
{
    $is_login = $user->exists();
    foreach ($comments as $comment) :
        $author = $comment->user_id == $post->post_author ? true : false;
?>
        <div id="comment-<?php echo $comment->comment_ID; ?>" class="pt-3 border-top<?php echo $is_children ? ' children' : '' ?>">
            <div class="d-flex flex-column">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 dale6_com_user_ico">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($comment->user_id, array('size' => 40)) ?>" width="40" height="40" alt="<?php echo dale6_com_get_display_name($comment) ?>">
                    </div>
                    <div class="flex-grow-1 d-flex flex-column ms-3">
                        <div class="d-flex">
                            <div class="d-inline-block text-truncate w-150px">
                                <?php if ('0' == $comment->comment_approved) : ?>
                                    <?php _e('待审核', 'dale6_com') ?><?php _e('作者', 'dale6_com') ?>
                                <?php else : ?>
                                    <?php if ($comment->comment_type == 'pingback' || $comment->comment_type == 'trackback') : ?>
                                        <?= mb_substr(dale6_com_get_display_name($comment), 0, 50, 'utf8'); ?>
                                    <?php else : ?>
                                        <a href="<?php echo get_author_posts_url($comment->user_id) ?>"><?= mb_substr(dale6_com_get_display_name($comment), 0, 50, 'utf8'); ?></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($author) : ?>
                                <span class="badge rounded-pill text-bg-dark"><?php _e('作者', 'dale6_com') ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted lh-1">
                            <?php echo jiange_time(__('评论于', 'dale6_com'), $comment->comment_date, __('前', 'dale6_com')) ?>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <?php if ('0' == $comment->comment_approved) : ?>
                        <div class="pt-3"><?php _e('您的评论正在等待审核.', 'dake6_com'); ?></div>
                    <?php else : ?>
                        <div class="pt-3 overflow-hidden comment-text">
                            <a rel="nofollow" class="btn btn-sm border w-100 bg-secondary-subtle comment-btn" style="display:none;"><?php _e('评论过长,点击展开', 'dale6_com') ?><?php _e('展开', 'dale6_com') ?></a>
                            <?= apply_filters('comment_text', $comment->comment_content) ?>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="pe-2">
                                <?php do_action('dale6_com_top_or_down_button', $is_login, $comment->meta_value, $comment->comment_ID, 'pinglun'); ?>
                            </div>
                            <div class="ps-2">
                                <span class="text-muted">
                                    <?php
                                    // 显示评论的回复链接，已打开必须注册才能回复，未登录
                                    if (get_option('comment_registration') && !$is_login) {
                                        echo '<a rel="nofollow" class="btn btn-link btn-sm ps-0" data-bs-toggle="modal" data-bs-target="#exampleModal">' . __('登录后回复', 'dake6_com') . '</a>';
                                    } else {
                                        echo sprintf(
                                            "<a rel='nofollow' class='btn btn-link btn-sm' href='%s' aria-label='%s'>%s</a>",
                                            esc_url(
                                                add_query_arg(
                                                    array(
                                                        'replytocom'      => $comment->comment_ID,
                                                        'unapproved'      => false,
                                                        'moderation-hash' => false,
                                                    ),
                                                    get_permalink($post->ID)
                                                )
                                            ) . '#respond',
                                            $comment->comment_author,
                                            __('回复', 'dake6_com')
                                        );
                                    }
                                    ?>
                                </span>
                                <?php if ($is_login && $comment->user_id == $user->ID) : ?>
                                    <span class="text-muted">
                                        <a rel="nofollow" class="btn btn-link btn-sm" href="<?= admin_url('comment.php?action=editcomment&amp;c=') . $comment->comment_ID ?>"><?php _e('编辑', 'dake6_com'); ?></a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (!empty($comment->children)) : ?>
                <?php dale6_com_echo_comment($comment->children, $post, $user, true); ?>
            <?php endif; ?>
        </div>
    <?php endforeach;
}

/**
 * 输出点赞与踩图标
 * @param string|int    $tval  显示的结果
 * @param string|int|false    $tid 唯一id,一般是文章ID或者评论ID
 * @param string $type 'pinglun' 'post' 二选一
 * @return void  输出结果
 */
add_action('dale6_com_top_or_down_button', function ($bool, $tval, $tid, $type) {
    $ding = __('赞', 'dale6_com'); //Thumbs up
    $cai = __('踩', 'dale6_com'); //Thumbs down
    $upico = '<svg class="bi bi-caret-up-fill" width="20" height="20" viewBox="0 0 18 18"><path d="M1 12h16L9 4l-8 8Z"></path></svg>';
    $doico = '<svg class="bi bi-caret-down-fill" width="20" height="20" viewBox="0 0 18 18"><path d="M1 6h16l-8 8-8-8Z"></path></svg>';
    if ($bool) : ?>
        <button type="button" aria-label="<?= $ding ?>" class="btn btn-sm btn-outline-light pinglun_up" dale6_com_data="up" ty="<?php echo $type; ?>" tid="<?php echo $type; ?>-<?php echo $tid; ?>">
            <?= $upico ?>
        </button>
        <span class="text-center px-2" id="<?php echo $type; ?>_top_sum-<?php echo $tid; ?>"><?php echo $tval; ?></span>
        <button type="button" aria-label="<?= $cai ?>" class="btn btn-sm btn-outline-light pinglun_down" dale6_com_data="down" ty="<?php echo $type; ?>" tid="<?php echo $type; ?>-<?php echo $tid; ?>">
            <?= $doico ?>
        </button>
    <?php else : ?>
        <button type="button" aria-label="<?= $ding ?>" class="btn btn-sm btn-outline-light pinglun_up" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <?= $upico ?>
        </button>
        <span class="text-center px-2"><?php echo $tval; ?></span>
        <button type="button" aria-label="<?= $cai ?>" class="btn btn-sm btn-outline-light pinglun_down" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <?= $doico ?>
        </button>
    <?php endif;
}, 10, 4);

// 跳转到主题编辑link
function to_zhuti_bianji_link()
{
    global $admin;
    if ($admin) :
    ?>
        <li class="nav-item text-center">
            <a class="nav-link dl_a" href="<?php echo wp_customize_url('dale6') ?>">
                <?php _e('跳转到主题编辑添加菜单！'); ?>
            </a>
        </li>
    <?php
    endif;
}
// 主要用来处理内容被分页显示不全，这个方法显示所有内容
function dale6_the_content($post_content)
{
    $content = get_extended($post_content);
    $content = $content['main'] . (!empty($content['extended']) ? $content['extended'] : '');
    // $content = content_text_guolv($content['main'] . (!empty($content['extended']) ? $content['extended'] : ''));
    return apply_filters('the_content', $content);
}
// 返回详细距离时间 get_option('gmt_offset')获取时区间隔int
function jiange_time($qianzui, $date, $houzui)
{
    $time = strtotime(wp_date('Y-m-d H:i:s')) - strtotime($date);
    if ($time <= 0) return $qianzui . $time . __('秒', 'dale6_com') . $houzui;
    switch ($time) {
        case $time > 0 && $time < 60:
            return $qianzui . $time . __('秒', 'dale6_com') . $houzui;
        case $time > 59 && $time < 3600:
            return $qianzui . ceil($time / 60) . __('分钟', 'dale6_com') . $houzui;
        case $time > 3600 && $time < 86400:
            return $qianzui . ceil($time / 3600) . __('小时', 'dale6_com') . $houzui;
        case $time > 86400 && $time < 604800:
            return $qianzui . ceil($time / 86400) . __('天', 'dale6_com') . $houzui;
        case $time > 604800 && $time < 2592000:
            return $qianzui . ceil($time / 604800) . __('星期', 'dale6_com') . $houzui;
        case $time > 2592000 && $time < 31536000:
            return $qianzui . ceil($time / 2592000) . __('个月', 'dale6_com') . $houzui;
        case $time > 31536000 && $time < 94608000:
            return $qianzui . ceil($time / 31536000) . __('年', 'dale6_com') . $houzui;
        default: //超过3年返回具体时间
            return date('Y-m-d', strtotime($date));
    }
}

// 没有设置首部菜单的默认菜单输出，由 wp_nav_menu 输出
function ds_nav_menu_fallback()
{
    return '<div>ds_nav_menu_fallback</div>';
    // exit;
    $categories = get_categories(array(
        'orderby' => 'name',
        'parent'  => 0
    ));
    $res = '<ul class="navbar-nav flex-grow-1">';
    foreach ($categories as $category) {
        $res .= '<li class="nav-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
    }
    return $res . '</ul>';
}
// 移除菜单的多余CSS选择器,给菜单a标签添加class
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_link_attributes', 'sonliss_menu_link_atts', 10, 3);
function my_css_attributes_filter($var)
{
    return is_array($var) ? array('nav-item', 'text-center') : '';
}
function sonliss_menu_link_atts($atts, $item, $args)
{
    $atts['class'] = 'nav-link';
    return $atts;
}

// 注册菜单
register_nav_menus(array(
    'head_nav'   => __('页头', 'dale6_com'),
    'left_nav'   => __('首页左', 'dale6_com'),
    'footer_nav' => __('页脚', 'dale6_com'),
));

// 返回可用用户名
function dale6_com_get_display_name($object)
{
    if (!empty($object->comment_author)) return $object->comment_author;
    $userid = 0;
    if (!empty($object->post_author)) $userid = $object->post_author;
    if (!empty($object->user_id)) $userid = $object->user_id;
    if (!empty($object->display_name)) return $object->display_name;
    if (!empty($object->user_nickname)) return $object->user_nickname;
    if (!empty($object->user_login)) return $object->user_login;
    $object = get_userdata($userid);
    if (!empty($object->display_name)) return $object->display_name;
    if (!empty($object->user_nickname)) return $object->user_nickname;
    if (!empty($object->user_login)) return $object->user_login;
    return __('未知用户', 'dale6_com');
}
// 直接将方法写入到文件页面
function dale6_com_add_views()
{
    $post = get_post();
    $post_ID = $post->ID;
    if ($post_ID) {
        $post_views = (int)get_post_meta($post_ID, 'post_views_count', true);
        if (!update_post_meta($post_ID, 'post_views_count', ($post_views + 1))) {
            add_post_meta($post_ID, 'post_views_count', 1, true);
        }
    }
}
// 文章的阅读次数
function dale6_com_the_views($echo = 0)
{
    if ($echo > 0) return $echo;
    $post = get_post();
    $post_ID = $post->ID;
    $views = (int)get_post_meta($post_ID, 'post_views_count', true);
    return $views;
}
function echo_dale6_com_the_views($echo = 0)
{
    echo dale6_com_the_views($echo);
}

// 加载前端js用到的数据
if (!function_exists('dale6_com_js_data')) {
    function dale6_com_js_data()
    {
        // 当前页面url
        // $dangqian_url = home_url(add_query_arg(array(), $wp->request));
        // $dangqian_url = this_url();
        $data = array(
            'ajaxurl' => '/wp-admin/admin-ajax.php',
        );
        echo '<script>var dale6_com_global = ', json_encode($data, JSON_FORCE_OBJECT), '</script>';
    }
}
add_action('wp_head', 'dale6_com_js_data');
add_action('admin_enqueue_scripts', 'dale6_com_js_data');

add_action('wp_head', function () {
    if (is_front_page() || is_home()) {
        $description = get_bloginfo('name');
        $keywords = get_bloginfo('description');
    } else if (is_single()) {
        $postid = get_the_ID();
        if ($postid) {
            // 填写自定义字段description时显示自定义字段的内容，否则使用文章内容前200字作为描述
            $seo_arr = get_post_meta($postid, 'dale6_com_post_seo', true);
            $description1 = isset($seo_arr['seo_description']) ? $seo_arr['seo_description'] : '';
            if (empty($description1)) {
                $description = get_the_title() . ' | ' . get_bloginfo('name') . ' | '  . mb_strimwidth(str_ireplace(array(' ', "\r\n", "\n"), '', strip_tags(get_the_content())), 0, 180);
            } else {
                $description = $description1;
            }
            // 填写自定义字段keywords时显示自定义字段的内容，否则使用文章tags作为关键词
            $keywords1 = isset($seo_arr['seo_keywords']) ? $seo_arr['seo_keywords'] : '';
            if (empty($keywords1)) {
                $tags = wp_get_post_tags($postid);
                foreach ($tags as $tag) {
                    $keywords1 .= $tag->name . ', ';
                }
                $keywords = rtrim($keywords1, ', ');
            } else {
                $keywords = $keywords1;
            }
        }
    } else if (is_category()) {
        $cat = get_query_var('cat');
        $yourcat = get_category($cat);
        $description = category_description() . $yourcat->cat_name . ' | ' . get_bloginfo('name');
        $keywords = single_cat_title('', false) . ', ' . get_bloginfo('description');
    } else if (is_tag()) {
        $description = tag_description() . single_tag_title('', false) . ' | ' . get_bloginfo('name');
        $keywords = single_tag_title('', false) . ' | ' . get_bloginfo('description');
    } else if (is_archive()) {
        $description = get_the_archive_title() . ' | ' . get_bloginfo('name');
        $keywords = get_bloginfo('description');
    } else if (is_page()) {
        $description = (empty(get_the_title()) ? '' : get_the_title() . ' | ') . get_bloginfo('name');
        $keywords = get_bloginfo('description');
    } else {
        $description = get_bloginfo('name');
        $keywords = get_bloginfo('description');
    }
    echo "<title>$description - $keywords</title>";
    echo "<meta name='description' content='$description' />";
    echo "<meta name='keywords' content='$keywords' />";
});
// 获取当前url
function this_url()
{
    $current_url = home_url(add_query_arg(array()));
    if (is_single()) {
        $current_url = preg_replace('/(\/comment|page|#).*$/', '', $current_url);
    } else {
        $current_url = preg_replace('/(comment|page|#).*$/', '', $current_url);
    }
    return $current_url;
}
// 用于翻译
function theme_init()
{
    load_theme_textdomain('dale6_com', get_template_directory() . '/languages');
}

add_action('login_head', function () {
    echo '<style type="text/css">#login h1 a {background-image:unset;display:contents;}#login form{background:unset;border:unset;box-shadow:unset;}</style>';
});

add_filter('login_headertext', function () {
    return get_bloginfo('name');
});

add_filter('login_headerurl', function () {
    return get_bloginfo('url');
});

// 文章编辑添加box
add_action('add_meta_boxes', function () {
    add_meta_box('dale6_com_post_seo', __('大乐文章SEO设置', 'textdomain'), function ($post) {
        wp_nonce_field('wenzhang_meta_input_a', 'custom_nonce');
        $seo_arr = get_post_meta($post->ID, 'dale6_com_post_seo', true);
    ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="biaotibiaoqian"><?php _e('文章标题前缀', 'dale6_com') ?></label></th>
                    <td>
                        <input name="biaotibiaoqian" id="biaotibiaoqian" type="text" value="<?= isset($seo_arr['biaotibiaoqian']) ? $seo_arr['biaotibiaoqian'] : '' ?>" class="regular-text">
                        <p><?php _e('为空则不输出,一般用于原创转载识别,HTML格式,CSS框架[Bootstrap v5.3.0],点击例子:', 'dale6_com') ?>
                            <span class="badge bg-secondary add_biaotibiaoqian">原创</span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th><label for="seo_description"><?php _e('SEO简介说明', 'dale6_com') ?></label></th>
                    <td>
                        <input name="seo_description" id="seo_description" type="text" value="<?= isset($seo_arr['seo_description']) ? $seo_arr['seo_description'] : '' ?>" class="regular-text">
                        <p><?php _e('为空则输出文章前约180个字', 'dale6_com') ?></p>
                    </td>
                </tr>
                <tr>
                    <th><label for="seo_keywords"><?php _e('SEO关键字', 'dale6_com') ?></label></th>
                    <td>
                        <input name="seo_keywords" id="seo_keywords" type="text" value="<?= isset($seo_arr['seo_keywords']) ? $seo_arr['seo_keywords'] : '' ?>" class="regular-text">
                        <p><?php _e('为空则输出文章标签', 'dale6_com') ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', ".add_biaotibiaoqian", function() {
                    var str = $(this)[0].outerHTML;
                    $('#biaotibiaoqian').val(str.replace(' add_biaotibiaoqian', ''));
                });
            });
        </script>
        <?php
    }, 'post');
});
add_action('save_post', function ($post_ID) {
    $nonce_name   = isset($_POST['custom_nonce']) ? $_POST['custom_nonce'] : '';
    $nonce_action = 'wenzhang_meta_input_a';
    // 检查随机数是否有效。
    if (!wp_verify_nonce($nonce_name, $nonce_action)) {
        return;
    }
    // 是否有保存数据权限
    if (!current_user_can('edit_post', $post_ID)) {
        return;
    }
    // 检查是否不是自动保存。
    if (wp_is_post_autosave($post_ID)) {
        return;
    }
    // 检查是否不是修订版。
    if (wp_is_post_revision($post_ID)) {
        return;
    }
    // 保存数据
    update_post_meta($post_ID, 'dale6_com_post_seo', array(
        'biaotibiaoqian'  => trim($_POST['biaotibiaoqian']),
        'seo_description' => trim($_POST['seo_description']),
        'seo_keywords'    => trim($_POST['seo_keywords']),
    ));
});


if ($is_admin) {
    // 主题设置菜单
    add_action('admin_menu', function () {
        add_menu_page(__('大乐主题|极致简洁', 'dale6'), __('大乐主题', 'dale6'), 'manage_options', 'dale6_com_Theme_shezhiyemian', function () {
        ?>
            <div class="wrap">
                <h1><?php _e('大乐主题相关设置', 'dale6_com') ?></h1>
                <form method="post" action="options.php">
                    <?php settings_fields('dale6_com'); ?>
                    <?php do_settings_sections('dale6_com'); ?>
                    <?php submit_button(__('保存', 'dale6_com')); ?>
                </form>
            </div>
    <?php
        }, 'dashicons-admin-generic', 90);
    });

    add_action('admin_init', function () {
        register_setting('dale6_com', 'dale6_com_setting');

        add_settings_section(
            'dale6_com_setting_section',
            __('大乐主题相关设置', 'dale6_com'),
            'stp_api_settings_section_callback',
            'dale6_com'
        );
        add_settings_field(
            'dale6_com_pingluntxleixing',
            __('评论头像显示方法', 'dale6_com'),
            'dale6_com_echo_pingluntxleixing',
            'dale6_com',
            'dale6_com_setting_section'
        );

        add_settings_field(
            'dale6_com_pinglunbeijingyanse',
            __('评论头像背景颜色', 'dale6_com'),
            'dale6_com_echo_pinglunbeijingyanse',
            'dale6_com',
            'dale6_com_setting_section'
        );

        add_settings_field(
            'dale6_com_wenzhangzhuijia',
            __('文章说明HTML', 'dale6_com'),
            'dale6_com_echo_wenzhangzhuijia',
            'dale6_com',
            'dale6_com_setting_section'
        );

        add_settings_field(
            'dale6_com_yejiaozhuijia',
            __('页脚追加HTML', 'dale6_com'),
            'dale6_com_echo_yejiaozhuijia',
            'dale6_com',
            'dale6_com_setting_section'
        );

        add_settings_field(
            'dale6_com_email_username',
            __('邮箱SMTP设置', 'dale6_com'),
            'dale6_com_echo_email_username',
            'dale6_com',
            'dale6_com_setting_section'
        );
    });
}
// 邮件SMTP功能 添加设置页面
add_action('phpmailer_init', function ($mail) {
    global $options;
    // $options = get_option('dale6_com_setting');
    $mail->From = $mail->Username = $options['email_username'];
    $mail->Password = $options['email_password'];
    $mail->Host = $options['email_host'];
    $mail->SMTPSecure = $options['email_Secure'] ? $options['email_Secure'] : '';
    $mail->Port = (int)$options['email_port'];
    $mail->SMTPAuth = true;
    $mail->FromName = $options['email_nicheng'];
    $mail->IsSMTP();
});

function dale6_com_echo_email_username()
{
    global $options;
    ?>
    <p><?php _e('认证用户名(一般情况下填写你的邮箱账号,例如:example@gmail.com!)', 'dale6_com'); ?></p>
    <input type='text' name='dale6_com_setting[email_username]' value='<?php echo isset($options['email_username']) ? $options['email_username'] : ''; ?>'>
    <br />
    <p><?php _e('认证密码(一般情况下不要填写你的邮箱登录密码,而是在你的邮箱服务商那里开通SMTP功能的时候给的专用密码!)', 'dale6_com'); ?></p>
    <input type='text' name='dale6_com_setting[email_password]' value='<?php echo isset($options['email_password']) ? $options['email_password'] : ''; ?>'>
    <br />
    <p><?php _e('SMTP地址:(这个地址请到你的邮箱服务商那里获取!例如:smtp.163.com)', 'dale6_com'); ?></p>
    <input type='text' name='dale6_com_setting[email_host]' value='<?php echo isset($options['email_host']) ? $options['email_host'] : ''; ?>'>
    <br />
    <p><?php _e('SMTP加密:(根据邮箱服务商的SMTP功能来决定!例如:SSL/TTLS)', 'dale6_com'); ?></p>
    <input type='text' name='dale6_com_setting[email_Secure]' value='<?php echo isset($options['email_Secure']) ? $options['email_Secure'] : ''; ?>'>
    <br />
    <p><?php _e('SMTP端口:(例如:25/465/587)', 'dale6_com'); ?></p>
    <input type='number' name='dale6_com_setting[email_port]' value='<?php echo isset($options['email_port']) ? $options['email_port'] : ''; ?>'>
    <br />
    <p><?php _e('发件人昵称:(通过SMTP发送的邮件都会写上的发件人名称!例如:大乐文章)', 'dale6_com'); ?></p>
    <input type='text' name='dale6_com_setting[email_nicheng]' value='<?php echo isset($options['email_nicheng']) ? $options['email_nicheng'] : ''; ?>'>
<?php
}
function dale6_com_echo_pingluntxleixing()
{
    global $options;
?>
    <input id="pingluntxleixing1" name="dale6_com_setting[pingluntxleixing]" type="radio" value="1" <?php echo isset($options['pingluntxleixing']) && $options['pingluntxleixing'] == '1' ? 'checked' : '' ?>>
    <label for="pingluntxleixing1"><?php _e('不显示头像,只显示背景颜色', 'dale6_com'); ?></label>
    <input id="pingluntxleixing2" name="dale6_com_setting[pingluntxleixing]" type="radio" value="2" <?php echo isset($options['pingluntxleixing']) && $options['pingluntxleixing'] == '2' || empty($options['pingluntxleixing']) ? 'checked' : '' ?>>
    <label for="pingluntxleixing2"><?php _e('显示头像,错误后再显示背景颜色', 'dale6_com'); ?></label>
<?php
}
function dale6_com_echo_pinglun_txbj_color()
{
    global $options;
    if (!isset($options['pinglunbeijingyanse']) || empty($options['pinglunbeijingyanse'])) $options['pinglunbeijingyanse'] = DALE6_COM_PINGLUN_TX_COLOR;
    echo '["' . implode('","', $options['pinglunbeijingyanse']) . '"]';
}
function dale6_com_echo_pinglunbeijingyanse()
{
    global $options;
?>
    <p><?php _e('评论头像背景颜色循环显示,至少设置一个颜色,用于无法正确显示头像的时候显示.', 'dale6_com') ?></p>
    <div id="pinglunbeijingyanse">
        <?php if (!isset($options['pinglunbeijingyanse']) || empty($options['pinglunbeijingyanse'])) $options['pinglunbeijingyanse'] = DALE6_COM_PINGLUN_TX_COLOR ?>
        <?php if (isset($options['pinglunbeijingyanse']) && is_array($options['pinglunbeijingyanse']) && count($options['pinglunbeijingyanse']) > 0) : ?>
            <?php foreach ($options['pinglunbeijingyanse'] as $v) : ?>
                <input name="dale6_com_setting[pinglunbeijingyanse][]" type="color" value="<?php echo $v ?>">
            <?php endforeach; ?>
        <?php else : ?>
            <input name="dale6_com_setting[pinglunbeijingyanse][]" type="color">
        <?php endif; ?>
    </div>
    <button type="button" id="" onclick="dale6_addcolorbtn()">+</button>
    <button type="button" id="delcolorbtn" onclick="dale6_delcolorbtn()">-</button>
    <script>
        var dale6_com_pinglun_tx_color = <?php echo '["' . implode('","', DALE6_COM_PINGLUN_TX_COLOR) . '"]' ?>;
        var dale6_com_del_btn_color = false;
        var dale6_com_colorIndex = 0;
        dale6_addLoadEvent(dale6_input_mouse());

        function dale6_input_mouse() {
            var btn = document.getElementById('delcolorbtn');
            document.querySelectorAll('#pinglunbeijingyanse input').forEach(function(e) {
                e.addEventListener('mouseover', function(el) {
                    dale6_com_del_btn_color = e;
                    btn.style.backgroundColor = e.value;
                });
            });
        }

        function dale6_addcolorbtn(color) {
            var c = document.createElement('input');
            c.name = 'dale6_com_setting[pinglunbeijingyanse][]';
            c.type = 'color';
            c.value = color ? color : dale6_com_pinglun_tx_color[dale6_com_colorIndex++ % dale6_com_pinglun_tx_color.length];
            document.getElementById('pinglunbeijingyanse').append(c);
            dale6_input_mouse();
        }

        function dale6_delcolorbtn() {
            if (dale6_com_del_btn_color != false) {
                document.getElementById('delcolorbtn').style.backgroundColor = 'buttonface';
                dale6_com_del_btn_color.remove();
                dale6_com_del_btn_color = false;
            }
            let c = document.getElementsByName('dale6_com_setting[pinglunbeijingyanse][]');
            if (!dale6_com_del_btn_color && c.length > 0) {
                c[c.length - 1].remove();
            }
            if (c.length == 0) {
                for (a in dale6_com_pinglun_tx_color) {
                    dale6_addcolorbtn(dale6_com_pinglun_tx_color[a]);
                }
            }
        }

        function dale6_addLoadEvent(func) {
            var oldonload = window.onload;
            if (typeof window.onload != 'function') {
                window.onload = func;
            } else {
                window.onload = function() {
                    oldonload();
                    func();
                }
            }
        }
    </script>
<?php
}
function dale6_com_echo_wenzhangzhuijia()
{
    global $options;
?>
    <p><?php _e('在文章结尾添加HTML', 'dale6_com') ?></p>
    <textarea name="dale6_com_setting[wenzhangzhuijia]" class="regular-text" rows="6"><?php echo isset($options['wenzhangzhuijia']) ? $options['wenzhangzhuijia'] : ''; ?></textarea>
<?php
}
function dale6_com_echo_yejiaozhuijia()
{
    global $options;
?>
    <p><?php _e('在页脚最后添加HTML', 'dale6_com') ?></p>
    <textarea name="dale6_com_setting[yejiaozhuijia]" class="regular-text" rows="6"><?php echo isset($options['yejiaozhuijia']) ? $options['yejiaozhuijia'] : ''; ?></textarea>
<?php
}

function stp_api_settings_section_callback()
{
?>
    <p><a href="https://www.dashengx.com" target="_blank"><?php _e('请使用插件[大圣盒子]来提升性能!', 'dale6_com'); ?></a></p>

<?php
}

add_filter('dale6_com_the_title', function ($title) {
    $title = trim($title);
    if (empty($title)) return __('未知标题', 'dale6_com');
    return $title;
});

// 修改 pingback 自动评论内容格式
add_filter('dale6_pingback_or_trackback_comment_content', function ($comment) {
    $comment_content = '<a href="' . $comment->comment_author_url . '" target="_blank" rel="noopener noreferrer nofollow">' . __('很荣幸转载了你的文章!感谢!', 'dale6_com') . '</a>';
    return $comment_content;
});
// 修改 pingback 自动评论显示名称
add_filter('dale6_pingback_or_trackback_display_name', function ($comment) {
    $display_name = parse_url($comment->comment_author_url, PHP_URL_HOST);
    return $display_name;
});

// 最后输出前的安全处理
add_action('init', function () {
    header('Server:');
    header('X-Content-Type-Options: nosniff');
    header_remove('server');
    header_remove('Expires');
    header_remove('Host');
    header_remove('P3P');
    header_remove('Pragma');
    header_remove('Public-Key-Pins');
    header_remove('Public-Key-Pins-Report-Only');
    header_remove('Via');
    header_remove('X-AspNet-Version');
    header_remove('X-AspNetMvc-version');
    header_remove('X-Frame-Options');
    header_remove('X-Powered-By');
    header_remove('X-Runtime');
    header_remove('X-Version');
});
// 登录与注册,没有账号则注册后登录,有账号直接登录
// if (!is_user_logged_in()) {
function dale6_com_ajax_login()
{
    if (!isset($_SESSION['send_mailcode']) || !isset($_POST['send_mailcode']) || !isset($_POST['emailcode']) || !isset($_POST['email']) || !is_email(sanitize_email($_POST['email'])) || $_SESSION['send_mailcode'] != $_POST['send_mailcode'] || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url(home_url(), PHP_URL_HOST) || !isset($_SESSION['mailcode']) || !isset($_SESSION['mailcode_guoqitime'])) {
        status_header(404);
        exit();
    }
    if ($_SESSION['mailcode'] != $_POST['emailcode']) {
        wp_send_json_success(array('c' => '0', 'ms' => __('验证码错误!', 'dale6_com')), 200);
    }
    if ($_SESSION['mailcode_guoqitime'] < time()) {
        wp_send_json_success(array('c' => '0', 'ms' => __('验证码过期了!请重新发送!', 'dale6_com')), 200);
    }
    if (is_user_logged_in()) {
        wp_send_json_success(array('c' => '0', 'ms' => __('已经登录!', 'dale6_com')), 200);
    }
    $user_email = sanitize_email($_POST['email']);
    // 没有邮箱则注册
    if (!email_exists($user_email)) {
        $emailfenge = explode('@', $user_email, 2);
        $username = trim($emailfenge[0] . md5($emailfenge[1]));
        $user_login = sanitize_text_field(sanitize_user($username));
        // 注册
        $user_pass = wp_generate_password(12, false);
        $user_ID = wp_create_user($user_login, $user_pass, $user_email);
    } else {
        //获取POST数据并登录用户
        $user = get_user_by('email', $user_email);
        $user_ID = $user->ID;
    }
    // wp_clear_auth_cookie();
    wp_set_current_user($user_ID);
    wp_set_auth_cookie($user_ID, 1, is_ssl());
    wp_send_json_success(array('c' => 300, 'ms' => __('登录成功!', 'dale6_com'), 'd' => $_SERVER['HTTP_REFERER']), 200);
    wp_die();
}
add_action('wp_ajax_nopriv_ajaxdenglu', 'dale6_com_ajax_login');

// 发送邮件验证码
function dale6_com_sendmailcode()
{
    // 验证 send_mailcode 
    if (!isset($_SESSION['send_mailcode']) || !isset($_POST['send_mailcode']) || !isset($_POST['email']) || $_SESSION['send_mailcode'] != $_POST['send_mailcode'] || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != parse_url(home_url(), PHP_URL_HOST)) {
        status_header(404);
        exit();
    }
    // 每次间隔
    if (isset($_SESSION['send_mailcode_time'])) {
        if (time() - $_SESSION['send_mailcode_time'] < 60) {
            wp_send_json_error(array('c' => 500, 'ms' => __('发送验证码太频繁！请等待60秒以后再次发送！', 'dale6_com')), 200);
            exit();
        }
    } else {
        $_SESSION['send_mailcode_time'] = time();
    }
    // 生成并保存邮件验证码
    $_SESSION['mailcode'] = rand(100000, 999999);
    $to = $_POST['email'];
    $subject = '大乐文章邮箱验证码';
    $body = "这是由 大乐文章<www.dale6.com> 系统发送的一封验证邮件，请不要回复！\r\n\r\n如果能收到这封邮件，说明您的邮箱能够正常使用！\r\n\r\n如果您不知道这封邮件的来龙去脉，请忽略此邮件并且不要告知任何人此邮件的内容！\r\n\r\n验证码：  " . $_SESSION['mailcode'] . "\r\n\r\n验证码在10分钟内有效!\r\n\r\n如果您有任何建议请联系我们：admin@dale6.com";
    if (wp_mail($to, $subject, $body)) {
        // 两次发送验证码的间隔
        $_SESSION['mailcode_time'] = time() + 60;
        // 验证码过期的时间
        $_SESSION['mailcode_guoqitime'] = time() + 600;
        wp_send_json_success(array('c' => 0, 'ms' =>  __('验证码发送成功!请登录邮箱查收!', 'dale6_com')), 200);
    } else {
        wp_send_json_error(array('c' => 500, 'ms' => __('服务器繁忙!请稍后再试!多次发送问题依然存在请联系:', 'dale6_com') . get_option('admin_email')), 200);
    }
}
add_action('wp_ajax_nopriv_sendmailcode', 'dale6_com_sendmailcode');


// 角色注销后跳转地址,这里改成你要跳转的网址
function logout_redirect($logouturl, $redir)
{
    $outurl = this_url();
    return $logouturl . '&redirect_to=' . urlencode($outurl);
}
add_filter('logout_url', 'logout_redirect', 10, 3);

// 评论top
function dale6_com_ajax_pingluntop()
{
    if (!is_user_logged_in() || !isset($_POST['ty']) || !isset($_POST['type']) || !isset($_POST['cid']) || !isset($_POST['key']) || !wp_verify_nonce($_POST['key'], 'ajaxpingluntop')) {
        header("HTTP/1.1 404 Not Found");
        wp_die();
    }
    $user = wp_get_current_user();
    if ((isset($user->ID) ? (int) $user->ID : 0) == 0) {
        header("HTTP/1.1 404 Not Found");
        wp_die();
    }
    $lx = $_POST['ty'] == 'post' ? 'post' : 'pinglun';
    $tmp = explode($lx . '-', $_POST['cid'], 2);
    $comment_id = (int)$tmp[1];
    if ($lx == 'post') {
        $comment_arr = get_post_meta($comment_id, 'dale6_com_post_top', true);
    } else {
        $comment_arr = get_comment_meta($comment_id, 'dale6_com_comment_top', true);
    }
    if (!is_array($comment_arr)) {
        $comment_arr = array();
    }
    $_POST['type'] = $_POST['type'] == 'up' ? 1 : -1;
    $ms = __('投票成功', 'dale6_com');
    if ($comment_arr[$comment_id] == $_POST['type']) {
        unset($comment_arr[$comment_id]);
        $ms = __('取消成功', 'dale6_com');
    } else {
        $comment_arr[$comment_id] = $_POST['type'];
    }
    if (empty($comment_arr)) {
        $comment_arr = array();
    }
    if ($lx == 'post') {
        update_post_meta($comment_id, 'dale6_com_post_top', $comment_arr);
    } else {
        update_comment_meta($comment_id, 'dale6_com_comment_top', $comment_arr);
    }
    $comment_top_sum = array_sum($comment_arr);
    wp_send_json_success(array('c' => 200, 'ms' => $ms, 'data' => array('sum' => $comment_top_sum, 'cid' => $comment_id)), 200);
    wp_die();
}
add_action('wp_ajax_ajaxpingluntop', 'dale6_com_ajax_pingluntop');
