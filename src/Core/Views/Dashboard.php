<?php

updateProjectsRelations();

$data = selectCNRSDataProvider();
$rows = $data['provider']['data'];
$pages = $data['provider']['pages'];
$count = $data['provider']['count'];
$current = $data['provider']['current'];
$next = $data['provider']['next'];
$previous = $data['provider']['previous'];
$providerType = $data['type'];

$head = [__('Name', 'cnrs-data-manager'), __('Description', 'cnrs-data-manager')];
if ($providerType === 'agents') {
    $head = [
        __('Identity', 'cnrs-data-manager'),
        __('Status', 'cnrs-data-manager'),
        __('Guardianship', 'cnrs-data-manager'),
        __('Teams', 'cnrs-data-manager'),
        __('Services', 'cnrs-data-manager'),
        __('Platforms', 'cnrs-data-manager'),
    ];
}

$noResultMessage = __('No result found in teams.', 'cnrs-data-manager');
if ($providerType === 'services') {
    $noResultMessage = __('No result found in services.', 'cnrs-data-manager');
} else if( $providerType === 'platforms') {
    $noResultMessage = __('No result found in platforms.', 'cnrs-data-manager');
} else if ($providerType === 'agents') {
    $noResultMessage = __('No result found in agents.', 'cnrs-data-manager');
}

?>

