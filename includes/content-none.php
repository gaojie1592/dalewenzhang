<section class="no-results not-found position-relative" style="min-height: 500px;">
	<div class="page-content default-max-width text-center position-absolute top-50 start-50 translate-middle">
		<?php if (is_search()) : ?>
			<h1 class="page-title text-center">
				<?php
				printf(
					__('搜索关键字词"%s"没有结果！请重新搜索!', 'dale6_com'),
					'<span class="page-description search-term text-danger">' . esc_html(get_search_query()) . '</span>'
				);
				?>
			</h1>
		<?php elseif (is_home() && current_user_can('publish_posts')) : ?>
			<?php
			printf(
				'<p>' . wp_kses(
					__('准备好发表你的第一篇文章了吗? <a href="%s">点这里发文章</a>.', 'dale6_com'),
					array('a' => array('href' => array()))
				) . '</p>',
				esc_url(admin_url('post-new.php'))
			);
			?>
		<?php else : ?>
			<?php _e('<h1>没有找到数据！</h1>', 'dale6_com'); ?>
		<?php endif; ?>
	</div>
</section>