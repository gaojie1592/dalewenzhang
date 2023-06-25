<?php
global $options;
// 是否已登录
$user = wp_get_current_user();
// 是否已登录
$is_login = $user->exists();
// 如果有页面参数则不记录页面查看次数
if (!isset($_GET['page'])) dale6_com_add_views();

while (have_posts()) : the_post(); ?>
    <div id="post-<?php the_ID() ?>" <?php post_class('card border-0 bg-white'); ?>>
        <div class="card-body">
            <?php
            $seo_arr = get_post_meta($post->ID, 'dale6_com_post_seo', true);
            $biaotibiaoqian = isset($seo_arr['biaotibiaoqian']) ? $seo_arr['biaotibiaoqian'] : '';
            ?>
            <h1 class="card-title"><?php echo $biaotibiaoqian; ?><?php echo apply_filters('dale6_com_the_title', get_the_title()); ?></h1>
            <div class="card-text">
                <span class="dale6_svg_eye wh-16px px-2"></span>
                <span class="text-muted fw-light mx-2">
                    <?php echo dale6_com_the_views(); ?>
                </span>
                <span class="dale6_svg_up wh-16px px-2"></span>
                <span class="text-muted fw-light mx-2">
                    <?php $post_top_sum = apply_filters(DALE6_COM_POST_TOP, $post->ID); ?>
                    <?php echo $post_top_sum; ?>
                </span>
                <span class="dale6_svg_pinglun wh-16px px-2"></span>
                <span class="text-muted fw-light mx-2">
                    <?php echo $post->comment_count; ?>
                </span>
                <?php if ($user->ID == $post->post_author) : ?>
                    <span class="text-muted pe-2"><a rel="nofollow" href="<?php echo admin_url('post.php?action=edit&post=') . $post->ID ?>"><?php echo __('编辑', 'dale6_com'); ?></a></span>
                <?php endif; ?>
            </div>
            <div class="card-text d-flex align-items-center py-3 mb-3 border-bottom">
                <div class="flex-shrink-0 dale6_com_user_ico">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($post->post_author, array('size' => 40)) ?>" width="40" height="40" alt="<?php echo dale6_com_get_display_name($post) ?>">
                </div>
                <div class="flex-grow-1 d-flex flex-column ms-3">
                    <div class="d-flex">
                        <div class="d-inline-block text-truncate w-150px">
                            <a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo mb_substr(dale6_com_get_display_name($post), 0, 50, 'utf8'); ?></a>
                        </div>
                    </div>
                    <div class="text-muted lh-1">
                        <?php echo jiange_time(__('发表于', 'dale6_com'), $post->post_date, __('前', 'dale6_com')) ?>
                    </div>
                </div>
            </div>
            <?php if (has_post_thumbnail()) the_post_thumbnail(); ?>
            <?php the_content(); ?>

            <?php
            $next_a = wp_link_pages(array(
                'before'           => '',
                'after'            => '',
                'next_or_number'   => 'next',
                'echo'             => false,
                'nextpagelink'     => __('下一页', 'dale6_com'),
                'previouspagelink' => __('上一页', 'dale6_com'),
            )); ?>
            <?php if (!empty($next_a)) : ?>
                <div class="d-grid gap-2">
                    <?php echo preg_replace("/class=\".*?\"/", 'class="btn btn-sm border w-100 bg-secondary-subtle"', $next_a) ?>
                </div>
            <?php endif; ?>
            <?php $link = esc_url(apply_filters('the_permalink', get_permalink($post), $post)); ?>
            <div class="alert alert-secondary mt-3">
                <?php if (isset($options['wenzhangzhuijia']) && !empty($options['wenzhangzhuijia'])) : ?>
                    <?php echo $options['wenzhangzhuijia']; ?>
                <?php endif; ?>
                <p class="text-muted mb-0"><?php _e('本文链接: ', 'dale6_com') ?><a href="<?php echo $link; ?>"><?php echo $link; ?></a></p>
            </div>
            <?php if ($post_tags = get_the_tags()) : ?>
                <div class="d-flex align-items-center pb-3">
                    <span class="dale6_svg_tags wh-16px px-2"></span>
                    <?php foreach ($post_tags as $tag) : ?>
                        <a class="btn btn-outline-secondary btn-sm ms-2" href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo $tag->name ?></a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="d-flex align-items-center justify-content-between">
                <div class="pe-2">
                    <?php do_action('dale6_com_top_or_down_button', $is_login, $post_top_sum, $post->ID, 'post'); ?>
                </div>
                <span class="pe-2 text-muted">
                    <?php echo __('最后修改:', 'dale6_com'), jiange_time('', $post->post_modified, __('前', 'dale6_com')) ?>
                </span>
            </div>

        </div>

    </div>
<?php endwhile; ?>
<?php comments_template(); ?>