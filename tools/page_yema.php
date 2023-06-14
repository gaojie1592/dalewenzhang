<?php
$pag_arr = paginate_links(array('type' => 'array'));
if (is_single()) {
    $pag_arr = paginate_comments_links(array('type' => 'array'));
}
// file_put_contents(__DIR__ . '/tmp', print_r($pag_arr, true), FILE_APPEND);
?>
<?php if (is_array($pag_arr) && count($pag_arr) > 0) : ?>
    <nav id="page_ym" class="pt-3">
        <ul class="pagination">
            <?php foreach ($pag_arr as $v) {
                echo '<li class="page-item">', str_ireplace("page-numbers", "page-link text-dark", $v), '</li>';
            } ?>
        </ul>
    </nav>
<?php endif ?>