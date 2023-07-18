<?php

/**
 * 热门标签列表小工具
 */
class remenbiaoqian extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'dalewenzhang_remenbiaoqian',
            __('[大乐文章]主题的小工具[热门标签列表]', 'dalewenzhang'),
            array(
                'description' => __('显示热门标签10条.', 'dalewenzhang'),
            ),
        );
    }
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;
        if (!empty($title)) {
            $titlee = $before_title . $title . $after_title;
        } else {
            $titlee = __('热门标签', 'dalewenzhang');
        }
?><div class="card border-0 bg-white mb-3">
            <div class="card-body fw-light">
                <?php $tags = get_terms(array(
                    'taxonomy' => 'post_tag',
                    'orderby'  => 'count',
                    'order'    => 'DESC',
                    'number'   => 10,
                ));
                $a = 0;
                if (!empty($tags) && !is_wp_error($tags)) : ?>
                    <p class="card-title"><?php echo $titlee; ?></p>
                    <?php foreach ($tags as $tag) : $a++ ?>
                        <a class="btn btn-outline-secondary w-100" target="_blank" href="<?php echo get_permalink($tag); ?>">
                            <div class="d-flex">
                                <div class="flex-grow-1 text-start text-truncate"><?php echo $tag->name; ?></div>
                                <div class=""><?php echo $tag->count; ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="card-text"><?php _e('没有标签,请发布评论!', 'dalewenzhang'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php
        echo $after_widget;
    }
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('热门标签', 'dalewenzhang');
        }
    ?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance          = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}
