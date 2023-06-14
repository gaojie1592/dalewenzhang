<div class="container py-3 mt-4 border-top d-flex flex-column">
    <div class="footerlist">
        <ul class="navbar-nav itemlist align-items-start flex-wrap justify-content-center">
            <?php
            wp_nav_menu(array(
                'theme_location'  => 'footer_nav',
                'container'       => false,
                'items_wrap'      => '%3$s',
                'fallback_cb'     => 'to_zhuti_bianji_link',
            ));
            ?>
        </ul>
    </div>
    <div class="p-2 fw-light dl_a text-center">
        <span class="text-muted">Copyright © <?php echo date("Y") ?></span>
        <a class="text-decoration-none" href="<?php echo home_url() ?>" target="_blank"><?php echo get_bloginfo("name") ?></a>
        <span class="text-muted"> · 基于 </span><a href="https://wordpress.org" target="_blank">WordPress</a>
        <span class="text-muted"> · </span><a class="text-decoration-none" href="<?php echo home_url() ?>" target="_blank">使用大乐主题</a>
        <?php global $options; ?>
        <?php if (isset($options['yejiaozhuijia']) && !empty($options['yejiaozhuijia'])) : ?>
            <?php echo $options['yejiaozhuijia']; ?>
        <?php endif; ?>
    </div>
</div>
<script>
    dale6_addLoadEvent(function() {
        const colors = <?php dale6_com_echo_pinglun_txbj_color(); ?>;
        let colorIndex = 0;
        document.querySelectorAll('.dale6_com_user_ico').forEach(function(e) {
            let el = e.firstElementChild;
            let url = el.getAttribute('data-src');
            let alt = el.getAttribute('alt');
            let width = el.getAttribute('width');
            let height = el.getAttribute('height');
            e.style.width = width + "px";
            e.style.height = height + "px";
            el.remove();
            <?php if (isset($options['pingluntxleixing']) && $options['pingluntxleixing'] == '2' || empty($options['pingluntxleixing'])) : ?>
                var img = new Image();
                img.src = url;
                img.onload = function() {
                    let newimg = new Image();
                    newimg.src = url;
                    newimg.alt = alt;
                    newimg.width = width;
                    newimg.height = height;
                    e.firstElementChild.remove();
                    e.appendChild(newimg);
                };
            <?php endif; ?>
            let div = document.createElement('div');
            div.style.backgroundColor = colors[colorIndex++ % colors.length];
            div.style.lineHeight = height + 'px';
            div.classList.add('dale6_com_user_ico_div');
            div.innerText = alt.substr(0, 1).toUpperCase();
            e.appendChild(div);
        });
    });
</script>
<?php wp_footer() ?>