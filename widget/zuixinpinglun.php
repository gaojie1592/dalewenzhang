<?php

/**
 * 最新评论列表小工具
 */
class zuixinpinglun extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'dalewenzhang_zuixinpinglun',
            __('[大乐文章]主题的小工具[最新评论列表]', 'dalewenzhang'),
            array(
                'description' => __('显示最新评论10条.', 'dalewenzhang'),
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
            $titlee = __('最新评论', 'dalewenzhang');
        }
?><div class="card border-0 bg-white mb-3">
            <div class="card-body fw-light">
                <?php $comments = get_comments(array(
                    'number'  => 10,
                ));
                if ($comments) : ?>
                    <p class="card-title"><?php echo $titlee; ?></p>
                    <?php foreach ($comments as $comment) : ?>
                        <div class="d-flex align-self-center py-1">
                            <div class="flex-shrink-0 dalewenzhang_user_ico">
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/noavatar.svg" data-src="<?php echo get_avatar_url($comment->user_id, array('size' => 24)) ?>" width="24" height="24" alt="<?php echo dalewenzhang_get_display_name($comment) ?>" role="img">
                            </div>
                            <?php
                            // 删除所有HTML标签
                            $comment_content = trim(strip_tags($comment->comment_content));
                            ?>
                            <a class="text-truncate card-text mb-0 ps-2 dl_a text-decoration-none" target="_blank" href="<?php echo get_permalink($comment->comment_post_ID); ?>">
                                <?php echo $comment_content; ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="card-text"><?php _e('没有评论,请发布评论!', 'dalewenzhang'); ?></p>
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
            $title = __('最新评论', 'dalewenzhang');
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