<div class="wrap cnrs-data-manager-page">
    <form method="get" style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_DASHBOARD_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Dashboard', 'cnrs-data-manager'); ?>
        </h1>
        <h3 class="cnrs-dm-tools-h2"><?php echo __('Overview', 'cnrs-data-manager') ?></h3>
        <p id="cnrs-dm-first-text"><?php echo __('In this section, you will find an overview of the information provided by the XML file containing the data updated by the CNRS IS.', 'cnrs-data-manager') ?></p>
        <p id="cnrs-dm-last-text"><?php echo __('Select a data type using the selection field below to browse results for teams, services, platforms or agents.', 'cnrs-data-manager') ?></p>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row" class="cnrs-dm-data-selector-th"><label for="cnrs-data-manager-provider"><?php echo __('Choose a data type', 'cnrs-data-manager') ?></label></th>
                <td class="cnrs-dm-data-selector-td">
                    <select id="cnrs-data-manager-provider">
                        <option <?php echo $providerType === 'teams' ? 'selected' : '' ?> value="teams"><?php echo __('Teams', 'cnrs-data-manager') ?></option>
                        <option <?php echo $providerType === 'services' ? 'selected' : '' ?> value="services"><?php echo __('Services', 'cnrs-data-manager') ?></option>
                        <option <?php echo $providerType === 'platforms' ? 'selected' : '' ?> value="platforms"><?php echo __('Platforms', 'cnrs-data-manager') ?></option>
                        <option <?php echo $providerType === 'agents' ? 'selected' : '' ?> value="agents"><?php echo __('Agents', 'cnrs-data-manager') ?></option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="subsubsub cnrs-data-manager-subsubsub">
            <p class="current" aria-current="page"><?php echo __('Total', 'cnrs-data-manager') ?> <span class="count">(<?php echo $count ?>)</span></p>
        </div>
        <div id="cnrs-dm-search-box-form">
            <div class="search-box">
                <label class="screen-reader-text" for="cnrs-data-manager-search"><?php echo __('Search', 'cnrs-data-manager') ?>:</label>
                <input type="hidden" name="page" value="data-manager">
                <input type="hidden" name="cnrs-data-manager-provider" value="<?php echo $providerType ?>">
                <input type="hidden" name="cnrs-data-manager-limit" value="<?php echo $_GET['cnrs-data-manager-limit'] ?? CNRS_DATA_MANAGER_LIMIT_OFFSET ?>">
                <input type="search" id="cnrs-data-manager-search" name="cnrs-data-manager-search" value="<?php echo $_GET['cnrs-data-manager-search'] ?>">
                <input type="submit" id="cnrs-data-manager-search-submit" class="button" value="<?php echo __('Search', 'cnrs-data-manager') ?>">
            </div>
        </div>
        <?php if ($count === 0): ?>
            <div>
                <p><span class="cnrs-dm-emoji">&#128528;</span> <i><?php echo $noResultMessage ?></i></p>
                <p><?php echo __('Either the data source does not contain any results or you need to modify your search.', 'cnrs-data-manager') ?></p>
            </div>
        <?php else: ?>
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <label for="cnrs-data-manager-limit-1" class="screen-reader-text"><?php echo __('Select items number per page.', 'cnrs-data-manager') ?></label>
                    <select id="cnrs-data-manager-limit-1">
                        <option <?php echo (isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 5) || !isset($_GET['cnrs-data-manager-limit']) ? 'selected' : '' ?> value="5"><?php echo __('5 items per page', 'cnrs-data-manager') ?></option>
                        <option <?php echo isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 10 ? 'selected' : '' ?> value="10"><?php echo __('10 items per page', 'cnrs-data-manager') ?></option>
                        <option <?php echo isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 20 ? 'selected' : '' ?> value="20"><?php echo __('20 items per page', 'cnrs-data-manager') ?></option>
                        <option <?php echo isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 50 ? 'selected' : '' ?> value="50"><?php echo __('50 items per page', 'cnrs-data-manager') ?></option>
                    </select>
                    <input type="submit" id="cnrs-data-manager-limit-action-1" class="button action cnrs-data-manager-limit-action" value="<?php echo __('Apply', 'cnrs-data-manager') ?>">
                </div>
                <div class="tablenav-pages<?php echo $pages < 2 ? ' one-page' : '' ?>">
                    <span class="displaying-num"><?php echo count($rows) . ' ' .  __('elements', 'cnrs-data-manager') ?></span>
                    <span class="pagination-links">
                    <?php if($previous !== null): ?>
                        <a class="first-page button" href="<?php echo sanitizeURIForPagination(1) ?>">
                            <span class="screen-reader-text"><?php echo __('First page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">«</span>
                        </a>
                        <a class="prev-page button" href="<?php echo sanitizeURIForPagination($previous) ?>">
                            <span class="screen-reader-text"><?php echo __('Previous page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">‹</span>
                        </a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <?php endif; ?>
                    <span class="paging-input">
                        <label for="current-page-selector" class="screen-reader-text"><?php echo __('Current page', 'cnrs-data-manager') ?></label>
                        <input class="current-page" id="current-page-selector" type="text" name="cnrs-data-manager-pagi" value="<?php echo $current ?>" size="1" aria-describedby="table-paging">
                        <span class="tablenav-paging-text">
                            <?php echo __('of', 'cnrs-data-manager') ?>
                            <span class="total-pages"><?php echo $pages ?></span>
                        </span>
                    </span>
                    <?php if($next !== null): ?>
                        <a class="next-page button" href="<?php echo sanitizeURIForPagination($next) ?>">
                            <span class="screen-reader-text"><?php echo __('Next page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a class="last-page button" href="<?php echo sanitizeURIForPagination($pages) ?>">
                            <span class="screen-reader-text"><?php echo __('Last page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">»</span>
                        </a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                    <?php endif; ?>
                </span>
                </div>
                <br class="clear">
            </div>
            <table class="wp-list-table widefat fixed striped table-view-list tags">
                <caption class="screen-reader-text"><?php echo __('Table ordered hierarchically.', 'cnrs-data-manager') ?> <?php echo __('Ascending.', 'cnrs-data-manager') ?></caption>
                <thead>
                <tr>
                    <?php foreach ($head as $title):  ?>
                        <?php $class = '';
                        if ($title === __('Identity', 'cnrs-data-manager')) {
                            $class = ' cnrs-dm-avatar-td';
                        } ?>
                        <th scope="col" class="manage-column column-name column-primary sorted asc<?php echo $class ?>" aria-sort="ascending" abbr="<?php echo $title ?>">
                            <p><span><?php echo $title ?></span></p>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>

                <tbody id="the-list" data-wp-lists="list:tag">
                <?php $level = 0; ?>
                <?php foreach ($rows as $item): ?>
                    <tr id="cnrs-item-<?php echo $level ?>" class="level-<?php echo $level ?>">
                        <?php $subLevel = 0; ?>
                        <?php foreach ($item as $key => $value): ?>
                            <?php if(!str_contains($key, '_id') && ((!in_array($key, ['type', 'autorise_pub_photo', 'prenom', 'photo', 'avatar', 'email_pro', 'civilite', 'expertise', 'responsabilite', 'liens_externes', 'activite']) && $providerType === 'agents') || ($key !== 'type' && $providerType !== 'agents'))): ?>
                                <td class="<?php echo $subLevel === 0 ? 'name column-name has-row-actions column-primary' : '' ?> <?php echo $class ?>" data-colname="<?php echo $head[$subLevel] ?>">
                                    <?php if ($key === 'nom' && $providerType === 'agents'): ?>
                                        <div class="cnrs-dm-split-column">
                                            <span class="cnrs-dm-avatar" style="background-image: url(<?php echo $item['photo'] ?>)"></span>
                                            <?php if ($item['autorise_pub_photo'] === false): ?>
                                                <span title="<?php echo __('No authorization for the distribution of the avatar.', 'cnrs-data-manager') ?>" class="cnrs-dm-autorise-pub-photo"></span>
                                            <?php endif; ?>
                                            <span class="screen-reader-text"><?php echo __('No description') ?></span>
                                            <div class="cnrs-dm-split-row">
                                                <span class="cnrs-dm-civility" aria-hidden="true"><?php echo humanizeCivility($value) ?></span>
                                                <strong><span class="row-title cnrs-dm-row-title" aria-hidden="true"><?php echo strtoupper($value) . ' ' . $item['prenom'] ?></span></strong>
                                            </div>
                                        </div>
                                        <span class="cnrs-dm-email" aria-hidden="true"><a href="mailto:<?php echo $item['email_pro'] ?>"><?php echo $item['email_pro'] ?></a></span>
                                    <?php elseif (in_array($key, ['services', 'plateformes', 'equipes'], true)): ?>
                                        <div aria-hidden="true">
                                            <?php if (empty($value)): ?>
                                                <span>--</span>
                                            <?php else: ?>
                                                <ul class="no-margin">
                                                    <?php foreach ($value as $row): ?>
                                                        <li class="cnrs-dm-teams"><?php echo $row['nom'] ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif($key === 'nom'): ?>
                                        <strong><span aria-hidden="true" class="row-title cnrs-dm-row-title"><?php echo $value ?></span></strong>
                                    <?php else: ?>
                                        <span aria-hidden="true"><?php echo $value ?></span>
                                    <?php endif; ?>
                                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                                    <?php if ($subLevel === 0): ?>
                                        <button type="button" class="toggle-row">
                                            <span class="screen-reader-text"><?php echo __('Show more details', 'cnrs-data-manager') ?></span>
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <?php $subLevel++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <?php $level++; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <?php foreach ($head as $title):  ?>
                        <?php $class = '';
                        if ($title === __('Identity', 'cnrs-data-manager')) {
                            $class = ' cnrs-dm-avatar-td';
                        } ?>
                        <th scope="col" class="manage-column column-name column-primary sorted asc<?php echo $class ?>" aria-sort="ascending" abbr="<?php echo $title ?>">
                            <p><span><?php echo $title ?></span></p>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </tfoot>
            </table>
            <div class="tablenav bottom">
                <div class="alignleft actions bulkactions">
                    <label for="cnrs-data-manager-limit-2" class="screen-reader-text"><?php echo __('Select items number per page.', 'cnrs-data-manager') ?></label>
                    <select id="cnrs-data-manager-limit-2">
                        <option <?php echo (isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 5) || !isset($_GET['cnrs-data-manager-limit']) ? 'selected' : '' ?> value="5"><?php echo __('5 items per page', 'cnrs-data-manager') ?></option>
                        <option <?php echo isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 10 ? 'selected' : '' ?> value="10"><?php echo __('10 items per page', 'cnrs-data-manager') ?></option>
                        <option <?php echo isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 20 ? 'selected' : '' ?> value="20"><?php echo __('20 items per page', 'cnrs-data-manager') ?></option>
                        <option <?php echo isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 50 ? 'selected' : '' ?> value="50"><?php echo __('50 items per page', 'cnrs-data-manager') ?></option>
                    </select>
                    <input type="submit" id="cnrs-data-manager-limit-action-2" class="button action cnrs-data-manager-limit-action" value="<?php echo __('Apply') ?>">
                </div>
                <div class="tablenav-pages<?php echo $pages < 2 ? ' one-page' : '' ?>">
                    <span class="displaying-num"><?php echo count($rows) . ' ' .  __('elements') ?></span>
                    <span class="pagination-links">
                    <?php if($previous !== null): ?>
                        <a class="first-page button" href="<?php echo sanitizeURIForPagination(1) ?>">
                            <span class="screen-reader-text"><?php echo __('First page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">«</span>
                        </a>
                        <a class="prev-page button" href="<?php echo sanitizeURIForPagination($previous) ?>">
                            <span class="screen-reader-text"><?php echo __('Previous page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">‹</span>
                        </a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <?php endif; ?>
                    <span class="screen-reader-text"><?php echo __('Current page') ?></span>
                    <span id="table-paging" class="paging-input">
                        <span class="tablenav-paging-text">
                            <?php echo $current ?> <?php echo __('of', 'cnrs-data-manager') ?>
                            <span class="total-pages"><?php echo $pages ?></span>
                        </span>
                    </span>
                    <?php if($next !== null): ?>
                        <a class="next-page button" href="<?php echo sanitizeURIForPagination($next) ?>">
                            <span class="screen-reader-text"><?php echo __('Next page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a class="last-page button" href="<?php echo sanitizeURIForPagination($pages) ?>">
                            <span class="screen-reader-text"><?php echo __('Last page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">»</span>
                        </a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                    <?php endif; ?>
                </span>
                </div>
                <br class="clear">
            </div>
        <?php endif; ?>
    </form>
    <hr/>
    <h3 class="cnrs-dm-tools-h2"><?php echo __('Teams & projects', 'cnrs-data-manager') ?></h3>
    <p>
        <?php echo __('This section allows you to assign projects to the different teams as well as their display priority orders on the pages of the different teams (from 1 to 16 in order of priority).', 'cnrs-data-manager') ?>
        <br/>
        <?php echo __('These selections allow the filter on the projects page to refine the team search.', 'cnrs-data-manager') ?>
    </p>
    <div id="cnrs-dm-search-box-form-project">
        <div class="search-box">
            <label class="screen-reader-text" for="cnrs-data-manager-search-projects"><?php echo __('Search') ?>:</label>
            <input type="search" id="cnrs-data-manager-search-projects">
            <input type="button" id="cnrs-data-manager-search-submit-projects" class="button" value="<?php echo __('Search') ?>">
        </div>
    </div>
    <form method="post">
        <table class="form-table" role="presentation">
            <tbody>
            <?php foreach (getProjects() as $key => $project): ?>
                <tr class="cnrs-dm-projects-row<?php echo ($key + 1) % 2 === 0 ? ' even' : '' ?>">
                    <th scope="row" class="cnrs-dm-data-selector-th cnrs-dm-data-selector-th-top">
                        <input type="hidden" name="cnrs-data-manager-project[]" value="<?php echo $project['id'] ?>">
                        <div class="cnrs-dm-project-item">
                        <span class="cnrs-dm-project-image-tag cnrs-dm-imported-item-image">
                                <?php echo $project['image'] !== ''
                                    ? $project['image']
                                    : '<img src="/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg" alt="' . __('Default image', 'cnrs-data-manager') . '">'
                                ?>
                        </span>
                            <a href="<?php echo $project['url'] ?>" target="_blank" class="cnrs-dm-imported-item-info">
                                <span><?php echo $project['name'] ?></span>
                                <i><?php echo $project['excerpt'] ?></i>
                            </a>
                        </div>
                        <div class="cnrs-dm-projects-expander">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/>
                            </svg>
                        </div>
                    </th>
                    <td>
                        <div class="cnrs-data-manager-project-teams">
                            <?php foreach (getTeams() as $team): ?>
                                <label>
                                    <input <?php echo isTeamSelected($team['id'], $project['teams']) ? 'checked' : '' ?> type="checkbox" value="<?php echo $team['id'] ?>" name="cnrs-data-manager-project-teams-<?php echo $project['id'] ?>[]">
                                    <i><?php echo $team['name'] ?></i>
                                    <select name="cnrs-data-manager-project-order-<?php echo $project['id'] ?>[]">
                                        <?php for ($i = 0; $i <= CNRS_DATA_MANAGER_PROJECTS_DISPLAY_NUMBER; $i++): ?>
                                            <option <?php echo isOrderSelected($i, $team['id'], $project['teams']) ? 'selected' : '' ?> value="<?php echo $i ?>"><?php echo $i === 0 ? __('Hided', 'cnrs-data-manager') : $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit-markers" class="button button-primary" value="<?php echo __('Save') ?>">
        </p>
    </form>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';