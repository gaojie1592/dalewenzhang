<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-5">
            <div class="modal-header border-0">
                <h3 class="modal-title"><?php bloginfo('name') ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="tab-content">
                    <?php echo apply_filters('dalewenzhang_login_form', wp_login_form(array('echo' => false))); ?>
                </div>
            </div>
        </div>
    </div>
</div>