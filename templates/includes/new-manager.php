<div class="cnrs-dm-manager-block">
    <input type="hidden" name="cnrs-dm-manager[<?php echo 'new_' . $iteration ?>][id]" value="new">
    <span class="cnrs-dm-tool-button cnrs-dm-manager-delete-button">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
            <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"></path>
        </svg>
    </span>
    <div>
        <label for="cnrs-dm-manager-convention-<?php echo 'new_' . $iteration ?>"><?php echo __('Convention name', 'cnrs-data-manager') ?></label>
        <input required type="text" spellcheck="false" id="cnrs-dm-manager-convention-<?php echo 'new_' . $iteration ?>" name="cnrs-dm-manager[<?php echo 'new_' . $iteration ?>][name]">
    </div>
    <div class="cnrs-dm-manager-wrapper-with-availability">
        <label for="cnrs-dm-manager-primary-<?php echo 'new_' . $iteration ?>"><?php echo __('Main email', 'cnrs-data-manager') ?></label>
        <div>
            <label for="cnrs-dm-manager-available-<?php echo 'new_' . $iteration ?>"><?php echo __('Not available', 'cnrs-data-manager') ?></label>
            <input type="checkbox" id="cnrs-dm-manager-available-<?php echo 'new_' . $iteration ?>" name="cnrs-dm-manager[<?php echo 'new_' . $iteration ?>][available]">
        </div>
        <input required type="email" spellcheck="false" id="cnrs-dm-manager-primary-<?php echo 'new_' . $iteration ?>" name="cnrs-dm-manager[<?php echo 'new_' . $iteration ?>][primary_email]">
    </div>
    <div>
        <label for="cnrs-dm-manager-secondary-<?php echo 'new_' . $iteration ?>"><?php echo __('Fallback email', 'cnrs-data-manager') ?></label>
        <input required type="email" spellcheck="false" id="cnrs-dm-manager-secondary-<?php echo 'new_' . $iteration ?>" name="cnrs-dm-manager[<?php echo 'new_' . $iteration ?>][secondary_email]">
    </div>
</div>
