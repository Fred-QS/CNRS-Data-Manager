<?php
$head = [
    __('Logo', 'cnrs-data-manager'),
    __('Name', 'cnrs-data-manager'),
    __('Type', 'cnrs-data-manager'),
    __('Actions', 'cnrs-data-manager')
];
$translations = [
    'FUNDER' => __('Funder', 'cnrs-data-manager'),
    'PARTNER' => __('Partner', 'cnrs-data-manager'),
];

?>

<?php if ($count === 0): ?>
    <div id="cnrs-dm-collaborators-list-no-result">
        <p><span class="cnrs-dm-emoji">&#128528;</span> <i><?php echo __('No result found.', 'cnrs-data-manager') ?></i></p>
        <p><?php echo __('Either the data source does not contain any results or you need to modify your search.', 'cnrs-data-manager') ?></p>
    </div>
<?php else: ?>
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="cnrs-data-manager-limit-1" class="screen-reader-text"><?php echo __('Select items number per page.', 'cnrs-data-manager') ?></label>
            <select id="cnrs-data-manager-limit-1">
                <option <?php echo $limit === 5 ? 'selected' : '' ?> value="5"><?php echo __('5 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 10 ? 'selected' : '' ?> value="10"><?php echo __('10 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 20 ? 'selected' : '' ?> value="20"><?php echo __('20 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 50 ? 'selected' : '' ?> value="50"><?php echo __('50 items per page', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-status-1" class="screen-reader-text"><?php echo __('Select status.', 'cnrs-data-manager') ?></label>
            <select id="cnrs-data-manager-status-1">
                <option <?php echo $entityType === 'ALL' ? 'selected' : '' ?> value="ALL"><?php echo __('All collaborators', 'cnrs-data-manager') ?></option>
                <option <?php echo $entityType === 'FUNDER' ? 'selected' : '' ?> value="FUNDER"><?php echo __('Funder', 'cnrs-data-manager') ?></option>
                <option <?php echo $entityType === 'PARTNER' ? 'selected' : '' ?> value="PARTNER"><?php echo __('Partner', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-order-1" class="screen-reader-text"><?php echo __('Select collaborators start date order.', 'cnrs-data-manager') ?></label>
            <select id="cnrs-data-manager-order-1">
                <option <?php echo $orderBy === 'DESC' ? 'selected' : '' ?> value="DESC"><?php echo __('Order by most recent', 'cnrs-data-manager') ?></option>
                <option <?php echo $orderBy === 'ASC' ? 'selected' : '' ?> value="ASC"><?php echo __('Order by oldest', 'cnrs-data-manager') ?></option>
            </select>
            <input type="button" class="button action cnrs-data-manager-limit-action" value="<?php echo __('Apply', 'cnrs-data-manager') ?>">
        </div>
        <div class="tablenav-pages<?php echo $pages < 2 ? ' one-page' : '' ?>">
            <span class="displaying-num"><?php echo count($rows) . ' ' .  __('elements', 'cnrs-data-manager') ?></span>
            <span class="pagination-links">
                <?php if($previous !== null): ?>
                    <span class="first-page button cnrs-dm-collaborators-pagination-btn" data-page="1">
                        <span class="screen-reader-text"><?php echo __('First page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">«</span>
                    </span>
                    <span class="prev-page button cnrs-dm-collaborators-pagination-btn" data-page="<?php echo $previous ?>">
                        <span class="screen-reader-text"><?php echo __('Previous page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">‹</span>
                    </span>
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
                    <span class="next-page button cnrs-dm-collaborators-pagination-btn" data-page="<?php echo $next ?>">
                        <span class="screen-reader-text"><?php echo __('Next page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">›</span>
                    </span>
                    <span class="last-page button cnrs-dm-collaborators-pagination-btn" data-page="<?php echo $pages ?>">
                        <span class="screen-reader-text"><?php echo __('Last page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">»</span>
                    </span>
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
                <th scope="col" class="manage-column column-name column-primary sorted asc" aria-sort="ascending" abbr="<?php echo $title ?>">
                    <p><span><?php echo $title ?></span></p>
                </th>
            <?php endforeach; ?>
        </tr>
        </thead>

        <tbody id="the-list" data-wp-lists="list:tag">
        <?php $level = 0; ?>
        <?php foreach ($rows as $item): ?>
            <tr id="cnrs-item-<?php echo $level ?>" class="level-<?php echo $level ?>">
                <td class="name column-name has-row-actions column-primary" data-colname="<?php echo $head[0] ?>">
                    <div class="cnrs-dm-split-column">
                        <?php if (isset($item['entity_logo'])): ?>
                            <span class="cnrs-dm-collaborator-avatar" style="background-image: url(<?php echo $item['entity_logo'] ?>)"></span>
                        <?php else: ?>
                            <span class="cnrs-dm-collaborator-avatar" style="background-image: url(/wp-content/plugins/cnrs-data-manager/assets/media/default_avatar.png)"></span>
                        <?php endif; ?>
                    </div>
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <button type="button" class="toggle-row">
                        <span class="screen-reader-text"><?php echo __('Show more details', 'cnrs-data-manager') ?></span>
                    </button>
                </td>
                <td data-colname="<?php echo $head[1] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span aria-hidden="true"><?php echo $item['entity_name'] ?></span>
                </td>
                <td data-colname="<?php echo $head[2] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span aria-hidden="true"><?php echo $translations[$item['entity_type']] ?></span>
                </td>
                <td data-colname="<?php echo $head[3] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span class="cnrs-dm-actions-triggers button button-danger" data-collaborator="<?php echo $item['id'] ?>" data-action="edit">
                        <?php echo __('Edit', 'cnrs-data-manager') ?>
                    </span>
                    <span class="cnrs-dm-actions-triggers button button-primary" data-collaborator="<?php echo $item['id'] ?>" data-action="delete">
                        <?php echo __('Delete', 'cnrs-data-manager') ?>
                    </span>
                </td>
            </tr>
            <?php $level++; ?>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <?php foreach ($head as $title):  ?>
                <th scope="col" class="manage-column column-name column-primary sorted asc" aria-sort="ascending" abbr="<?php echo $title ?>">
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
                <option <?php echo $limit === 5 ? 'selected' : '' ?> value="5"><?php echo __('5 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 10 ? 'selected' : '' ?> value="10"><?php echo __('10 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 20 ? 'selected' : '' ?> value="20"><?php echo __('20 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 50 ? 'selected' : '' ?> value="50"><?php echo __('50 items per page', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-status-2" class="screen-reader-text"><?php echo __('Select status.', 'cnrs-data-manager') ?></label>
            <select id="cnrs-data-manager-status-2">
                <option <?php echo $entityType === 'ALL' ? 'selected' : '' ?> value="ALL"><?php echo __('All collaborators', 'cnrs-data-manager') ?></option>
                <option <?php echo $entityType === 'FUNDER' ? 'selected' : '' ?> value="FUNDER"><?php echo __('Funder', 'cnrs-data-manager') ?></option>
                <option <?php echo $entityType === 'PARTNER' ? 'selected' : '' ?> value="PARTNER"><?php echo __('Partner', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-order-2" class="screen-reader-text"><?php echo __('Select collaborators start date order.', 'cnrs-data-manager') ?></label>
            <select id="cnrs-data-manager-order-2">
                <option <?php echo $orderBy === 'DESC' ? 'selected' : '' ?> value="DESC"><?php echo __('Order by most recent', 'cnrs-data-manager') ?></option>
                <option <?php echo $orderBy === 'ASC' ? 'selected' : '' ?> value="ASC"><?php echo __('Order by oldest', 'cnrs-data-manager') ?></option>
            </select>
            <input type="button" class="button action cnrs-data-manager-limit-action" value="<?php echo __('Apply') ?>">
        </div>
        <div class="tablenav-pages<?php echo $pages < 2 ? ' one-page' : '' ?>">
            <span class="displaying-num"><?php echo count($rows) . ' ' .  __('elements') ?></span>
            <span class="pagination-links">
                <?php if($previous !== null): ?>
                    <span class="first-page button cnrs-dm-collaborators-pagination-btn" data-page="1">
                        <span class="screen-reader-text"><?php echo __('First page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">«</span>
                    </span>
                    <span class="prev-page button cnrs-dm-collaborators-pagination-btn" data-page="<?php echo $previous ?>">
                        <span class="screen-reader-text"><?php echo __('Previous page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">‹</span>
                    </span>
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
                    <span class="next-page button cnrs-dm-collaborators-pagination-btn" data-page="<?php echo $next ?>">
                        <span class="screen-reader-text"><?php echo __('Next page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">›</span>
                    </span>
                    <span class="last-page button cnrs-dm-collaborators-pagination-btn" data-age="<?php echo $pages ?>">
                        <span class="screen-reader-text"><?php echo __('Last page', 'cnrs-data-manager') ?></span>
                        <span aria-hidden="true">»</span>
                    </span>
                <?php else: ?>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                    <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                <?php endif; ?>
            </span>
        </div>
        <br class="clear">
    </div>
<?php endif; ?>
