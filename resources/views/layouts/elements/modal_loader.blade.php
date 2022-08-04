<div class="modal" id="modal_log_comment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo __('LABEL_COMMENT') ?></h4>
            </div>
            <div class="modal-body">
                <textarea name="modal_log_comment_body" id="modal_log_comment_body" rows="6"></textarea>
            </div>
            <div class="modal-footer">
                <img src="<?php echo $BASE_URL ?>/img/ajax-loader-2.gif" class="modal_loading hidden"/>
                <button type="button" class="btn btn-primary" id="btn_modal_log_comment_save"><?php echo __('LABEL_SAVE') ?></button>
                <button type="button" class="btn btn-default" id="btn_modal_log_comment_cancel" data-dismiss="modal"><?php echo __('LABEL_CANCEL') ?></button>
            </div>
        </div>
    </div>
</div>