<div id="comments">
	<div class="card border-0 bg-white mb-3">
		<div class="card-body">
			<h5 class="card-title"><?php echo get_comments_number() . __('条评论', 'dale6_com'); ?></h5>
			<ul class="list-group list-group-flush">
				<?php wp_list_comments(array(
					'callback' => 'dale6_com_echo_comment',
				)); ?>
			</ul>
			<?php $pag_arr = paginate_comments_links(array(
				'next_text'  => __('下一页', 'dale6_com'),
				'prev_text'  => __('上一页', 'dale6_com'),
				'type'       => 'array',
			)); ?>
			<?php if (is_array($pag_arr) && count($pag_arr) > 0) : ?>
				<nav id="page_ym" class="pt-3">
					<ul class="pagination">
						<?php foreach ($pag_arr as $v) {
							echo '<li class="page-item">', str_ireplace("page-numbers", "page-link text-dark", $v), '</li>';
						} ?>
					</ul>
				</nav>
			<?php endif ?>
		</div>
	</div>

	<?php if (!comments_open()) : ?>
		<div class="card border-0 bg-white mb-3">
			<div class="card-body">
				<p class="card-title"><?php _e('评论已关闭.', 'dale6_com'); ?></p>
			</div>
		</div>
	<?php else : ?>
		<?php
		global $user_identity;
		// 评论
		comment_form(array(
			'comment_field' => '<textarea aria-label="' . __('书写评论', 'dale6_com') . '" id="comment" name="comment" class="form-control" style="height: 100px" required="required"></textarea>',
			'must_log_in' => '<div class="card-body"><p class="card-title">' .  sprintf(__('必须<a %s> 登录 </a>才能发表评论', 'dale6_com'), ' href="" data-bs-toggle="modal" data-bs-target="#exampleModal"') . '</p></div>',
			'logged_in_as'  => '<p>' . sprintf(__('已登录为<a class="text-danger mx-2" href="%1$s">%2$s</a><a class="text-muted" href="%3$s" title="注销这个账号">注销</a>', 'dale6_com'), admin_url('profile.php'), $user_identity, wp_logout_url(this_url())) . '</p>',
			'comment_notes_before' => '',
			'comment_notes_after' => '<div class="mb-3 d-flex">',
			'class_container' => 'card border-0 bg-white mb-3',
			'class_form' => 'card-body',
			'title_reply' => __('留下你的评论', 'dale6_com'),
			'title_reply_to' => __('回复%s的评论', 'dale6_com'),
			'title_reply_before' => '<div class="card-body pb-0"><p class="card-text">',
			'title_reply_after'  => '</p></div>',
			'cancel_reply_before' => '<span class="float-end">',
			'cancel_reply_after' => '</span>',
			'cancel_reply_link' => __('取消回复', 'dale6_com'),
			'label_submit' => __('发表评论', 'dale6_com'),
			'submit_button' => '<button type="button" class="btn btn-outline-dark" id="charudaima">' . __('插入代码', 'dale6_com') . '</button><button type="submit" class="%3$s ms-auto btn btn-outline-dark">%4$s</button></div><span class="text-muted">' . __('字符个数必须大于20小于5000个!', 'dale6_com') . '</span>',
			'submit_field' => '%1$s %2$s',
			'fields' => array(
				'author'  => '<input type="text" id="author" name="author" class="form-control" placeholder="昵称" required="required" >',
				'email'   => '<input type="text" id="email" name="email" class="form-control" placeholder="邮件地址" required="required" ></div><div class="mb-3 d-flex">',
				'cookies' => '',
			),
		));
		?>
		<script>
			dale6_addLoadEvent(function() {
				document.getElementById('charudaima').addEventListener('click', function(e) {
					document.getElementById('comment').value = document.getElementById('comment').value + '<code>//<?php _e('代码', 'dale6_com'); ?></code>';
				});
			});
		</script>
	<?php endif; ?>
</div>
<script>
	dale6_addLoadEvent(function() {
		document.querySelectorAll('.comment-text').forEach(function(e) {
			let height = e.scrollHeight;
			let btn = e.querySelector('.comment-btn');
			if (height > 125) {
				let show = true;
				btn.style.display = 'block';
				e.style.height = '125px';
				btn.addEventListener("click", function(event) {
					if (show) {
						show = false;
						btn.textContent = '<?php _e('收缩', 'dale6_com') ?>';
						e.style.height = (height + 30) + 'px';
					} else {
						show = true;
						btn.textContent = '<?php _e('展开', 'dale6_com') ?>';
						e.style.height = '125px';
					}
				});
			}
		});
	});
</script>