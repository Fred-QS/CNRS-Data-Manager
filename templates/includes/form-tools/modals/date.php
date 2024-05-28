<?php $info = isset($json['json']) ? json_decode($json['json'], true) : $data; ?>

<div id="cnrs-dm-form-modal-wrapper">
    <form id="cnrs-dm-form-modal">
        <h3>
            <?php echo __('Date field settings', 'cnrs-data-manager') ?>
            <?php if ($info['data']['isReference'] === true): ?>
                <span class="cnrs-dm-preview-reference-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="16" height="16">
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
                    </svg>
                </span>
            <?php endif; ?>
        </h3>
        <input type="hidden" name="cnrs-dm-iteration" value="<?php echo $iteration ?>">
        <input type="hidden" name="cnrs-dm-type" value="date">
        <input type="hidden" name="cnrs-dm-isReference" value="<?php echo $info['data']['isReference'] === true ? 1 : 0 ?>">
        <label class="cnrs-dm-form-modal-label">
            <span><?php echo __('Label', 'cnrs-data-manager') ?></span>
            <input spellcheck="false" type="text" name="cnrs-dm-label" value="<?php echo $info['label'] ?>">
        </label>
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
