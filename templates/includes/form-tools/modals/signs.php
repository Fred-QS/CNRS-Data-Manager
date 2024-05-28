<?php $info = isset($json['json']) ? json_decode($json['json'], true) : $data; ?>

<div id="cnrs-dm-form-modal-wrapper">
    <form id="cnrs-dm-form-modal">
        <h3><?php echo __('Signing pads settings', 'cnrs-data-manager') ?></h3>
        <input type="hidden" name="cnrs-dm-iteration" value="<?php echo $iteration ?>">
        <input type="hidden" name="cnrs-dm-type" value="signs">
        <input type="hidden" name="cnrs-dm-isReference" value="<?php echo $info['data']['isReference'] === true ? 1 : 0 ?>">
        <input type="hidden" name="cnrs-dm-label" value="<?php echo $info['label'] ?>">
        <p><?php echo __('Add pads', 'cnrs-data-manager') ?><span class="cnrs-dm-form-add-choice">+</span></p>
        <small><?php echo __('Use ";" as separator for multiline label', 'cnrs-data-manager') ?></small>
        <ol id="cnrs-dm-form-modal-choices">
            <?php foreach ($info['data']['choices'] as $choice): ?>
                <li class="cnrs-dm-form-modal-choice">
                    <label>
                        <input type="text" spellcheck="false" name="cnrs-dm-form-modal-choice[]" value="<?php echo $choice ?>">
                        <span class="cnrs-dm-form-remove-choice">-</span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ol>
        <div id="cnrs-dm-form-modal-buttons-wrapper">
            <input type="button" id="cnrs-dm-form-button-cancel" class="button" value="<?php echo __('Cancel', 'cnrs-data-manager') ?>">
            <input type="button" id="cnrs-dm-form-button-save" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
        </div>
    </form>
</div>
