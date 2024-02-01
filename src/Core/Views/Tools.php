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

<div class="wrap">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_TOOLS_ICON, '#5d5d5d', 19) ?>
        <?= __('Tools', 'cnrs-data-manager'); ?>
    </h1>
    <p><?= __('The <b>Tools</b> section allows you to assign entities from the XML file to those created in WordPress. The extension will then be able to find the agents belonging to each entity in order to be able to filter their affiliations in the public part of WordPress.') ?></p>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?= __('Assign the teams') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?= __('Category') ?>: <b><?= $teamsPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="teams">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $teamsCnt = 0; ?>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename"><?= $team['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-teams-xml-<?= $teamsCnt ?>" value="<?= $team['equipe_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-teams-type-<?= $teamsCnt ?>" value="teams">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <select required name="cnrs-data-manager-tools-teams-post-<?= $teamsCnt ?>">
                            <option selected disabled value="0"><?= __('Select a post', 'cnrs-data-manager') ?></option>
                        <?php foreach ($teamsPosts['data'] as $teamsPost): ?>
                            <option value="<?= $teamsPost['id'] ?>"><?= $teamsPost['title'] ?></option>
                        <?php endforeach; ?>
                        </select>
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
        <p class="submit">
            <input type="submit" name="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?= __('Assign the services') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?= __('Category') ?>: <b><?= $servicesPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="services">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $servicesCnt = 0; ?>
            <?php foreach ($services as $service): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename"><?= $service['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-services-xml-<?= $servicesCnt ?>" value="<?= $service['service_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-services-type-<?= $servicesCnt ?>" value="services">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <select required name="cnrs-data-manager-tools-services-post-<?= $servicesCnt ?>">
                            <option selected disabled value="0"><?= __('Select a post', 'cnrs-data-manager') ?></option>
                            <?php foreach ($servicesPosts['data'] as $servicesPost): ?>
                                <option value="<?= $servicesPost['id'] ?>"><?= $servicesPost['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
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
        <p class="submit">
            <input type="submit" name="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>

    <hr/>

    <h3 class="cnrs-dm-tools-h2"><?= __('Assign the platforms') ?></h3>
    <p class="cnrs-data-manager-tools-category"><?= __('Category') ?>: <b><?= $platformsPosts['name'] ?></b></p>
    <form method="post">
        <input type="hidden" name="cnrs-data-manager-tools-type" value="platforms">
        <table class="form-table" role="presentation">
            <tbody>
            <?php $platformsCnt = 0; ?>
            <?php foreach ($platforms as $platform): ?>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename"><?= $platform['nom'] ?></label>
                        <input type="hidden" name="cnrs-data-manager-tools-platforms-xml-<?= $platformsCnt ?>" value="<?= $platform['plateforme_id'] ?>">
                        <input type="hidden" name="cnrs-data-manager-tools-platforms-type-<?= $platformsCnt ?>" value="services">
                    </th>
                    <td rowspan="2" style="vertical-align: top;">
                        <select required name="cnrs-data-manager-tools-platforms-post-<?= $platformsCnt ?>">
                            <option selected disabled value="0"><?= __('Select a post', 'cnrs-data-manager') ?></option>
                            <?php foreach ($platformsPosts['data'] as $platformsPost): ?>
                                <option value="<?= $platformsPost['id'] ?>"><?= $platformsPost['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
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
        <p class="submit">
            <input type="submit" name="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>
</div>