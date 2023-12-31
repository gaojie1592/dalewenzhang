<?php get_header(); ?>

<body <?php body_class(); ?>>
    <?php get_template_part('toolbar'); ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-lg-12">
                <?php while (have_posts()) : the_post(); ?>
                    <div class="card border-0 bg-white mb-3">
                        <div class="card-body">
                            <h1 class="card-title text-center"><?php the_title(); ?></h1>
                            <hr>
                            <div class="card-text"><?php the_content(); ?></div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php comments_template(); ?>
            </div>
        </div>
        <?php if (is_active_sidebar('xiabianlan')) : ?>
            <div class="row">
                <div class="col-12">
                    <?php dynamic_sidebar('xiabianlan'); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php get_footer() ?>

</body>

</html>