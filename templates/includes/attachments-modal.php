<div id="cnrs-dm-attachments-modal-wrapper" data-project="<?php echo $projectId ?>">
    <div id="cnrs-dm-attachments-modal-container">
        <h2><?php echo __('Select images form the media library', 'cnrs-data-manager') ?></h2>
        <div id="cnrs-dm-attachments-list">
            <?php foreach ($images as $image): ?>
                <div class="cnrs-dm-attachments-image-container">
                    <label class="cnrs-dm-attachments-image<?php echo in_array($image->ID, $imagesFromProject, true) ? ' selected' : '' ?>" style="background-image: url(<?php echo image_get_intermediate_size($image->ID)['url'] ?? null ?>);" data-id='<?php echo $image->ID ?>'>
                        <input<?php echo in_array($image->ID, $imagesFromProject, true) ? ' checked' : '' ?> type="checkbox" value="<?php echo $image->ID ?>">
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="cnrs-dm-attachments-modal-btn-container">
            <button type="button" id="cnrs-dm-attachments-modal-close" class="button"><?php echo __('Cancel', 'cnrs-data-manager') ?></button>
            <button type="submit" id="cnrs-dm-attachments-modal-save" class="button button-primary"><?php echo __('Save', 'cnrs-data-manager') ?></button>
        </div>
    </div>
</div>
