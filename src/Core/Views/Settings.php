<?php

use CnrsDataManager\Core\Models\Settings;

Settings::update();
Settings::deployCategoryTemplate();
$settings = Settings::getSettings();
$teamsConfig = getCategoriesConfig('teams', (int) $settings['teams_category']);
$servicesConfig = getCategoriesConfig('services', (int) $settings['services_category']);
$platformsConfig = getCategoriesConfig('platforms', (int) $settings['platforms_category']);

?>

<div class="wrap cnrs-data-manager-page">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_SETTINGS_ICON, '#5d5d5d') ?>
        <?= __('Settings', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?= __('Here you can update the settings of the CNRS Data Manager extension.', 'cnrs-data-manager') ?>
        <br/>
        <?= __('In order to save your changes, please click on the <b>Update</b> button.', 'cnrs-data-manager') ?>
    </p>

    <p>
        <?= __('The <b>CNRS Data Manager</b> extension uses Shortcodes to be able to render the different views. You can use the <b>Filter</b> and <b>Default View</b> options in order to best personalize the renderings according to your integration.', 'cnrs-data-manager') ?>
    </p>
    <?php if ($settings['filename'] === null): ?>
        <p>
            <?= __('Before you can use the extension, you must enter the URL of the <b>Soap API</b> that WordPress must contact to retrieve data from your UMR.', 'cnrs-data-manager') ?>
        </p>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" name="action" value="<?= $settings['filename'] === null ? 'init' : 'update' ?>">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename">
                            <?= __('API endpoint URL', 'cnrs-data-manager') ?>
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
                            <small id="cnrs-dm-filename-error-input"><?= __('The field cannot be empty and has a maximum of 255 characters.') ?></small>
                            <input name="cnrs-dm-filename" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-filename" value="<?= $settings['filename'] ?>" class="regular-text">
                        </p>
                    </td>
                </tr>
                <?php if ($settings['filename'] !== null): ?>
                    <tr>
                        <th scope="row">
                            <label for="cnrs-dm-mode"><?= __('List display mode', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <p>
                                <select name="cnrs-dm-mode" id="cnrs-dm-mode">
                                    <option <?= $settings['mode'] === 'widget' ? 'selected' : '' ?> value="widget"><?= __('Widget', 'cnrs-data-manager') ?></option>
                                    <option <?= $settings['mode'] === 'page' ? 'selected' : '' ?> value="page"><?= __('Page', 'cnrs-data-manager') ?></option>
                                </select>
                            </p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($settings['filename'] !== null): ?>
            <p class="cnrs-dm-shortcode-p<?= $settings['mode'] === 'page' ? '' : ' hide' ?>" id="cnrs-dm-page-option-shortcode">
                <?= __('If <b>Page</b> mode is enabled, please use this shortcode as the value for the button link which will point to the page of the agents concerned by the article and its category.', 'cnrs-data-manager') ?>
                <br/>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="navigate" text="button title" target="<?= __('/url/to/reach', 'cnrs-data-manager') ?>"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <?= __('You can also use the shortcode below that of the <b>navigation</b> to display a dynamic title showing the title <b>Member of the <i>"entity"</i> <i>"entity name"</i></b> in the page where the <b>members list</b> shortcode will be implemented.', 'cnrs-data-manager') ?>
                <br/>
                <br/>
                <b><?= __('Team page title', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="page-title" entity="teams"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <b><?= __('Service page title', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="page-title" entity="services"]</code>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 448 512">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
                <br/>
                <b><?= __('Platform page title', 'cnrs-data-manager') ?></b>
                <span class="cnrs-data-manager-copy-shortcode cnrs-data-manager-copy-shortcode-page-mode">
                    <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                    <code>[cnrs-data-manager type="page-title" entity="platforms"]</code>
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
                            <label><?= __('Agents directory', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <p class="cnrs-dm-shortcode-p">
                                <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="all"]'>
                                    <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
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
                        <label><?= __('3D Map builder', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="map"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
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
                        <label><?= __('Team projects', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="projects"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="projects"]</code>
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
                <?= __('In order to optimize the use of filters and pagination, you can choose to use the custom page kit containing the files <b>category.php</b>, <b>archive.php</b> and <b>project.php</b> provided by the extension. It will be up to you to create your own design in the corresponding CSS and JS files (see the list in the <b>Tools</b> tab).', 'cnrs-data-manager') ?>
                <br/>
                <?= __('To use these templates, please check the <b>Use templates</b> box below. The extension will create the <b>custom pages kit</b> at the root of the activated theme, including filters and pagination.', 'cnrs-data-manager') ?>
            </p>
            <div>
                <label>
                    <input<?= (int) $settings['category_template'] === 1 ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-category-template">
                    <b><?= __('Use templates', 'cnrs-data-manager') ?></b>
                </label>
            </div>
            <p><?= __('Otherwise, you can also implement the filter and/or pagination system <b>manually</b> using the shortcodes below.', 'cnrs-data-manager') ?></p>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label><?= __('Filters', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?= __('To be implemented in category page only.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <b><?= __('Filter modules list', 'cnrs-data-manager') ?></b>
                        <br/>
                        <ul class="cnrs-dm-filter-modules">
                            <li>
                                <label>
                                    <input<?= stripos($settings['filter_modules'], 'per-page') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="per-page"><i><?= __('Posts per page', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input<?= stripos($settings['filter_modules'], 'sub-categories-list') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="sub-categories-list"><i><?= __('Categories selector', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input<?= stripos($settings['filter_modules'], 'by-year') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="by-year"><i><?= __('Filter by years', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input<?= stripos($settings['filter_modules'], 'search-field') !== false ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-filter-module[]" value="search-field"><i><?= __('Search field', 'cnrs-data-manager') ?></i>
                                </label>
                            </li>
                        </ul>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode">
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
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
                        <label><?= __('Pagination', 'cnrs-data-manager') ?></label>
                        <br>
                        <i class="cnrs-data-manager-disclaimer"><?= __('To be implemented in category page only.', 'cnrs-data-manager') ?></i>
                    </th>
                    <td>
                        <div>
                            <label>
                                <input<?= (int) $settings['silent_pagination'] === 1 ? ' checked' : '' ?> type="checkbox" id="cnrs-dm-pagination-ajax-checkbox" data-code='[cnrs-data-manager type="pagination" silent-selector="#my-css-selector"]' name="cnrs-dm-pagination-ajax-checkbox">
                                <b><?= __('Silent paging', 'cnrs-data-manager') ?></b></label>
                            <br/>
                            <i class="cnrs-data-manager-disclaimer"><?= __('If you choose <b>silent pagination</b>, you must add <b>class="cnrs-dm-front-silent-container"</b> attribute to the HTML element containing the posts.', 'cnrs-data-manager') ?></i>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode">
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
                                <code>[cnrs-data-manager type="pagination"]</code>
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
                <?= __('To allow the extension to link the data in the XML file to the corresponding categories, please select for teams, services and platforms a category for each of its entities.', 'cnrs-data-manager') ?>
                <br/>
                <?= __('Once selected, you can, in the <b>Options</b> tab, assign each team, services and platforms to the corresponding categorized articles, thus allowing you to link the agents to the different entities in the WordPress application.', 'cnrs-data-manager') ?>
            </p>
            <p>
                <?= __('As you make your choices, the <b>generated shortcode</b> must be copied then pasted into the text space of your choice in the articles or pages of your choice, while of course respecting the operating logic of the extension, either in the articles whose categories have been assigned or in the pages whose url will provide the key/value expected by the extension to display the expected rendering.', 'cnrs-data-manager') ?>
            </p>
            <p>
                <?= __('The filter and view selectors are not dynamic and just allow you to generate the shortcode to use. On the other hand, the <b>Show view selector</b> selector is dynamic and will be saved in the database.', 'cnrs-data-manager') ?>
            </p>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label><?= __('Assign a category to teams', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <?= wp_dropdown_categories($teamsConfig) ?>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?= __('Filter', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-filter-teams" value=''><?= __('None', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-teams" value='filter="services"'><?= __('Services', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-teams" value='filter="platforms"'><?= __('Platforms', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?= __('Default view', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-view-teams" value=''><?= __('List', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-view-teams" value='default="grid"'><?= __('Grid', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span class="cnrs-dm-selector-title"><?= __('Show view selector', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input <?= $settings['teams_view_selector'] === '1' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-teams" value="1"><?= __('Yes', 'cnrs-data-manager') ?></label></li>
                                <li><label><input <?= $settings['teams_view_selector'] === '0' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-teams" value="0"><?= __('No', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="teams"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
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
                        <label><?= __('Assign a category to services', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <?= wp_dropdown_categories($servicesConfig) ?>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?= __('Filter', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-filter-services" value=''><?= __('None', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-services" value='filter="teams"'><?= __('Teams', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-services" value='filter="platforms"'><?= __('Platforms', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?= __('Default view', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-view-services" value=''><?= __('List', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-view-services" value='default="grid"'><?= __('Grid', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span class="cnrs-dm-selector-title"><?= __('Show view selector', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input <?= $settings['services_view_selector'] === '1' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-services" value="1"><?= __('Yes', 'cnrs-data-manager') ?></label></li>
                                <li><label><input <?= $settings['services_view_selector'] === '0' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-services" value="0"><?= __('No', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="services"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
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
                        <label><?= __('Assign a category to platforms', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <?= wp_dropdown_categories($platformsConfig) ?>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?= __('Filter', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-filter-platforms" value=''><?= __('None', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-platforms" value='filter="teams"'><?= __('Teams', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-filter-platforms" value='filter="services"'><?= __('Services', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span><?= __('Default view', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input checked type="radio" name="cnrs-dm-view-platforms" value=''><?= __('List', 'cnrs-data-manager') ?></label></li>
                                <li><label><input type="radio" name="cnrs-dm-view-platforms" value='default="grid"'><?= __('Grid', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <div class="cnrs-dm-shortcode-filters">
                            <span class="cnrs-dm-selector-title"><?= __('Show view selector', 'cnrs-data-manager') ?></span>
                            <ul>
                                <li><label><input <?= $settings['platforms_view_selector'] === '1' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-platforms" value="1"><?= __('Yes', 'cnrs-data-manager') ?></label></li>
                                <li><label><input <?= $settings['platforms_view_selector'] === '0' ? 'checked' : '' ?> type="radio" name="cnrs-dm-selector-platforms" value="0"><?= __('No', 'cnrs-data-manager') ?></label></li>
                            </ul>
                        </div>
                        <p class="cnrs-dm-shortcode-p">
                            <span class="cnrs-data-manager-copy-shortcode" data-code='[cnrs-data-manager type="platforms"]'>
                                <span class="cnrs-dm-copied-to-clipboard"><?= __('Copied to clipboard', 'cnrs-data-manager') ?></span>
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
        <?php endif; ?>
        <p class="submit">
            <input <?= $settings['filename'] === null ? 'disabled ' : '' ?>type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';