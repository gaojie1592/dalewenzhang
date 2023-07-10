<form action="/" method="get" class="input-group flex-nowrap">
    <input type="hidden" value="post" name="post_type" />
    <input type="text" name="s" value="<?php the_search_query(); ?>" class="form-control wm-150px" placeholder="<?php _e('php', 'dalewenzhang') ?>" />
    <button type="submit" aria-label="<?php _e('搜索按钮', 'dalewenzhang') ?>" class="btn btn-outline-dark" type="button">
        <i class="bi bi-search"></i>
    </button>
</form>