<div class="container">
    <div class="row">
        <?php if (has_nav_menu('left_nav')) : ?>
            <nav class="col-md-2 col-lg-2 py-3">
                <?php get_template_part('includes/fenleilist'); ?>
            </nav>
            <main class="col-md-7 col-lg-7 border-start py-3">
                <?php get_template_part('tools/home_wenzhangliebiao'); ?>
                <?php get_template_part('tools/page_yema'); ?>
            </main>
            <nav class="col-md-3 col-lg-3 py-3">
                <?php get_template_part('includes/tools_you'); ?>
            </nav>
        <?php else : ?>
            <main class="col-md-8 col-lg-8 border-start py-3">
                <?php get_template_part('tools/home_wenzhangliebiao'); ?>
                <?php get_template_part('tools/page_yema'); ?>
            </main>
            <nav class="col-md-4 col-lg-4 py-3">
                <?php get_template_part('includes/tools_you'); ?>
            </nav>
        <?php endif; ?>
    </div>

    <?php //get_template_part('tools/youqinglianjie'); 
    ?>
</div>