<?php do_action('dalewenzhang_custom_header');?>

<div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasTopLabel">Offcanvas top</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="/" method="get" class="input-group flex-nowrap">
            <input type="hidden" value="post" name="post_type" />
            <input type="text" name="s" value="<?php the_search_query(); ?>" class="form-control w-100" placeholder="<?php _e('请输入要搜索的字符', 'dalewenzhang') ?>" id="ssinput" aria-label="<?php _e('请输入要搜索的字符', 'dalewenzhang') ?>" />
            <button type="submit" aria-label="<?php _e('搜索按钮', 'dalewenzhang') ?>" class="btn btn-outline-dark" type="button">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
</div>