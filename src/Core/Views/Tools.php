<?php

use CnrsDataManager\Core\Models\Tools;

Tools::update();
$relations = Tools::getRelations();
$teams = CNRS_DATA_MANAGER_XML_DATA['teams'];
$services = CNRS_DATA_MANAGER_XML_DATA['services'];
$platforms = CNRS_DATA_MANAGER_XML_DATA['platforms'];
$teamsPosts = getAllPostsFromCategoryId('teams');
$servicesPosts = getAllPostsFromCategoryId('services');
$platformsPosts = getAllPostsFromCategoryId('platforms');
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
    <b><?php echo __('Theme templates', 'cnrs-data-manager') ?></b>
    <?php $path = '/wp-content' . explode('wp-content', get_stylesheet_directory())[1] ?>
    <ul class="cnrs-dm-files-list">
        <li><?php echo sprintf(__('The <b>PHP</b> template file for archive posts (filtering project category) <b><i>%s/archive.php</i></b>', 'cnrs-data-manager'), $path) ?></li>
        <li><?php echo sprintf(__('The <b>PHP</b> template file for the category page <b><i>%s/project.php</i></b>', 'cnrs-data-manager'), $path) ?></li>
        <li><?php echo sprintf(__('The <b>JS</b> script file for the active theme <b><i>%s/cnrs-script.js</i></b>', 'cnrs-data-manager'), $path) ?></li>
    </ul>
    <i><?php echo __('Add this code for embedding the <b>cnrs-script.js</b> file to the <b>functions.php</b> in the active theme folder') ?></i>
    <pre id="cnrs-dm-pre-enqueue"><?php echo highlightText("function cnrs_script_enqueue() {
    wp_enqueue_script( 'custom-scripts', get_stylesheet_directory_uri() . '/cnrs-script.js' );
}
add_action( 'wp_enqueue_scripts', 'cnrs_script_enqueue');", 'php'); ?></pre>
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

    <?php cnrs_polylang_installed() ?>
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
                        <?php if (!empty($teamsPosts['data']['fr'])): ?>
                            <?php foreach ($teamsPosts['data'] as $lang => $teamsPost): ?>
                                <?php if ($teamsPosts['pll'] === true): ?>
                                    <div class="cnrs-dm-pll-select-wrapper">
                                        <?php echo $teamsPosts['data'][$lang][0]['flag'] ?>
                                        <select id="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>" required name="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>[<?php echo $lang ?>]">
                                            <option selected disabled value="0"><?php echo __('Select a team', 'cnrs-data-manager') ?></option>
                                            <?php foreach ($teamsPosts['data'][$lang] as $post): ?>
                                                <option <?php echo isCNRSDataManagerToolsSelected($relations['teams'], $post['id'], $team['equipe_id']) ? 'selected' : '' ?> value="<?php echo $post['id'] ?>"><?php echo $post['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <select id="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>" required name="cnrs-data-manager-tools-teams-post-<?php echo $teamsCnt ?>[<?php echo $lang ?>]">
                                        <option selected disabled value="0"><?php echo __('Select a team', 'cnrs-data-manager') ?></option>
                                        <?php foreach ($teamsPosts['data'][$lang] as $post): ?>
                                            <option <?php echo isCNRSDataManagerToolsSelected($relations['teams'], $post['id'], $team['equipe_id']) ? 'selected' : '' ?> value="<?php echo $post['id'] ?>"><?php echo $post['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endforeach; ?>
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
        <?php if (!empty($teamsPosts['data']['fr'])): ?>
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
                        <?php if (!empty($servicesPosts['data']['fr'])): ?>
                            <?php foreach ($servicesPosts['data'] as $lang => $servicesPost): ?>
                                <?php if ($servicesPosts['pll'] === true): ?>
                                    <div class="cnrs-dm-pll-select-wrapper">
                                        <?php echo $servicesPosts['data'][$lang][0]['flag'] ?>
                                        <select id="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>" required name="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>[<?php echo $lang ?>]">
                                            <option selected disabled value="0"><?php echo __('Select a service', 'cnrs-data-manager') ?></option>
                                            <?php foreach ($servicesPosts['data'][$lang] as $post): ?>
                                                <option <?php echo isCNRSDataManagerToolsSelected($relations['services'], $post['id'], $service['service_id']) ? 'selected' : '' ?> value="<?php echo $post['id'] ?>"><?php echo $post['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <select id="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>" required name="cnrs-data-manager-tools-services-post-<?php echo $servicesCnt ?>[<?php echo $lang ?>]">
                                        <option selected disabled value="0"><?php echo __('Select a service', 'cnrs-data-manager') ?></option>
                                        <?php foreach ($servicesPosts['data'][$lang] as $post): ?>
                                            <option <?php echo isCNRSDataManagerToolsSelected($relations['services'], $post['id'], $service['service_id']) ? 'selected' : '' ?> value="<?php echo $post['id'] ?>"><?php echo $post['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endforeach; ?>
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
        <?php if (!empty($servicesPosts['data']['fr'])): ?>
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
                        <?php if (!empty($platformsPosts['data']['fr'])): ?>
                            <?php foreach ($platformsPosts['data'] as $lang => $platformsPost): ?>
                                <?php if ($platformsPosts['pll'] === true): ?>
                                    <div class="cnrs-dm-pll-select-wrapper">
                                        <?php echo $platformsPosts['data'][$lang][0]['flag'] ?>
                                        <select id="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>" required name="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>[<?php echo $lang ?>]">
                                            <option selected disabled value="0"><?php echo __('Select a platform', 'cnrs-data-manager') ?></option>
                                            <?php foreach ($platformsPosts['data'][$lang] as $post): ?>
                                                <option <?php echo isCNRSDataManagerToolsSelected($relations['platforms'], $post['id'], $platform['plateforme_id']) ? 'selected' : '' ?> value="<?php echo $post['id'] ?>"><?php echo $post['title'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <select id="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>" required name="cnrs-data-manager-tools-platforms-post-<?php echo $platformsCnt ?>[<?php echo $lang ?>]">
                                        <option selected disabled value="0"><?php echo __('Select a platform', 'cnrs-data-manager') ?></option>
                                        <?php foreach ($platformsPosts['data'][$lang] as $post): ?>
                                            <option <?php echo isCNRSDataManagerToolsSelected($relations['platforms'], $post['id'], $platform['plateforme_id']) ? 'selected' : '' ?> value="<?php echo $post['id'] ?>"><?php echo $post['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            <?php endforeach; ?>
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
        <?php if (!empty($platformsPosts['data']['fr'])): ?>
            <p class="submit">
                <input type="submit" name="submit" class="button button-primary" value="<?php echo __('Update', 'cnrs-data-manager') ?>">
            </p>
        <?php endif; ?>
    </form>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
