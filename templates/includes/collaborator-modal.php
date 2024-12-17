<div id="cnrs-dm-collaborator-modal-wrapper">
    <form method="post" id="cnrs-dm-collaborator-modal-form" enctype="multipart/form-data">
        <h2><?php echo $data === null ? __('Add a collaborator', 'cnrs-data-manager') : __('Edit a collaborator', 'cnrs-data-manager') ?></h2>
        <?php if ($data !== null): ?>
            <input type="hidden" name="cnrs-data-manager-collaborator-id" value="<?php echo $data['id'] ?>">
        <?php endif; ?>
        <label class="required">
            <b><?php echo __('Collaborator name', 'cnrs-data-manager') ?></b>
            <input required type="text" spellcheck="false" autocomplete="off" name="cnrs-data-manager-collaborator-name" value="<?php echo $data['entity_name'] ?? '' ?>">
        </label>
        <label class="required">
            <b><?php echo __('Collaborator type', 'cnrs-data-manager') ?></b>
            <select required name="cnrs-data-manager-collaborator-type">
                <option<?php echo isset($data['entity_type']) && $data['entity_type'] === 'FUNDER' ? ' selected' : '' ?> value="FUNDER"><?php echo __('Funder', 'cnrs-data-manager') ?></option>
                <option<?php echo isset($data['entity_type']) && $data['entity_type'] === 'PARTNER' ? ' selected' : '' ?> value="PARTNER"><?php echo __('Partner', 'cnrs-data-manager') ?></option>
            </select>
        </label>
        <b><?php echo __('Collaborator logo', 'cnrs-data-manager') ?></b>
        <div id="cnrs-dm-collaborator-modal-image-container">
            <div id="cnrs-dm-collaborator-modal-image-left">
                <span id="cnrs-dm-collaborator-modal-image-wrapper">
                    <?php if (isset($data['entity_logo'])): ?>
                        <span id="cnrs-dm-collaborator-modal-image" style="background-image: url(<?php echo $data['entity_logo'] ?>)"></span>
                    <?php else: ?>
                        <span id="cnrs-dm-collaborator-modal-image" style="background-image: url(/wp-content/plugins/cnrs-data-manager/assets/media/default_avatar.png)"></span>
                    <?php endif; ?>
                </span>
            </div>
            <div id="cnrs-dm-collaborator-modal-image-right">
                <input type="hidden" name="cnrs-data-manager-collaborator-logo-hidden" value="<?php echo isset($data['entity_logo']) ? 'exist' : 'none' ?>">
                <label class="button button-primary">
                    <?php echo __('Upload an image', 'cnrs-data-manager') ?>
                    <input style="display: none;" type="file" accept="image/*" name="cnrs-data-manager-collaborator-logo">
                </label>
                <button id="cnrs-data-manager-collaborator-logo-delete" type="button" class="button"><?php echo __('Delete image', 'cnrs-data-manager') ?></button>
            </div>
        </div>
        <div id="cnrs-dm-collaborator-modal-btn-container">
            <button type="button" id="cnrs-dm-collaborator-modal-close" class="button"><?php echo __('Cancel', 'cnrs-data-manager') ?></button>
            <button type="submit" id="cnrs-dm-collaborator-modal-save" class="button button-primary"><?php echo __('Save', 'cnrs-data-manager') ?></button>
        </div>
    </form>
</div>
