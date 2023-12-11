<?php

/**
 * 最新文章列表小工具
 */
class zuixinwenzhang extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'dalewenzhang_zuixinwenzhang',
            __('[大乐文章]主题的小工具[最新文章列表]', 'dalewenzhang'),
            array(
                'description' => __('显示最新文章10条.', 'dalewenzhang'),
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
        $title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');
        echo $before_widget;
        if (!empty($title)) {
            $titlee = $before_title . $title . $after_title;
        } else {
            $titlee = __('最新文章', 'dalewenzhang');
        }
?><div class="card border-0 bg-white mb-3">
            <div class="card-body fw-light">
                <?php $lastposts = get_posts(array(
                    'post_password'       => '',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => 0,
                    'orderby'             => 'post_date',
                    'posts_per_page'      => 10,
                    'order'               => 'DESC',
                ));
                $a = 1;
                if ($lastposts) : ?>
                    <p class="card-title"><?php echo $titlee; ?></p>
                    <?php foreach ($lastposts as $posta) : ?>
                        <div class="d-flex py-1">
                            <span class="align-self-center me-2">
                                <?php echo $a++; ?>.
                            </span>
                            <p class="flex-grow-1 text-truncate card-text mb-0">
                                <?php
                                $title = trim($posta->post_title);
                                if (empty($title)) $title = __('未知标题', 'dalewenzhang');
                                ?>
                                <a class="dl_a text-decoration-none" target="_blank" href="<?php echo get_permalink($posta->ID); ?>"><?php echo $title; ?></a>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="card-text"><?php _e('没有文章,请发布文章!', 'dalewenzhang'); ?></p>
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
            $title = __('最新文章', 'dalewenzhang');
        }
    ?>
        <p>
            <label for="<?php echo $this->get_field_name('title'); ?>"><?php _e('标题:', 'dalewenzhang'); ?></label>
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
