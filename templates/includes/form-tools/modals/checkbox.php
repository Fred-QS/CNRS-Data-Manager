<?php $info = isset($json['json']) ? json_decode($json['json'], true) : $data; ?>

<div id="cnrs-dm-form-modal-wrapper" data-comment="<?php echo __('Comment', 'cnrs-data-manager') ?>">
    <form id="cnrs-dm-form-modal">
        <h3><?php echo __('Multi choice settings', 'cnrs-data-manager') ?></h3>
        <input type="hidden" name="cnrs-dm-iteration" value="<?php echo $iteration ?>">
        <input type="hidden" name="cnrs-dm-type" value="checkbox">
        <input type="hidden" name="cnrs-dm-isReference" value="<?php echo $info['data']['isReference'] === true ? 1 : 0 ?>">
        <label class="cnrs-dm-form-modal-label">
            <span><?php echo __('Label', 'cnrs-data-manager') ?></span>
            <input spellcheck="false" type="text" name="cnrs-dm-label" value="<?php echo $info['label'] ?>">
        </label>
        <p><?php echo __('Add choices', 'cnrs-data-manager') ?><span class="cnrs-dm-form-add-choice">+</span></p>
        <ol id="cnrs-dm-form-modal-choices">
            <?php foreach ($info['data']['choices'] as $choice): ?>
                <li class="cnrs-dm-form-modal-choice">
                    <label>
                        <input type="text" spellcheck="false" name="cnrs-dm-form-modal-choice[]" value="<?php echo str_replace('-opt-comment', '', $choice) ?>">
                        <span class="cnrs-dm-form-remove-choice">-</span>
                    </label>
                    <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-other">
                        <input type="checkbox" name="cnrs-dm-other-option" value="other"<?php if (stripos($choice, '-opt-comment') !== false): echo ' checked'; endif; ?>>
                        <span><?php echo __('Comment', 'cnrs-data-manager') ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ol>
        <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-tooltip">
            <span><?php echo __('Tooltip', 'cnrs-data-manager') ?></span>
            <textarea spellcheck="false" autocomplete="off" name="cnrs-dm-tooltip"><?php echo str_replace('<br/>', "\n", $info['data']['tooltip']) ?></textarea>
        </label>
        <label class="cnrs-dm-form-modal-label cnrs-dm-form-modal-label-required">
            <input type="checkbox" name="cnrs-dm-required-option" value="required" <?php if ($info['data']['required'] === true): echo 'checked'; endif; ?>>
            <span><?php echo __('Required', 'cnrs-data-manager') ?></span>
        </label>
        <div id="cnrs-dm-form-modal-buttons-wrapper">
            <input type="button" id="cnrs-dm-form-button-cancel" class="button" value="<?php echo __('Cancel', 'cnrs-data-manager') ?>">
            <input type="button" id="cnrs-dm-form-button-save" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
        </div>
    </form>
</div>
