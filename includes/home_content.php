<div class="container">
    <div class="row">
        <?php
        $cold = has_nav_menu('left_nav') ? 2 : 0;
        $cole = is_active_sidebar('youbianlan') ? (empty($cold) ? 4 : 3) : 0;
        $colf = !empty($cold) && !empty($cole) ? 7 : 8;
        if (!empty($cole)) {
            $class1 = 'col-md-' . (12 - $cold - $cole) . ' col-lg-' . (12 - $cold - $cole);
            $class2 = 'col-md-' . (12 - $cold - $colf) . ' col-lg-' . (12 - $cold - $colf);
        } else {
            $class1 = 'col-md-' . (12 - $cold) . ' col-lg-' . (12 - $cold);
            $class2 = '';
        }
        ?>
        <?php if (!empty($cold)) : ?>
            <nav class="col-md-2 col-lg-2 py-3">
                <?php get_template_part('includes/fenleilist'); ?>
            </nav>
        <?php endif; ?>
        <main class="<?php echo $class1; ?> border-start py-3">
            <?php get_template_part('tools/home_wenzhangliebiao'); ?>
            <?php get_template_part('tools/page_yema'); ?>
        </main>
        <?php if (!empty($cole)) : ?>
            <nav class="<?php echo $class2; ?> py-3">
                <?php dynamic_sidebar('youbianlan'); ?>
            </nav>
        <?php endif; ?>
    </div>
    <?php if (is_active_sidebar('xiabianlan')) : ?>
        <div class="row">
            <div class="col-12">
                <?php dynamic_sidebar('xiabianlan'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>