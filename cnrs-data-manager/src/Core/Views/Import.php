<?php

use CnrsDataManager\Core\Models\Projects;

Projects::uploadFile();

?>

<div class="wrap" style="position: relative">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_IMPORT_ICON, '#5d5d5d', 26) ?>
        <?= __('Import projects', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?= __('The extension allows you to import <b>Projects</b> in bulk. To do this, please follow the instructions below, namely the type of file to upload in <b>ZIP</b> format, the structure contained in the file as well as the columns that must be present and completed in the expected format for the file import.', 'cnrs-data-manager') ?>
    </p>
    <p>
        <?= __('The file will be tested first. If errors are present, these will be listed so that the file can be modified accordingly. Otherwise, you will be able to confirm the import.', 'cnrs-data-manager') ?>
    </p>
    <form method="post" enctype="multipart/form-data" id="cnrs-dm-file-import-form">
        <input type="file" name="cnrs-dm-projects" id="cnrs-dm-import-file" accept=".zip" data-error="<?= __('WordPress was unable to contact the server correctly. Please try Again.', 'cnrs-data-manager') ?>">
        <p>
            <button id="cnrs-dm-import-file-btn" type="button" class="button button-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM96 48c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16zm-6.3 71.8c3.7-14 16.4-23.8 30.9-23.8h14.8c14.5 0 27.2 9.7 30.9 23.8l23.5 88.2c1.4 5.4 2.1 10.9 2.1 16.4c0 35.2-28.8 63.7-64 63.7s-64-28.5-64-63.7c0-5.5 .7-11.1 2.1-16.4l23.5-88.2zM112 336c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H112z"/>
                </svg>
                <span><?= __('Import <b>.zip</b> file', 'cnrs-data-manager') ?></span>
            </button>
        </p>
        <input type="submit" value="Test" id="cnrs-dm-file-import-form-submit">
    </form>
</div>
