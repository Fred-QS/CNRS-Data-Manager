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
        <?php echo svgFromBase64(CNRS_DATA_MANAGER_TOOLS_ICON, '#5d5d5d', 19) ?>
        <?php echo __('Tools', 'cnrs-data-manager'); ?>
    </h1>
    <?php if (isset($_POST['cnrs-dm-restore']) && $_POST['cnrs-dm-restore'] === 'restore'): ?>
        <p id="cnrs-dm-restore-message"><?php echo __('The files have been restored.', 'cnrs-data-manager') ?></p>
    <?php endif; ?>
    <p><?php echo __('The <b>Tools</b> section allows you to assign entities from the XML file to those created in WordPress. The extension will then be able to find the agents belonging to each entity in order to be able to filter their affiliations in the public part of WordPress.', 'cnrs-data-manager') ?></p>
    <p><?php echo __('You can also decide to restore the initial state of the files provided by the extension listed below used for style, javascript and php template', 'cnrs-data-manager') ?>:</p>
    <b><?php echo __('Styles templates', 'cnrs-data-manager') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?php echo __('The <b>CSS</b> file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>CSS</b> filters file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-filters-style.css</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>CSS</b> pagination file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-pagination-style.css</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?php echo __('Scripts templates', 'cnrs-data-manager') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?php echo __('The <b>JS</b> file <b><i>/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?php echo __('Build templates', 'cnrs-data-manager') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?php echo __('The <b>PHP</b> template file for displaying agents in a list <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-inline.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>PHP</b> template file for the agent card display <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-card.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>PHP</b> template file for the full agent list item <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-list-item.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>PHP</b> template file for the entity title if display mode is sorted <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-sorted-title.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>PHP</b> template file for the agent modal display <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-info.php</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?php echo __('Email templates', 'cnrs-data-manager') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?php echo __('The <b>PHP</b> template file for email header <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-email-header.php</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>PHP</b> template file for email footer <b><i>/wp-includes/cnrs-data-manager/templates/cnrs-data-manager-email-footer.php</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <b><?php echo __('Icons templates', 'cnrs-data-manager') ?></b>
    <ul class="cnrs-dm-files-list">
        <li><?php echo __('The <b>SVG</b> file for the list view button <b><i>/wp-includes/cnrs-data-manager/svg/list.svg</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>SVG</b> file for the grid view button <b><i>/wp-includes/cnrs-data-manager/svg/grid.svg</i></b>', 'cnrs-data-manager') ?></li>
        <li><?php echo __('The <b>SVG</b> file for the loader icon <b><i>/wp-includes/cnrs-data-manager/svg/loader.svg</i></b>', 'cnrs-data-manager') ?></li>
    </ul>
    <br>
    <p>
        <?php echo __('These files allow you to customize the rendering of agents in the front part of <b>WordPress</b>.', 'cnrs-data-manager') ?>
        <br/>
        <?php echo __('Keep in mind that the files must keep <b>the same name and path</b>. Only the content can be modified at your convenience for personalization.', 'cnrs-data-manager') ?>
    </p>
    <form method="post">
        <input type="hidden" name="cnrs-dm-restore" value="restore">
        <input type="submit" name="submit" class="button button-primary" value="<?php echo __('Restore files', 'cnrs-data-manager') ?>">
    </form>
    <br/>
    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?php echo __('Assign a category to teams', 'cnrs-data-manager') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?php echo __('Category', 'cnrs-data-manager') ?>: <b><?php echo $teamsPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="teams">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $teamsCnt = 0; ?>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>"><?php echo $team['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-teams-xml-<?php echo $teamsCnt ?>" value="<?php echo $team['equipe_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-teams-type-<?php echo $teamsCnt ?>" value="teams">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <?php if (!empty($teamsPosts['data'])): ?>
                            <select id="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>" required name="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>">
                                <option selected disabled value="0"><?php echo __('Select a post', 'cnrs-data-manager') ?></option>
                            <?php foreach ($teamsPosts['data'] as $teamsPost): ?>
                                <option <?php echo isCNRSDataManagerToolsSelected($relations['teams'], $teamsPost['id'], $team['equipe_id']) ? 'selected' : '' ?> value="<?php echo $teamsPost['id'] ?>"><?php echo $teamsPost['title'] ?></option>
                            <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <i class="cnrs-dm-no-article"><?php echo __('No article available for this category.', 'cnrs-data-manager') ?></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cnrs-dm-tools-desc">
                        <i><?php echo $team['description'] ?></i>
                    </td>
                </tr>
                <?php $teamsCnt++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($teamsPosts['data'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?php echo __('Assign a category to services', 'cnrs-data-manager') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?php echo __('Category', 'cnrs-data-manager') ?>: <b><?php echo $servicesPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="services">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $servicesCnt = 0; ?>
            <?php foreach ($services as $service): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>"><?php echo $service['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-services-xml-<?php echo $servicesCnt ?>" value="<?php echo $service['service_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-services-type-<?php echo $servicesCnt ?>" value="services">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <?php if (!empty($servicesPosts['data'])): ?>
                            <select id="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>" required name="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>">
                                <option selected disabled value="0"><?php echo __('Select a post', 'cnrs-data-manager') ?></option>
                                <?php foreach ($servicesPosts['data'] as $servicesPost): ?>
                                    <option <?php echo isCNRSDataManagerToolsSelected($relations['services'], $servicesPost['id'], $service['service_id']) ? 'selected' : '' ?> value="<?php echo $servicesPost['id'] ?>"><?php echo $servicesPost['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <i class="cnrs-dm-no-article"><?php echo __('No article available for this category.', 'cnrs-data-manager') ?></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cnrs-dm-tools-desc">
                        <i><?php echo $service['description'] ?></i>
                    </td>
                </tr>
                <?php $servicesCnt++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($servicesPosts['data'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?php echo __('Assign a category to platforms', 'cnrs-data-manager') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?php echo __('Category', 'cnrs-data-manager') ?>: <b><?php echo $platformsPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="platforms">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $platformsCnt = 0; ?>
            <?php foreach ($platforms as $platform): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>"><?php echo $platform['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-platforms-xml-<?php echo $platformsCnt ?>" value="<?php echo $platform['plateforme_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-platforms-type-<?php echo $platformsCnt ?>" value="platforms">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <?php if (!empty($platformsPosts['data'])): ?>
                            <select id="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>" required name="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>">
                                <option selected disabled value="0"><?php echo __('Select a post', 'cnrs-data-manager') ?></option>
                                <?php foreach ($platformsPosts['data'] as $platformsPost): ?>
                                    <option <?php echo isCNRSDataManagerToolsSelected($relations['platforms'], $platformsPost['id'], $platform['plateforme_id']) ? 'selected' : '' ?> value="<?php echo $platformsPost['id'] ?>"><?php echo $platformsPost['title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <i class="cnrs-dm-no-article"><?php echo __('No article available for this category.', 'cnrs-data-manager') ?></i>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="cnrs-dm-tools-desc">
                        <i><?php echo $platform['description'] ?></i>
                    </td>
                </tr>
                <?php $platformsCnt++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (!empty($platformsPosts['data'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';