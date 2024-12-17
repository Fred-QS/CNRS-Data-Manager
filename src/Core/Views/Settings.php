<?php

use CnrsDataManager\Core\Models\Settings;
//use CnrsDataManager\Core\Models\Projects;

//Projects::importRelations();

$settings = Settings::getSettings();
$hiddenFiltersByCatId = Settings::getHiddenTermsIds();
$candidatingCatIds = Settings::getCandidatingTermsIds();
$teamsConfig = getCategoriesConfig('teams', $settings['teams_category']);
$servicesConfig = getCategoriesConfig('services', $settings['services_category']);
$platformsConfig = getCategoriesConfig('platforms', $settings['platforms_category']);
$categories = cnrs_get_translated_categories();
$designs = Settings::getDesigns();
$designsTypes = [
    'POSTER' => __('Poster', 'cnrs-data-manager'),
    'CARD' => __('Card', 'cnrs-data-manager'),
    'THUMBNAIL' => __('Thumbnail', 'cnrs-data-manager')
];

?>

<div class="wrap cnrs-data-manager-page">
    <h1 class="wp-heading-inline title-and-logo">
        <?php echo svgFromBase64(CNRS_DATA_MANAGER_SETTINGS_ICON, '#5d5d5d') ?>
        <?php echo __('Settings', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?php echo __('Here you can update the settings of the CNRS Data Manager extension.', 'cnrs-data-manager') ?> <?php echo __('In order to save your changes, please click on the <b>Update</b> button.', 'cnrs-data-manager') ?>
    </p>

    <p>
        <?php echo __('The <b>CNRS Data Manager</b> extension uses Shortcodes to be able to render the different views. You can use the <b>Filter</b> and <b>Default View</b> options in order to best personalize the renderings according to your integration.', 'cnrs-data-manager') ?>
    </p>
    <?php if ($settings['filename'] === null): ?>
        <p>
            <?php echo __('Before you can use the extension, you must enter the URL of the <b>Soap API</b> that WordPress must contact to retrieve data from your UMR.', 'cnrs-data-manager') ?>
        </p>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="action" value="<?php echo $settings['filename'] === null ? 'init' : 'update' ?>">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename">
                            <?php echo __('API endpoint URL', 'cnrs-data-manager') ?>
                            <div class="cnrs-dm-filename-states-container">
                                <svg class="cnrs-dm-filename-states" id="cnrs-dm-filename-bad" viewBox="0 0 384 512">
                                    <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                                </svg>
                                <svg class="cnrs-dm-filename-states" id="cnrs-dm-filename-good" viewBox="0 0 448 512">
                                    <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                                </svg>
                                <svg class="cnrs-dm-display-state cnrs-dm-filename-states" id="cnrs-dm-filename-refresh" viewBox="0 0 512 512">
                                    <path d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H352c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V80c0-17.7-14.3-32-32-32s-32 14.3-32 32v35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V432c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H160c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/>
                                </svg>
                            </div>
                        </label>
                    </th>
                    <td>
                        <p style="position: relative;">
                            <small id="cnrs-dm-filename-error-input"><?php echo __('The field cannot be empty and has a maximum of 255 characters.') ?></small>
                            <input name="cnrs-dm-filename" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-filename" value="<?php echo $settings['filename'] ?>" class="regular-text">
                        </p>
                    </td>
                </tr>
                <?php if ($settings['filename'] !== null): ?>
                    <tr>
                        <th scope="row">
                            <label for="cnrs-dm-mode"><?php echo __('Candidating email', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="email" name="cnrs-dm-candidating-email" value="<?php echo $settings['candidating_email'] ?>">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cnrs-dm-mode"><?php echo __('Default image URL', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <label>
                                <input required spellcheck="false" type="text" name="cnrs-dm-project-default-image-url" value="<?php echo $settings['project_default_image_url'] ?>">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cnrs-dm-mode"><?php echo __('Default thumbnail URL', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <label>
                                <input required spellcheck="false" type="text" name="cnrs-dm-project-default-thumbnail-url" value="<?php echo $settings['project_default_thumbnail_url'] ?>">
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cnrs-dm-mode"><?php echo __('List display mode', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <p>
                                <select name="cnrs-dm-mode" id="cnrs-dm-mode">
                                    <option <?php echo $settings['mode'] === 'widget' ? 'selected' : '' ?> value="widget"><?php echo __('Widget', 'cnrs-data-manager') ?></option>
                                    <option <?php echo $settings['mode'] === 'page' ? 'selected' : '' ?> value="page"><?php echo __('Page', 'cnrs-data-manager') ?></option>
                                </select>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($settings['filename'] !== null): ?>
            <p class="cnrs-dm-shortcode-p<?php echo $settings['mode'] === 'page' ? '' : ' hide' ?>" id="cnrs-dm-page-option-shortcode">
                <?php echo __('If <b>Page</b> mode is enabled, please use this shortcode as the value for the button link which will point to the page of the agents concerned by the article and its category.', 'cnrs-data-manager') ?>
                <br/>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="navigate" text="button title" target="<?php echo __('/url/to/reach', 'cnrs-data-manager') ?>"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <?php echo __('You can also use the shortcode below that of the <b>navigation</b> to display a dynamic title showing the title <b>Member of the <i>"entity"</i> <i>"entity name"</i></b> in the page where the <b>members list</b> shortcode will be implemented.', 'cnrs-data-manager') ?>
                <br/>
                <br/>
                <b><?php echo __('Team page title', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="page-title" entity="teams"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <b><?php echo __('Service page title', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="page-title" entity="services"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <b><?php echo __('Platform page title', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="page-title" entity="platforms"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <b><?php echo __('Project default image URL', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="project-default-image-url"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
            </p>
            <hr/>
            <table class="form-table" role="presentation">
                <tbody
                    <tr>
                        <th scope="row">
                            <label><?php echo __('Agents directory', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <p class="cnrs-dm-shortcode-p">
                                <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="all"]'>
                                    <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                    <code>[cnrs-data-manager type="all"]</code>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                    </svg>
                                </span>
                            </p>
                        </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('3D Map builder', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="map"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="map"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Publications', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="publications"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="publications"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Team projects', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="projects"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="projects"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Project collaborators', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="collabs"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="collabs"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Project images slider', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="project-slider"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="project-slider"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
            <hr/>
            <p>
                <?php echo __('In order to optimize the use of filters and pagination, you can choose to use the custom page kit containing the files <b>category.php</b>, <b>archive.php</b> and <b>cnrs-script.js</b> provided by the extension. It will be up to you to create your own design in the corresponding CSS and JS files (see the list in the <b>Tools</b> tab).', 'cnrs-data-manager') ?>
                <br/>
                <?php echo __('To use these templates, please check the <b>Use templates</b> box below. The extension will create the <b>custom pages kit</b> at the root of the activated theme, including filters and pagination.', 'cnrs-data-manager') ?>
            </p>
            <div>
                <label>
                    <input<?php echo (int) $settings['category_template'] === 1 ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-category-template">
                    <b><?php echo __('Use templates', 'cnrs-data-manager') ?></b>
                </label>
            </div>
            <p><?php echo __('Otherwise, you can also implement the filter and/or pagination system <b>manually</b> using the shortcodes below.', 'cnrs-data-manager') ?></p>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Filters', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?php echo __('To be implemented in category page only.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <b><?php echo __('Filter modules list', 'cnrs-data-manager') ?></b>
                        <br/>
                        <ul class="cnrs-dm-filter-modules">
                            <li>
                                <label>
                                    <input<?php echo stripos($settings['filter_modules'], 'per-page') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="per-page"><i><?php echo __('Posts per page', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input<?php echo stripos($settings['filter_modules'], 'sub-categories-list') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="sub-categories-list"><i><?php echo __('Categories selector', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input<?php echo stripos($settings['filter_modules'], 'by-year') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="by-year"><i><?php echo __('Filter by years', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input<?php echo stripos($settings['filter_modules'], 'search-field') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="search-field"><i><?php echo __('Search field', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                        </ul>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode">
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="filters"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Disable filters', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?php echo __('Hide filters on certain category or project landing pages.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <?php cnrs_polylang_installed() ?>
                        <div class="cnrs-dm-filters-allowed-wrapper">
                            <?php foreach ($categories as $row): ?>
                                <div class="cnrs-dm-filters-allowed-container">
                                    <?php foreach ($row as $lang => $category): ?>
                                        <label>
                                            <?php echo $category['flag'] !== null ? $category['flag'] : '' ?>
                                            <input<?php echo in_array((int) $category['term_id'], $hiddenFiltersByCatId, true) ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-allowed[]" value="<?php echo $category['term_id'] ?>">
                                            <i><?php echo $category['name'] ?></i>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Pagination', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?php echo __('To be implemented in category page only.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <div>
                            <label>
                                <input<?php echo (int) $settings['silent_pagination'] === 1 ? ' checked' : '' ?> type="checkbox" id="cnrs-dm-pagination-ajax-checkbox" data-code='[cnrs-data-manager type="pagination" silent-selector="#my-css-selector"]' name="cnrs-dm-pagination-ajax-checkbox">
                                <b><?php echo __('Silent paging', 'cnrs-data-manager') ?></b></label>
                            <br/>
                            <i class="cnrs-data-manager-disclaimer"><?php echo __('If you choose <b>silent pagination</b>, you must add <b>class="cnrs-dm-front-silent-container"</b> attribute to the HTML element containing the posts.', 'cnrs-data-manager') ?></i>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode">
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="pagination"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Categories', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?php echo __('To be implemented in categories / projects landing page only.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode">
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="categories"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Candidating', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?php echo __('Associate categories and candidating.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <?php cnrs_polylang_installed() ?>
                        <div class="cnrs-dm-filters-allowed-wrapper">
                            <?php foreach ($categories as $row): ?>
                                <div class="cnrs-dm-filters-allowed-container">
                                    <?php foreach ($row as $lang => $category): ?>
                                        <label>
                                            <?php echo $category['flag'] !== null ? $category['flag'] : '' ?>
                                            <input<?php echo in_array((int) $category['term_id'], $candidatingCatIds, true) ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-candidating[]" value="<?php echo $category['term_id'] ?>">
                                            <i><?php echo $category['name'] ?></i>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <hr/>
            <p>
                <?php echo __('To allow the extension to link the data in the XML file to the corresponding categories, please select for teams, services and platforms a category for each of its entities.', 'cnrs-data-manager') ?>
                <br/>
                <?php echo __('Once selected, you can, in the <b>Options</b> tab, assign each team, services and platforms to the corresponding categorized articles, thus allowing you to link the agents to the different entities in the WordPress application.', 'cnrs-data-manager') ?>
            </p>
            <p>
                <?php echo __('As you make your choices, the <b>generated shortcode</b> must be copied then pasted into the text space of your choice in the articles or pages of your choice, while of course respecting the operating logic of the extension, either in the articles whose categories have been assigned or in the pages whose url will provide the key/value expected by the extension to display the expected rendering.', 'cnrs-data-manager') ?>
            </p>
            <p>
                <?php echo __('The filter and view selectors are not dynamic and just allow you to generate the shortcode to use. On the other hand, the <b>Show view selector</b> selector is dynamic and will be saved in the database.', 'cnrs-data-manager') ?>
            </p>
            <?php cnrs_polylang_installed() ?>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Assign a category to teams', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <?php echo cnrs_get_languages_from_pll($teamsConfig) ?>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?php echo __('Filter', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-filter-teams" value=''><?php echo __('None', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-teams" value='filter="services"'><?php echo __('Services', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-teams" value='filter="platforms"'><?php echo __('Platforms', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?php echo __('Default view', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-view-teams" value=''><?php echo __('List', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-view-teams" value='default="grid"'><?php echo __('Grid', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span class="cnrs-dm-selector-title"><?php echo __('Show view selector', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input <?php echo $settings['teams_view_selector'] === '1' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-teams" value="1"><?php echo __('Yes', 'cnrs-data-manager') ?></label></li>
                                <li><label><input <?php echo $settings['teams_view_selector'] === '0' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-teams" value="0"><?php echo __('No', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="teams"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="teams"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Assign a category to services', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <?php echo cnrs_get_languages_from_pll($servicesConfig) ?>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?php echo __('Filter', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-filter-services" value=''><?php echo __('None', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-services" value='filter="teams"'><?php echo __('Teams', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-services" value='filter="platforms"'><?php echo __('Platforms', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?php echo __('Default view', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-view-services" value=''><?php echo __('List', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-view-services" value='default="grid"'><?php echo __('Grid', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span class="cnrs-dm-selector-title"><?php echo __('Show view selector', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input <?php echo $settings['services_view_selector'] === '1' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-services" value="1"><?php echo __('Yes', 'cnrs-data-manager') ?></label></li>
                                <li><label><input <?php echo $settings['services_view_selector'] === '0' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-services" value="0"><?php echo __('No', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="services"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="services"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php echo __('Assign a category to platforms', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <?php echo cnrs_get_languages_from_pll($platformsConfig) ?>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?php echo __('Filter', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-filter-platforms" value=''><?php echo __('None', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-platforms" value='filter="teams"'><?php echo __('Teams', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-platforms" value='filter="services"'><?php echo __('Services', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?php echo __('Default view', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-view-platforms" value=''><?php echo __('List', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-view-platforms" value='default="grid"'><?php echo __('Grid', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span class="cnrs-dm-selector-title"><?php echo __('Show view selector', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input <?php echo $settings['platforms_view_selector'] === '1' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-platforms" value="1"><?php echo __('Yes', 'cnrs-data-manager') ?></label></li>
                                <li><label><input <?php echo $settings['platforms_view_selector'] === '0' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-platforms" value="0"><?php echo __('No', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="platforms"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?php echo __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="platforms"]</code>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                                </svg>
                            </span>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
            <hr>
            <p><?php echo __('In order to manage the <b>design</b> of the article previews on the main pages of the different categories, please select the <b>display template</b> for each category.', 'cnrs-data-manager') ?></p>
            <p><?php echo __('Project previews are by default displayed with the <b>Card</b> template.', 'cnrs-data-manager') ?></p>
            <?php cnrs_polylang_installed() ?>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <td>
                            <div class="cnrs-dm-filters-allowed-wrapper">
                                <?php foreach ($categories as $row): ?>
                                    <div class="cnrs-dm-filters-allowed-container cnrs-dm-design-container">
                                        <?php foreach ($row as $lang => $category): ?>
                                        <p>
                                            <?php echo $category['flag'] !== null ? $category['flag'] : '' ?>
                                            <i><?php echo $category['name'] ?></i>
                                        </p>
                                        <div class="cnrs-dm-design-wrapper">
                                            <?php foreach ($designsTypes as $designsType => $designTranslation): ?>
                                                <label>
                                                    <input<?php echo cnrs_isDesignSelected($category['term_id'], $designsType, $designs) === true ? ' checked' : '' ?> type="radio" name="cnrs-dm-design[<?php echo $category['term_id'] ?>]" value="<?php echo $designsType ?>">
                                                    <span><?php echo $designTranslation ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
        <p class="submit">
            <input <?php echo $settings['filename'] === null ? 'disabled ' : '' ?>type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Update') ?>">
        </p>
    </form>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
