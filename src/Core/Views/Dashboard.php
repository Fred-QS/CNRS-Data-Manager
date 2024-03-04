<?php

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

<div class="wrap">
    <form method="get" style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?= svgFromBase64(CNRS_DATA_MANAGER_DASHBOARD_ICON, '#5d5d5d', 22) ?>
            <?= __('Dashboard', 'cnrs-data-manager'); ?>
        </h1>
        <h3 class="cnrs-dm-tools-h2"><?= __('Overview', 'cnrs-data-manager') ?></h3>
        <p id="cnrs-dm-first-text"><?= __('In this section, you will find an overview of the information provided by the XML file containing the data updated by the CNRS IS.', 'cnrs-data-manager') ?></p>
        <p id="cnrs-dm-last-text"><?= __('Select a data type using the selection field below to browse results for teams, services, platforms or agents.', 'cnrs-data-manager') ?></p>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row" class="cnrs-dm-data-selector-th"><label for="cnrs-data-manager-provider"><?= __('Choose a data type', 'cnrs-data-manager') ?></label></th>
                <td class="cnrs-dm-data-selector-td">
                    <select id="cnrs-data-manager-provider">
                        <option <?= $providerType === 'teams' ? 'selected' : '' ?> value="teams"><?= __('Teams', 'cnrs-data-manager') ?></option>
                        <option <?= $providerType === 'services' ? 'selected' : '' ?> value="services"><?= __('Services', 'cnrs-data-manager') ?></option>
                        <option <?= $providerType === 'platforms' ? 'selected' : '' ?> value="platforms"><?= __('Platforms', 'cnrs-data-manager') ?></option>
                        <option <?= $providerType === 'agents' ? 'selected' : '' ?> value="agents"><?= __('Agents', 'cnrs-data-manager') ?></option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="subsubsub cnrs-data-manager-subsubsub">
            <p class="current" aria-current="page"><?= __('Total', 'cnrs-data-manager') ?> <span class="count">(<?= $count ?>)</span></p>
        </div>
        <div id="cnrs-dm-search-box-form">
            <div class="search-box">
                <label class="screen-reader-text" for="cnrs-data-manager-search"><?= __('Search', 'cnrs-data-manager') ?>:</label>
                <input type="hidden" name="page" value="data-manager">
                <input type="hidden" name="cnrs-data-manager-provider" value="<?= $providerType ?>">
                <input type="hidden" name="cnrs-data-manager-limit" value="<?= $_GET['cnrs-data-manager-limit'] ?? CNRS_DATA_MANAGER_LIMIT_OFFSET ?>">
                <input type="search" id="cnrs-data-manager-search" name="cnrs-data-manager-search" value="<?= $_GET['cnrs-data-manager-search'] ?>">
                <input type="submit" id="cnrs-data-manager-search-submit" class="button" value="<?= __('Search', 'cnrs-data-manager') ?>">
            </div>
        </div>
        <?php if ($count === 0): ?>
            <div>
                <p><span class="cnrs-dm-emoji">&#128528;</span> <i><?= $noResultMessage ?></i></p>
                <p><?= __('Either the data source does not contain any results or you need to modify your search.', 'cnrs-data-manager') ?></p>
            </div>
        <?php else: ?>
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <label for="cnrs-data-manager-limit-1" class="screen-reader-text"><?= __('Select items number per page.', 'cnrs-data-manager') ?></label>
                    <select id="cnrs-data-manager-limit-1">
                        <option <?= (isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 5) || !isset($_GET['cnrs-data-manager-limit']) ? 'selected' : '' ?> value="5"><?= __('5 items per page', 'cnrs-data-manager') ?></option>
                        <option <?= isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 10 ? 'selected' : '' ?> value="10"><?= __('10 items per page', 'cnrs-data-manager') ?></option>
                        <option <?= isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 20 ? 'selected' : '' ?> value="20"><?= __('20 items per page', 'cnrs-data-manager') ?></option>
                        <option <?= isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 50 ? 'selected' : '' ?> value="50"><?= __('50 items per page', 'cnrs-data-manager') ?></option>
                    </select>
                    <input type="submit" id="cnrs-data-manager-limit-action-1" class="button action cnrs-data-manager-limit-action" value="<?= __('Apply', 'cnrs-data-manager') ?>">
                </div>
                <div class="tablenav-pages<?= $pages < 2 ? ' one-page' : '' ?>">
                    <span class="displaying-num"><?= count($rows) . ' ' .  __('elements', 'cnrs-data-manager') ?></span>
                    <span class="pagination-links">
                    <?php if($previous !== null): ?>
                        <a class="first-page button" href="<?= sanitizeURIForPagination(1) ?>">
                            <span class="screen-reader-text"><?= __('First page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">«</span>
                        </a>
                        <a class="prev-page button" href="<?= sanitizeURIForPagination($previous) ?>">
                            <span class="screen-reader-text"><?= __('Previous page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">‹</span>
                        </a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <?php endif; ?>
                    <span class="paging-input">
                        <label for="current-page-selector" class="screen-reader-text"><?= __('Current page', 'cnrs-data-manager') ?></label>
                        <input class="current-page" id="current-page-selector" type="text" name="cnrs-data-manager-pagi" value="<?= $current ?>" size="1" aria-describedby="table-paging">
                        <span class="tablenav-paging-text">
                            <?= __('of', 'cnrs-data-manager') ?>
                            <span class="total-pages"><?= $pages ?></span>
                        </span>
                    </span>
                    <?php if($next !== null): ?>
                        <a class="next-page button" href="<?= sanitizeURIForPagination($next) ?>">
                            <span class="screen-reader-text"><?= __('Next page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a class="last-page button" href="<?= sanitizeURIForPagination($pages) ?>">
                            <span class="screen-reader-text"><?= __('Last page', 'cnrs-data-manager') ?></span>
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
                <caption class="screen-reader-text"><?= __('Table ordered hierarchically.', 'cnrs-data-manager') ?> <?= __('Ascending.', 'cnrs-data-manager') ?></caption>
                <thead>
                <tr>
                    <?php foreach ($head as $title):  ?>
                        <?php $class = '';
                        if ($title === __('Identity', 'cnrs-data-manager')) {
                            $class = ' cnrs-dm-avatar-td';
                        } ?>
                        <th scope="col" class="manage-column column-name column-primary sorted asc<?= $class ?>" aria-sort="ascending" abbr="<?= $title ?>">
                            <p><span><?= $title ?></span></p>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </thead>

                <tbody id="the-list" data-wp-lists="list:tag">
                <?php $level = 0; ?>
                <?php foreach ($rows as $item): ?>
                    <tr id="cnrs-item-<?= $level ?>" class="level-<?= $level ?>">
                        <?php $subLevel = 0; ?>
                        <?php foreach ($item as $key => $value): ?>
                            <?php if(!str_contains($key, '_id') && ((!in_array($key, ['type', 'autorise_pub_photo', 'prenom', 'photo', 'avatar', 'email_pro', 'civilite', 'expertise', 'responsabilite', 'liens_externes', 'specialite', 'activite']) && $providerType === 'agents') || ($key !== 'type' && $providerType !== 'agents'))): ?>
                                <td class="<?= $subLevel === 0 ? 'name column-name has-row-actions column-primary' : '' ?> <?= $class ?>" data-colname="<?= $head[$subLevel] ?>">
                                    <?php if ($key === 'nom' && $providerType === 'agents'): ?>
                                        <div class="cnrs-dm-split-column">
                                            <span class="cnrs-dm-avatar" style="background-image: url(<?= $item['photo'] ?>)"></span>
                                            <?php if ($item['autorise_pub_photo'] === false): ?>
                                                <span title="<?= __('No authorization for the distribution of the avatar.', 'cnrs-data-manager') ?>" class="cnrs-dm-autorise-pub-photo"></span>
                                            <?php endif; ?>
                                            <span class="screen-reader-text"><?= __('No description') ?></span>
                                            <div class="cnrs-dm-split-row">
                                                <span class="cnrs-dm-civility" aria-hidden="true"><?= humanizeCivility($value) ?></span>
                                                <strong><span class="row-title cnrs-dm-row-title" aria-hidden="true"><?= strtoupper($value) . ' ' . $item['prenom'] ?></span></strong>
                                            </div>
                                        </div>
                                        <span class="cnrs-dm-email" aria-hidden="true"><a href="mailto:<?= $item['email_pro'] ?>"><?= $item['email_pro'] ?></a></span>
                                    <?php elseif (in_array($key, ['services', 'plateformes', 'equipes'], true)): ?>
                                        <div aria-hidden="true">
                                            <?php if (empty($value)): ?>
                                                <span>--</span>
                                            <?php else: ?>
                                                <ul class="no-margin">
                                                    <?php foreach ($value as $row): ?>
                                                        <li class="cnrs-dm-teams"><?= $row['nom'] ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif($key === 'nom'): ?>
                                        <strong><span aria-hidden="true" class="row-title cnrs-dm-row-title"><?= $value ?></span></strong>
                                    <?php else: ?>
                                        <span aria-hidden="true"><?= $value ?></span>
                                    <?php endif; ?>
                                    <span class="screen-reader-text"><?= __('No description', 'cnrs-data-manager') ?></span>
                                    <?php if ($subLevel === 0): ?>
                                        <button type="button" class="toggle-row">
                                            <span class="screen-reader-text"><?= __('Show more details', 'cnrs-data-manager') ?></span>
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
                        <th scope="col" class="manage-column column-name column-primary sorted asc<?= $class ?>" aria-sort="ascending" abbr="<?= $title ?>">
                            <p><span><?= $title ?></span></p>
                        </th>
                    <?php endforeach; ?>
                </tr>
                </tfoot>
            </table>
            <div class="tablenav bottom">
                <div class="alignleft actions bulkactions">
                    <label for="cnrs-data-manager-limit-2" class="screen-reader-text"><?= __('Select items number per page.', 'cnrs-data-manager') ?></label>
                    <select id="cnrs-data-manager-limit-2">
                        <option <?= (isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 5) || !isset($_GET['cnrs-data-manager-limit']) ? 'selected' : '' ?> value="5"><?= __('5 items per page', 'cnrs-data-manager') ?></option>
                        <option <?= isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 10 ? 'selected' : '' ?> value="10"><?= __('10 items per page', 'cnrs-data-manager') ?></option>
                        <option <?= isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 20 ? 'selected' : '' ?> value="20"><?= __('20 items per page', 'cnrs-data-manager') ?></option>
                        <option <?= isset($_GET['cnrs-data-manager-limit']) && (int)$_GET['cnrs-data-manager-limit'] === 50 ? 'selected' : '' ?> value="50"><?= __('50 items per page', 'cnrs-data-manager') ?></option>
                    </select>
                    <input type="submit" id="cnrs-data-manager-limit-action-2" class="button action cnrs-data-manager-limit-action" value="<?= __('Apply') ?>">
                </div>
                <div class="tablenav-pages<?= $pages < 2 ? ' one-page' : '' ?>">
                    <span class="displaying-num"><?= count($rows) . ' ' .  __('elements') ?></span>
                    <span class="pagination-links">
                    <?php if($previous !== null): ?>
                        <a class="first-page button" href="<?= sanitizeURIForPagination(1) ?>">
                            <span class="screen-reader-text"><?= __('First page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">«</span>
                        </a>
                        <a class="prev-page button" href="<?= sanitizeURIForPagination($previous) ?>">
                            <span class="screen-reader-text"><?= __('Previous page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">‹</span>
                        </a>
                    <?php else: ?>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                        <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                    <?php endif; ?>
                    <span class="screen-reader-text"><?= __('Current page') ?></span>
                    <span id="table-paging" class="paging-input">
                        <span class="tablenav-paging-text">
                            <?= $current ?> <?= __('of', 'cnrs-data-manager') ?>
                            <span class="total-pages"><?= $pages ?></span>
                        </span>
                    </span>
                    <?php if($next !== null): ?>
                        <a class="next-page button" href="<?= sanitizeURIForPagination($next) ?>">
                            <span class="screen-reader-text"><?= __('Next page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">›</span>
                        </a>
                        <a class="last-page button" href="<?= sanitizeURIForPagination($pages) ?>">
                            <span class="screen-reader-text"><?= __('Last page', 'cnrs-data-manager') ?></span>
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
    <h3 class="cnrs-dm-tools-h2"><?= __('Teams & projects', 'cnrs-data-manager') ?></h3>
    <p>
        <?= __('This section allows you to assign projects to the different teams as well as their display priority orders on the pages of the different teams (from 1 to 16 in order of priority).', 'cnrs-data-manager') ?>
        <br/>
        <?= __('These selections allow the filter on the projects page to refine the team search.', 'cnrs-data-manager') ?>
    </p>
    <div id="cnrs-dm-search-box-form-project">
        <div class="search-box">
            <label class="screen-reader-text" for="cnrs-data-manager-search-projects"><?= __('Search') ?>:</label>
            <input type="search" id="cnrs-data-manager-search-projects">
            <input type="button" id="cnrs-data-manager-search-submit-projects" class="button" value="<?= __('Search') ?>">
        </div>
    </div>
    <form method="post">
        <table class="form-table" role="presentation">
            <tbody>
            <?php foreach (getProjects() as $project): ?>
                <tr class="cnrs-dm-projects-row">
                    <th scope="row" class="cnrs-dm-data-selector-th cnrs-dm-data-selector-th-top">
                        <input type="hidden" name="cnrs-data-manager-project" value="<?= $project['id'] ?>">
                        <div class="cnrs-dm-project-item">
                        <span class="cnrs-dm-project-image-tag cnrs-dm-imported-item-image">
                                <?= $project['image'] !== ''
                                    ? $project['image']
                                    : '<img src="/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg" alt="' . __('Default image', 'cnrs-data-manager') . '">'
                                ?>
                        </span>
                            <a href="<?= $project['url'] ?>" target="_blank" class="cnrs-dm-imported-item-info">
                                <span><?= $project['name'] ?></span>
                                <i><?= $project['excerpt'] ?></i>
                            </a>
                        </div>
                    </th>
                    <td>
                        <div class="cnrs-data-manager-project-teams">
                            <?php foreach (getTeams() as $team): ?>
                                <label>
                                    <input <?= isTeamSelected($team['id'], $project['teams']) ? 'checked' : '' ?> type="checkbox" value="<?= $team['id'] ?>" name="cnrs-data-manager-project-teams-<?= $project['id'] ?>[]">
                                    <i><?= $team['name'] ?></i>
                                    <select name="cnrs-data-manager-project-order-<?= $project['id'] ?>">
                                        <?php for ($i = 0; $i <= CNRS_DATA_MANAGER_PROJECTS_DISPLAY_NUMBER; $i++): ?>
                                            <option <?= isOrderSelected($i, $team['id'], $project['teams']) ? 'selected' : '' ?> value="<?= $i ?>"><?= $i === 0 ? __('not displayed', 'cnrs-data-manager') : $i ?></option>
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
            <input type="submit" name="submit" id="submit-markers" class="button button-primary" value="<?= __('Save') ?>">
        </p>
    </form>
</div>