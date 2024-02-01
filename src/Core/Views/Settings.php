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
            </tbody>
        </table>
        <p>
            <?= __('To allow the extension to link the data in the XML file to the corresponding categories, please select for teams, services and platforms a category for each of its entities.', 'cnrs-data-manager') ?>
            <br/>
            <?= __('Once selected, you can, in the <b>Options</b> tab, assign each team, services and platforms to the corresponding categorized articles, thus allowing you to link the agents to the different entities in the WordPress application.', 'cnrs-data-manager') ?></p>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-filename"><?= __('Assign a category to teams', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <?= wp_dropdown_categories($teamsConfig) ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-filename"><?= __('Assign a category to services', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <?= wp_dropdown_categories($servicesConfig) ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-filename"><?= __('Assign a category to platforms', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <?= wp_dropdown_categories($platformsConfig) ?>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>
</div>