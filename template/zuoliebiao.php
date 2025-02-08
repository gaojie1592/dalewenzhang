<?php
/*
Template Name: [dale6_com]左列表模板
*/
?>
<?php get_header(); ?>

<body <?php echo body_class(); ?>>
    <?php get_template_part('toolbar'); ?>

    <?php if (have_posts()) : ?>
        <div class="container mt-3">
            <div class="row">
                <?php dale6_com_add_views(); ?>
                <div class="col-lg-2">
                    <?php // 左边栏样式
                    echo "<style>.btn-toggle{display:inline-flex;align-items:center;padding: .25rem .5rem;font-weight: 600;  color: rgba(0, 0, 0, .65);  background-color:transparent;border:0;}.btn-toggle:hover,.btn-toggle:focus{color:#FFF;background-color:#FF0000;}.btn-toggle::before{width:1.25em;  line-height:0;content:url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e\");transition: transform .35s ease;  transform-origin: .5em 50%;}.btn-toggle[aria-expanded=\"true\"] {color:#FFF;}.btn-toggle[aria-expanded=\"true\"]::before {transform: rotate(90deg);}.btn-toggle-nav a {display:inline-flex;padding:.1875rem .5rem;margin-top:.125rem;margin-left:1.25rem;text-decoration: none;}.btn-toggle-nav a:hover,.btn-toggle-nav a:focus,.btn-toggle-nav .xuanzhong{color:#FFF;background-color:#FF0000;}.scrollarea{overflow-y:hidden;max-height:calc(100vh);}.scrollarea:hover {overflow-y: auto;}.zuoliebiao{position: sticky;top:0px;}.fw-semibold {font-weight:600;}.lh-tight{line-height:1.25;}</style>"; ?>

                    <div class="zuoliebiao ">
                        <ul class="list-unstyled scrollarea">
                            <?php
                            $postid = get_the_ID();
                            // 获取当前文章所属分类ID
                            $category = get_the_category();
                            // var_dump($category);
                            if (count($category) > 0) :
                                $catid = array();
                                foreach ($category as $v) {
                                    array_push($catid, $v->cat_ID);
                                }
                                // var_dump($catid);
                                // $myposts = new WP_Query(array(
                                $myposts = get_posts(array(
                                    'cat' => implode(',', array_values($catid)),
                                    'post_status' => 'publish',
                                    'numberposts' => -1,
                                    'orderby'     => 'title',
                                    'order'       => 'ASC',
                                ));
                                if ($myposts) : ?>
                                    <li class="mb-1">
                                        <div class="">
                                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1">
                                                <?php foreach ($myposts as $v) {
                                                    echo '<li><a href="' . get_permalink($v->ID) . '" class="link-dark rounded ms-0' . ($postid == $v->ID ? ' xuanzhong' : '') . '">' . $v->post_title . '</a></li>';
                                                } ?>
                                            </ul>
                                        </div>
                                    </li>
                                <?php endif ?>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-7">
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h1 class="card-title text-center"><?php the_title(); ?></h1>
                                <p class="card-text text-center">
                                    <span class="text-muted"><?php echo __('阅读:', 'dale6_com') . dale6_com_the_views(); ?></span>
                                </p>
                                <hr>
                                <div class="card-text"><?php the_content(); ?></div>

                                <?php if ($tags = get_the_tags()) : ?>
                                    <hr>
                                    <span class="text-muted pe-2"><?php _e('标签:', 'dale6_com'); ?></span>
                                    <?php foreach ($tags as $tag) :  ?>
                                        <a href="<?php bloginfo('url'); ?>/tag/<?php echo $tag->slug ?>"><?php echo $tag->name ?></a>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </div>
                        </div>
                    <?php endwhile; ?>

                    <?php if (is_single()) : ?>
                        <?php $prev_post = get_adjacent_post(true, '', true); ?>
                        <?php $next_post = get_adjacent_post(true, '', false); ?>
                        <?php if (is_a($prev_post, 'WP_Post') || is_a($next_post, 'WP_Post')) : ?>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="p-2 bd-highlight">
                                            <?php if (is_a($next_post, 'WP_Post')) : ?>
                                                <a href="<?php echo get_permalink($next_post->ID); ?>">&lt;<?php echo get_the_title($next_post->ID); ?></a>
                                            <?php endif ?>
                                        </div>
                                        <div class="p-2 bd-highlight">
                                            <?php if (is_a($prev_post, 'WP_Post')) : ?>
                                                <a href="<?php echo get_permalink($prev_post->ID); ?>"><?php echo get_the_title($prev_post->ID); ?>&gt;</a>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endif ?>

                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>

                </div>
                <div class="col-lg-3">
                    <?php get_template_part('template/tools_you'); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php wp_footer() ?>
    <?php get_footer() ?>

</body>

</html>