<?php

use CnrsDataManager\Core\Models\Tools;

Tools::update();
$relations = Tools::getRelations();
$teams = CNRS_DATA_MANAGER_XML_DATA['teams'];
$services = CNRS_DATA_MANAGER_XML_DATA['services'];
$platforms = CNRS_DATA_MANAGER_XML_DATA['platforms'];
$teamsPosts = getAllPostsFromCategoryId(Tools::getTeamsCategoryId());
$servicesPosts = getAllPostsFromCategoryId(Tools::getServicesCategoryId());
$platformsPosts = getAllPostsFromCategoryId(Tools::getPlatformsCategoryId());
?>

<div class="wrap cnrs-data-manager-page" style="position: relative">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_TOOLS_ICON, '#5d5d5d', 19) ?>
        <?= __('Tools', 'cnrs-data-manager'); ?>
    </h1>
    <?php if (isset($_POST['cnrs-dm-restore']) && $_POST['cnrs-dm-restore'] === 'restore'): ?>
        <p id="cnrs-dm-restore-message"><?= __('The files have been restored.', 'cnrs-data-manager') ?></p>
    <?php endif; ?>
    <p><?= __('The <b>Tools</b> section allows you to assign entities from the XML file to those created in WordPress. The extension will then be able to find the agents belonging to each entity in order to be able to filter their affiliations in the public part of WordPress.', 'cnrs-data-manager') ?></p>
    <p><?= __('You can also decide to restore the initial state of the files provided by the extension listed below used for style, javascript and php template', 'cnrs-data-manager') ?>:</p>
    <b><?= __('Styles templates') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?= __('The <b>CSS</b> file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>CSS</b> filters file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-filters-style.css</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>CSS</b> pagination file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-pagination-style.css</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?= __('Scripts templates') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?= __('The <b>JS</b> file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?= __('Build templates') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?= __('The <b>PHP</b> template file for displaying agents in a list <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-inline.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>PHP</b> template file for the agent card display <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-card.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>PHP</b> template file for the full agent list item <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-list-item.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>PHP</b> template file for the entity title if display mode is sorted <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-sorted-title.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>PHP</b> template file for the agent modal display <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-info.php</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?= __('Icons templates') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?= __('The <b>SVG</b> file for the list view button <b><i>/wp-includes/cnrs-data-manager/svg/list.svg</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>SVG</b> file for the grid view button <b><i>/wp-includes/cnrs-data-manager/svg/grid.svg</i></b>', 'cnrs-data-manager') ?></li>
        <li><?= __('The <b>SVG</b> file for the loader icon <b><i>/wp-includes/cnrs-data-manager/svg/loader.svg</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <p>
        <?= __('These files allow you to customize the rendering of agents in the front part of <b>WordPress</b>.', 'cnrs-data-manager') ?>
        <br/>
        <?= __('Keep in mind that the files must keep <b>the same name and path</b>. Only the content can be modified at your convenience for personalization.', 'cnrs-data-manager') ?>
    </p>
    <form method="post">
        <input type="hidden" name="cnrs-dm-restore" value="restore">
        <input type="submit" name="submit" class="button button-primary" value="<?= __('Restore files', 'cnrs-data-manager') ?>">
    </form>
    <br/>
    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?= __('Assign a category to teams', 'cnrs-data-manager') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?= __('Category', 'cnrs-data-manager') ?>: <b><?= $teamsPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="teams">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $teamsCnt = 0; ?>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-data-manager-tools-teams-post-<?= $teamsCnt ?>"><?= $team['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-teams-xml-<?= $teamsCnt ?>" value="<?= $team['equipe_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-teams-type-<?= $teamsCnt ?>" value="teams">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <?php if (!empty($teamsPosts['data'])): ?>
                            <select id="cnrs-data-manager-tools-teams-post-<?= $teamsCnt ?>" required name="cnrs-data-manager-tools-teams-post-<?= $teamsCnt ?>">
                                <option selected disabled value="0"><?= __('Select a post', 'cnrs-data-manager') ?></option>
                            <?php foreach ($teamsPosts['data'] as $teamsPost): ?>
                                <option <?= isCNRSDataManagerToolsSelected($relations['teams'], $teamsPost['id'], $team['equipe_id']) ? 'selected' : '' ?> value="<?= $teamsPost['id'] ?>"><?= $teamsPost['title'] ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <i class="cnrs-dm-no-article"><?= __('No article available for this category.', 'cnrs-data-manager') ?></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cnrs-dm-tools-desc">
                        <i><?= $team['description'] ?></i>
                    </td>
                </tr>
                <?php $teamsCnt++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($teamsPosts['data'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?= __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?= __('Assign a category to services', 'cnrs-data-manager') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?= __('Category', 'cnrs-data-manager') ?>: <b><?= $servicesPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="services">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $servicesCnt = 0; ?>
            <?php foreach ($services as $service): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-data-manager-tools-services-post-<?= $servicesCnt ?>"><?= $service['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-services-xml-<?= $servicesCnt ?>" value="<?= $service['service_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-services-type-<?= $servicesCnt ?>" value="services">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <?php if (!empty($servicesPosts['data'])): ?>
                            <select id="cnrs-data-manager-tools-services-post-<?= $servicesCnt ?>" required name="cnrs-data-manager-tools-services-post-<?= $servicesCnt ?>">
                                <option selected disabled value="0"><?= __('Select a post', 'cnrs-data-manager') ?></option>
                                <?php foreach ($servicesPosts['data'] as $servicesPost): ?>
                                    <option <?= isCNRSDataManagerToolsSelected($relations['services'], $servicesPost['id'], $service['service_id']) ? 'selected' : '' ?> value="<?= $servicesPost['id'] ?>"><?= $servicesPost['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <i class="cnrs-dm-no-article"><?= __('No article available for this category.', 'cnrs-data-manager') ?></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cnrs-dm-tools-desc">
                        <i><?= $service['description'] ?></i>
                    </td>
                </tr>
                <?php $servicesCnt++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($servicesPosts['data'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?= __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?= __('Assign a category to platforms', 'cnrs-data-manager') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?= __('Category', 'cnrs-data-manager') ?>: <b><?= $platformsPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="platforms">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $platformsCnt = 0; ?>
            <?php foreach ($platforms as $platform): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-data-manager-tools-platforms-post-<?= $platformsCnt ?>"><?= $platform['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-platforms-xml-<?= $platformsCnt ?>" value="<?= $platform['plateforme_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-platforms-type-<?= $platformsCnt ?>" value="platforms">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <?php if (!empty($platformsPosts['data'])): ?>
                            <select id="cnrs-data-manager-tools-platforms-post-<?= $platformsCnt ?>" required name="cnrs-data-manager-tools-platforms-post-<?= $platformsCnt ?>">
                                <option selected disabled value="0"><?= __('Select a post', 'cnrs-data-manager') ?></option>
                                <?php foreach ($platformsPosts['data'] as $platformsPost): ?>
                                    <option <?= isCNRSDataManagerToolsSelected($relations['platforms'], $platformsPost['id'], $platform['plateforme_id']) ? 'selected' : '' ?> value="<?= $platformsPost['id'] ?>"><?= $platformsPost['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <i class="cnrs-dm-no-article"><?= __('No article available for this category.', 'cnrs-data-manager') ?></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cnrs-dm-tools-desc">
                        <i><?= $platform['description'] ?></i>
                    </td>
                </tr>
                <?php $platformsCnt++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($platformsPosts['data'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?= __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';