<?php $pag_arr = paginate_links(array('type' => 'array')); ?>
<?php if (is_array($pag_arr) && count($pag_arr) > 0) : ?>
    <nav id="page_ym" class="pt-3">
        <ul class="pagination">
            <?php foreach ($pag_arr as $v) {
                echo '<li class="page-item">', str_ireplace("page-numbers", "page-link text-dark", $v), '</li>';
            } ?>
        </ul>
    </nav>
<?php endif ?>