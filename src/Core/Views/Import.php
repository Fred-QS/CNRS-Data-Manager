<div class="wrap" style="position: relative">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_IMPORT_ICON, '#5d5d5d', 26) ?>
        <?= __('Import projects', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?= __('The extension allows you to import <b>Projects</b> in bulk. To do this, please follow the instructions below, namely the type of file to upload in <b>ZIP</b> format, the structure contained in the file as well as the columns that must be present and completed in the expected format for the file import.', 'cnrs-data-manager') ?>
    </p>
    <p>
        <a class="cnrs-dm-template-link" href="/wp-content/plugins/cnrs-data-manager/templates/samples/projects.xlsx" download="<?= __('projects', 'cnrs-data-manager') ?>.xlsx">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM155.7 250.2L192 302.1l36.3-51.9c7.6-10.9 22.6-13.5 33.4-5.9s13.5 22.6 5.9 33.4L221.3 344l46.4 66.2c7.6 10.9 5 25.8-5.9 33.4s-25.8 5-33.4-5.9L192 385.8l-36.3 51.9c-7.6 10.9-22.6 13.5-33.4 5.9s-13.5-22.6-5.9-33.4L162.7 344l-46.4-66.2c-7.6-10.9-5-25.8 5.9-33.4s25.8-5 33.4 5.9z"/>
            </svg>
            <span><?= __('Download <b>.xlsx</b> file template', 'cnrs-data-manager') ?></span>
        </a>
    </p>
    <p class="cnrs-dm-label-like"><?= __('Expected structure', 'cnrs-data-manager') ?></p>
    <?php include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-file-structure.php'); ?>
    <p>
        <?= __('The file will be tested first. If errors are present, these will be listed so that the file can be modified accordingly. Otherwise, you will be able to confirm the import.', 'cnrs-data-manager') ?>
    </p>
    <form method="post" enctype="multipart/form-data" id="cnrs-dm-file-import-form">
        <input type="file" name="cnrs-dm-projects" id="cnrs-dm-import-file" accept=".zip" data-error="<?= __('WordPress was unable to load the file correctly. Please try Again by checking your file content to fit the waited structure.', 'cnrs-data-manager') ?>" data-step1="<?= __('File uploaded', 'cnrs-data-manager') ?>" data-step2="<?= __('Checking files', 'cnrs-data-manager') ?>" data-ko="<?= __('The import failed.', 'cnrs-data-manager') ?>" data-ok="<?= __('The projects were imported successfully.', 'cnrs-data-manager') ?>">
        <p>
            <i class="cnrs-dm-import-filesize"><?= __('Maximum file size', 'cnrs-data-manager') ?>: <?= ini_get('upload_max_filesize') ?></i>
            <button id="cnrs-dm-import-file-btn" type="button" class="button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM96 48c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16zm0 64c0-8.8 7.2-16 16-16h32c8.8 0 16 7.2 16 16s-7.2 16-16 16H112c-8.8 0-16-7.2-16-16zm-6.3 71.8c3.7-14 16.4-23.8 30.9-23.8h14.8c14.5 0 27.2 9.7 30.9 23.8l23.5 88.2c1.4 5.4 2.1 10.9 2.1 16.4c0 35.2-28.8 63.7-64 63.7s-64-28.5-64-63.7c0-5.5 .7-11.1 2.1-16.4l23.5-88.2zM112 336c-8.8 0-16 7.2-16 16s7.2 16 16 16h32c8.8 0 16-7.2 16-16s-7.2-16-16-16H112z"/>
                </svg>
                <span><?= __('Import <b>.zip</b> file', 'cnrs-data-manager') ?></span>
            </button>
        </p>
        <input type="submit" id="cnrs-dm-file-import-form-submit">
    </form>
    <p class="cnrs-dm-label-like"><?= __('Import steps', 'cnrs-data-manager') ?></p>
    <div id="cnrs-dm-import-initial-state-container" class="cnrs-dm-import-state-container">
        <ul>
            <li class="cnrs-dm-import-state-pending">
                <i class="cnrs-dm-import-state-waiting cnrs-dm-import-state">1.&nbsp;<?= __('Waiting for file', 'cnrs-data-manager') ?></i>
                <i class="cnrs-dm-import-state-response cnrs-dm-import-state hide">1.&nbsp;<?= __('File uploaded', 'cnrs-data-manager') ?></i>
                <span class="cnrs-dm-import-state-logo hide">
                    <svg id="cnrs-dm-import-good" viewBox="0 0 448 512">
                        <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                    </svg>
                </span>
            </li>
            <li class="cnrs-dm-import-state-pending">
                <i class="cnrs-dm-import-state-waiting cnrs-dm-import-state">2.&nbsp;<?= __('Checking files', 'cnrs-data-manager') ?></i>
                <span class="cnrs-dm-import-state-logo hide">
                    <svg id="cnrs-dm-import-refresh" viewBox="0 0 512 512">
                        <path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H352c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V80c0-17.7-14.3-32-32-32s-32 14.3-32 32v35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V432c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H160c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/>
                    </svg>
                </span>
            </li>
        </ul>
    </div>
    <div id="cnrs-dm-import-response-state-container" class="cnrs-dm-import-state-container"></div>
</div>