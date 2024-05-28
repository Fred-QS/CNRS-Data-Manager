<?php $info = isset($json['json']) ? json_decode($json['json'], true) : $data; ?>

<div id="cnrs-dm-form-modal-wrapper">
    <form id="cnrs-dm-form-modal">
        <h3><?php echo __('Comment field settings', 'cnrs-data-manager') ?></h3>
        <input type="hidden" name="cnrs-dm-iteration" value="<?php echo $iteration ?>">
        <input type="hidden" name="cnrs-dm-type" value="comment">
        <input type="hidden" name="cnrs-dm-isReference" value="<?php echo $info['data']['isReference'] === true ? 1 : 0 ?>">
        <label class="cnrs-dm-form-modal-label">
            <span><?php echo __('Your comment', 'cnrs-data-manager') ?></span>
            <textarea id="cnrs-dm-tinymce" class="cnrs-dm-tinymce" spellcheck="false" name="cnrs-dm-comment"><?php echo str_replace('<br/>', "\n", $info['data']['value'][0]) ?></textarea>
        </label>
        <div id="cnrs-dm-form-modal-buttons-wrapper">
            <input type="button" id="cnrs-dm-form-button-cancel" class="button" value="<?php echo __('Cancel', 'cnrs-data-manager') ?>">
            <input type="button" id="cnrs-dm-form-button-save" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
        </div>
    </form>
</div>
