<?php
// 改成ajax获取,因为页面有缓存
$_SESSION['send_mailcode'] = rand(10000, 99999);
$_SESSION['logincode'] = md5((float)microtime());
?>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-5">
            <div class="modal-header border-0">
                <h3 class="modal-title"><?php bloginfo('name') ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="tab-content" style="min-height:400px;">
                    <div class="form-floating mb-4">
                        <input type="email" class="form-control" id="r_mail" placeholder="name@example.com">
                        <label for="r_mail"><?php _e('邮箱', 'dale6_com'); ?></label>
                    </div>
                    <div class="input-group mb-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="codemm" placeholder="emailcode">
                            <label for="codemm"><?php _e('邮件验证码', 'dale6_com'); ?></label>
                        </div>
                        <button onclick="ds_send_mail_code(this);" class="btn btn-outline-dark">发送验证码</button>
                    </div>

                    <div class="mb-4 d-grid">
                        <button type="button" class="btn btn-success" onclick="ds_login(this);"><?php _e('登录', 'dale6_com'); ?></button>
                    </div>

                    <p>本网站不用注册,实行邮件验证码登录!</p>
                    <p>请谨慎发言!</p>
                </div>
                <div id="userlogin"></div>
            </div>
        </div>
    </div>
</div>