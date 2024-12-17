<div id="cnrs-dm-form-modal-wrapper">
    <form id="cnrs-dm-form-modal">
        <h3><?php echo sprintf(__('Toggles settings for "%s"', 'cnrs-data-manager'), $label) ?></h3>
        <input type="hidden" name="cnrs-dm-iteration" value="<?php echo $iteration ?>">
        <p><?php echo __('Toggles list', 'cnrs-data-manager') ?></p>
        <i><?php echo __('Check the options for each toggle if you want the field to be visible when the option is chosen in the form.', 'cnrs-data-manager') ?></i>
        <div class="cnrs-dm-toggle-setting-container">
            <?php foreach ($toggles as $id => $toggle): ?>
                <div class="cnrs-dm-toggle-setting" data-id="<?php echo $id ?>">
                    <span><?php echo $toggle['label'] ?></span>
                    <ul>
                        <li>
                            <label>
                                <input type="checkbox" id="cnrs-dm-toggle-option1-<?php echo $id ?>" value="0"<?php echo $toggle['option1']['active'] === true ? ' checked' : '' ?>>
                                <span><?php echo $toggle['option1']['value'] ?></span>
                            </label>
                        </li>
                        <li>
                            <label>
                                <input type="checkbox" id="cnrs-dm-toggle-option2-<?php echo $id ?>" value="1"<?php echo $toggle['option2']['active'] === true ? ' checked' : '' ?>>
                                <span><?php echo $toggle['option2']['value'] ?></span>
                            </label>
                        </li>
                        <?php if (isset($toggle['option3'])): ?>
                            <li>
                                <label>
                                    <input type="checkbox" id="cnrs-dm-toggle-option3-<?php echo $id ?>" value="1"<?php echo $toggle['option3']['active'] === true ? ' checked' : '' ?>>
                                    <span><?php echo $toggle['option3']['value'] ?></span>
                                </label>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="cnrs-dm-form-modal-buttons-wrapper">
            <input type="button" id="cnrs-dm-form-button-cancel" class="button" value="<?php echo __('Cancel', 'cnrs-data-manager') ?>">
            <input type="button" id="cnrs-dm-form-button-save" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
        </div>
    </form>
</div>
