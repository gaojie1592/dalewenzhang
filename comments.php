<div id="comments">
	<?php
	// 是否已登录
	$user = wp_get_current_user();
	// 是否已登录
	$is_login = $user->exists();
	// 返回所有评论与对应作者以及meta dale6_com_comment_top 是有点赞的评论
	global $wpdb, $table_prefix;
	$sql = "SELECT * FROM `{$table_prefix}comments` left join `{$table_prefix}commentmeta` on (`{$table_prefix}comments`.`comment_ID`=`{$table_prefix}commentmeta`.`comment_id` AND `{$table_prefix}commentmeta`.`meta_key`='dale6_com_comment_top') left join `{$table_prefix}users` on (`{$table_prefix}comments`.`user_id`=`{$table_prefix}users`.`ID`) where `{$table_prefix}comments`.`comment_post_id`={$post->ID} GROUP BY `{$table_prefix}comments`.`comment_ID`";
	$comments = $wpdb->get_results($sql);
	//获取主评论总数量
	$cnt = count($comments);
	if ($cnt > 0) :
		// 评论分级与排列
		$comments_a = $comments_b = $comments_c = array();
		foreach ($comments as $k => $v) {
			// 修改转载pingback和trackback的评论格式
			if ($v->comment_type == 'pingback' || $v->comment_type == 'trackback') {
				$v->comment_content = apply_filters('dale6_pingback_or_trackback_comment_content', $v);
				$v->display_name = apply_filters('dale6_pingback_or_trackback_display_name', $v);
			}
			if (!empty($v->meta_value)) {
				$v->meta_value = array_sum(unserialize($v->meta_value));
			} else {
				$v->meta_value = 0;
			}
			if ($v->comment_parent == '0') {
				$comments_a[$v->comment_ID] = $v;
			} else {
				$comments_b[$v->comment_ID] = $v;
			}
		}
		unset($comments);
		// 挂钩子评论
		foreach ($comments_b as $v) {
			$comments_a = dale6_com_add_comment_children($comments_a, $v);
		}
		// 按照点赞排序
		$comments = array();
		if (count($comments_a) > 1) {
			while (1) {
				foreach ($comments_a as $k => $v) {
					if (isset($tmps)) {
						if ($tmps->meta_value < $v->meta_value) {
							$tmps = $v;
						}
					} else {
						$tmps = $v;
					}
				}
				$comments[] = isset($tmps) ? $tmps : ($tmps = current($comments_a));
				unset($comments_a[$tmps->comment_ID]);
				unset($tmps);
				if (count($comments_a) == 1) {
					$comments[] = array_shift($comments_a);
					break;
				}
			}
		} else {
			$comments[] = current($comments_a);
		}
		// 是否分页
		$pcs = get_option('page_comments');
		// 如果分页拿出当前页评论数据
		if ($pcs) {
			//获取当前评论列表页码
			$page = (int)get_query_var('cpage');
			//获取每页评论显示数量
			$per_page = (int) get_query_var('comments_per_page');
			if (0 === $per_page) {
				$per_page = (int) get_option('comments_per_page');
			}
			//总页数
			$zct = ceil($cnt / $per_page);
			// 分页获取数据
			$comments = array_slice($comments, ($page - 1) * $per_page, $per_page);
		}
	?>
		<div class="card border-0 bg-white mb-3">
			<div class="card-body">
				<h5 class="card-title"><?php echo $cnt . __('条评论', 'dale6_com'); ?></h5>

				<?php dale6_com_echo_comment($comments, $post, $user); ?>

				<?php get_template_part('tools/page_yema'); ?>
			</div>
		</div>
	<?php endif; ?>

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
			'must_log_in' => '<div class="card-body"><p class="card-title">' .  sprintf(__('必须<a %s> 登录 </a>才能发表评论'), ' href="" data-bs-toggle="modal" data-bs-target="#exampleModal"') . '</p></div>',
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
			'submit_button' => '<button type="button" class="btn btn-outline-dark" id="charudaima">' . __('插入代码', 'dale6_com') . '</button><button type="submit" class="%3$s ms-auto btn btn-outline-dark">%4$s</button></div><span class="text-muted">' . __('字符个数必须大于20小于5000个!') . '</span>',
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