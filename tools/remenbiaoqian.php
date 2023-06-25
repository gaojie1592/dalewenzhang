<div class="card border-0 bg-white mb-3">
    <div class="card-body fw-light">
        <?php $lastposts = get_tags(array(
            'number' => 50,
            'orderby' => 'count',
            'order' => 'DESC',
            'hide_empty' => false
        ));

        if ($lastposts) : ?>
            <p class="card-title"><?php _e('热门标签', 'dalewenzhang'); ?></p>
            <?php foreach ($lastposts as $tag) : ?>
                <div class="d-flex">
                    <p class="flex-grow-1 text-truncate card-text mb-0">
                        <a class="dl_a" href='<?php echo get_tag_link($tag->term_id) ?>' title='<?php echo $tag->name; ?>'><?php echo $tag->name; ?></a>
                    </p>
                    <span class="text-muted align-self-center">
                        <?php echo $tag->count; ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="card-text"><?php _e('没有标签,请创建标签', 'dalewenzhang'); ?></p>
        <?php endif; ?>
    </div>
</div>