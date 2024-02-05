<?php

use CnrsDataManager\Core\Models\Settings;

Settings::update();
$settings = Settings::getSettings();
$teamsConfig = getCategoriesConfig('teams', (int) $settings['teams_category']);
$servicesConfig = getCategoriesConfig('services', (int) $settings['services_category']);
$platformsConfig = getCategoriesConfig('platforms', (int) $settings['platforms_category']);

?>

<div class="wrap">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_SETTINGS_ICON, '#5d5d5d') ?>
        <?= __('Settings', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?= __('Here you can update the settings of the CNRS Data Manager extension.', 'cnrs-data-manager') ?>
        <br/>
        <?= __('In order to save your changes, please click on the <b>Update</b> button.', 'cnrs-data-manager') ?>
    </p>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=settings">
        <table class="form-table" role="presentation">

            <tbody>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename"><?= __('XML file name', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p style="position: relative;">
                            <small id="cnrs-dm-filename-error-input"><?= __('The field cannot be empty and has a maximum of 100 characters.') ?></small>
                            <input name="cnrs-dm-filename" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-filename" value="<?= $settings['filename'] ?>" class="regular-text"><i class="cnrs-dm-unit">.xml</i>
                        </p>
                    </td>
                </tr>
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
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename"><?= __('Agents directory', 'cnrs-data-manager') ?></label>
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
                        <label for="cnrs-dm-filename"><?= __('3D Map builder', 'cnrs-data-manager') ?></label>
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
            </tbody>
        </table>
        <p>
            <?= __('To allow the extension to link the data in the XML file to the corresponding categories, please select for teams, services and platforms a category for each of its entities.', 'cnrs-data-manager') ?>
            <br/>
            <?= __('Once selected, you can, in the <b>Options</b> tab, assign each team, services and platforms to the corresponding categorized articles, thus allowing you to link the agents to the different entities in the WordPress application.', 'cnrs-data-manager') ?>
        </p>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-filename"><?= __('Assign a category to teams', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <?= wp_dropdown_categories($teamsConfig) ?>
                    <div class="cnrs-dm-shortcode-filters">
                        <span><?= __('Filter', 'cnrs-data-manager') ?></span>
                        <ul>
                            <li><label><input checked type="radio" name="cnrs-dm-filter-teams" value='[cnrs-data-manager type="teams"]'><?= __('None', 'cnrs-data-manager') ?></label></li>
                            <li><label><input type="radio" name="cnrs-dm-filter-teams" value='[cnrs-data-manager type="teams" filter="services"]'><?= __('Services', 'cnrs-data-manager') ?></label></li>
                            <li><label><input type="radio" name="cnrs-dm-filter-teams" value='[cnrs-data-manager type="teams" filter="platforms"]'><?= __('Platforms', 'cnrs-data-manager') ?></label></li>
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
                    <label for="cnrs-dm-filename"><?= __('Assign a category to services', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <?= wp_dropdown_categories($servicesConfig) ?>
                    <div class="cnrs-dm-shortcode-filters">
                        <span><?= __('Filter', 'cnrs-data-manager') ?></span>
                        <ul>
                            <li><label><input checked type="radio" name="cnrs-dm-filter-services" value='[cnrs-data-manager type="services"]'><?= __('None', 'cnrs-data-manager') ?></label></li>
                            <li><label><input type="radio" name="cnrs-dm-filter-services" value='[cnrs-data-manager type="services" filter="teams"]'><?= __('Teams', 'cnrs-data-manager') ?></label></li>
                            <li><label><input type="radio" name="cnrs-dm-filter-services" value='[cnrs-data-manager type="services" filter="platforms"]'><?= __('Platforms', 'cnrs-data-manager') ?></label></li>
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
                    <label for="cnrs-dm-filename"><?= __('Assign a category to platforms', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <?= wp_dropdown_categories($platformsConfig) ?>
                    <div class="cnrs-dm-shortcode-filters">
                        <span><?= __('Filter', 'cnrs-data-manager') ?></span>
                        <ul>
                            <li><label><input checked type="radio" name="cnrs-dm-filter-platforms" value='[cnrs-data-manager type="platforms"]'><?= __('None', 'cnrs-data-manager') ?></label></li>
                            <li><label><input type="radio" name="cnrs-dm-filter-platforms" value='[cnrs-data-manager type="platforms" filter="teams"]'><?= __('Teams', 'cnrs-data-manager') ?></label></li>
                            <li><label><input type="radio" name="cnrs-dm-filter-platforms" value='[cnrs-data-manager type="platforms" filter="services"]'><?= __('Services', 'cnrs-data-manager') ?></label></li>
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
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>
</div>