<?php $info = isset($json['json']) ? json_decode($json['json'], true) : $data; ?>

<div id="cnrs-dm-form-modal-wrapper">
    <form id="cnrs-dm-form-modal">
        <h3><?php echo __('Toggle field settings', 'cnrs-data-manager') ?></h3>
        <input type="hidden" name="cnrs-dm-iteration" value="<?php echo $iteration ?>">
        <input type="hidden" name="cnrs-dm-type" value="toggle">
        <input type="hidden" name="cnrs-dm-toggle-uuid" value="<?php echo $info['data']['value'][0] ?>">
        <input type="hidden" name="cnrs-dm-isReference" value="<?php echo $info['data']['isReference'] === true ? 1 : 0 ?>">
        <label class="cnrs-dm-form-modal-label">
            <span><?php echo __('Label', 'cnrs-data-manager') ?></span>
            <input spellcheck="false" type="text" name="cnrs-dm-label" value="<?php echo $info['label'] ?>">
        </label>
        <div class="cnrs-dm-form-modal-label-toggles-container">
            <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-toggle">
                <span class="required"><?php echo __('First choice value', 'cnrs-data-manager') ?></span>
                <input spellcheck="false" type="text" name="cnrs-dm-toggle-first-toggle" value="<?php echo $info['data']['values'][0] ?? '' ?>">
            </label>
            <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-toggle">
                <span class="required"><?php echo __('Second choice value', 'cnrs-data-manager') ?></span>
                <input spellcheck="false" type="text" name="cnrs-dm-toggle-second-toggle" value="<?php echo $info['data']['values'][1] ?? '' ?>">
            </label>
        </div>
        <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-tooltip">
            <span><?php echo __('Tooltip', 'cnrs-data-manager') ?></span>
            <textarea spellcheck="false" autocomplete="off" name="cnrs-dm-tooltip"><?php echo str_replace('<br/>', "\n", $info['data']['tooltip']) ?></textarea>
        </label>
        <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-required" style="display: none;">
            <input type="checkbox" name="cnrs-dm-required-option" value="required" checked>
        </label>
        <div id="cnrs-dm-form-modal-buttons-wrapper">
            <input type="button" id="cnrs-dm-form-button-cancel" class="button" value="<?php echo __('Cancel', 'cnrs-data-manager') ?>">
            <input type="button"<?php echo isset($info['data']['values'][0], $info['data']['values'][1]) ? '' : ' disabled' ?> id="cnrs-dm-form-button-save" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
        </div>
    </form>
</div>
