<?php

use CnrsDataManager\Core\Models\Agents;
use CnrsDataManager\Core\Models\Forms;

$head = [
    __('Identity', 'cnrs-data-manager'),
    __('Created at', 'cnrs-data-manager'),
    __('Mission start', 'cnrs-data-manager'),
    __('Status', 'cnrs-data-manager'),
    __('Versions', 'cnrs-data-manager'),
    __('Form', 'cnrs-data-manager'),
    __('Actions', 'cnrs-data-manager')
];

?>

<?php if ($count === 0): ?>
    <div id="cnrs-dm-mission-form-list-no-result">
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
                <option <?php echo $status === 'ALL' ? 'selected' : '' ?> value="ALL"><?php echo __('All status', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'PENDING' ? 'selected' : '' ?> value="PENDING"><?php echo __('Pending', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'EXCEPTION' ? 'selected' : '' ?> value="EXCEPTION"><?php echo __('Delayed', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'CANCELED' ? 'selected' : '' ?> value="CANCELED"><?php echo __('Abandoned', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'VALIDATED' ? 'selected' : '' ?> value="VALIDATED"><?php echo __('Validated', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-order-1" class="screen-reader-text"><?php echo __('Select mission start date order.', 'cnrs-data-manager') ?></label>
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
                        <span class="first-page button cnrs-dm-mission-form-pagination-btn" data-page="1">
                            <span class="screen-reader-text"><?php echo __('First page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">«</span>
                        </span>
                        <span class="prev-page button cnrs-dm-mission-form-pagination-btn" data-page="<?php echo $previous ?>">
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
                        <span class="next-page button cnrs-dm-mission-form-pagination-btn" data-page="<?php echo $next ?>">
                            <span class="screen-reader-text"><?php echo __('Next page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">›</span>
                        </span>
                        <span class="last-page button cnrs-dm-mission-form-pagination-btn" data-page="<?php echo $pages ?>">
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
                <?php $agent = Agents::getAgentByEmail($item['email'], $agents); ?>
                <td class="name column-name has-row-actions column-primary" data-colname="<?php echo $head[0] ?>">
                    <div class="cnrs-dm-split-column">
                        <span class="cnrs-dm-avatar" style="background-image: url(<?php echo $agent['photo'] ?>)"></span>
                        <?php if ($agent['autorise_pub_photo'] === false): ?>
                            <span title="<?php echo __('No authorization for the distribution of the avatar.', 'cnrs-data-manager') ?>" class="cnrs-dm-autorise-pub-photo"></span>
                        <?php endif; ?>
                        <span class="screen-reader-text"><?php echo __('No description') ?></span>
                        <div class="cnrs-dm-split-row">
                            <span class="cnrs-dm-civility" aria-hidden="true"><?php echo humanizeCivility($agent['civilite']) ?></span>
                            <strong><span class="row-title cnrs-dm-row-title" aria-hidden="true"><?php echo strtoupper($agent['nom']) . ' ' . $agent['prenom'] ?></span></strong>
                        </div>
                    </div>
                    <span class="cnrs-dm-email" aria-hidden="true"><a href="mailto:<?php echo $agent['email_pro'] ?>"><?php echo $agent['email_pro'] ?></a></span>
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <button type="button" class="toggle-row">
                        <span class="screen-reader-text"><?php echo __('Show more details', 'cnrs-data-manager') ?></span>
                    </button>
                </td>
                <td data-colname="<?php echo $head[1] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span aria-hidden="true"><?php echo wp_date('j F Y H:i', strtotime($item['created_at'])); ?></span>
                </td>
                <td data-colname="<?php echo $head[2] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span aria-hidden="true"><?php echo wp_date('j F Y', strtotime($item['mission_start_at'])); ?></span>
                </td>
                <td data-colname="<?php echo $head[3] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <?php if ($item['status'] === 'PENDING'): ?>
                        <span aria-hidden="true" class="cnrs-dm-status-icon cnrs-dm-status-icon-pending">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16">
                                <path d="M32 0C14.3 0 0 14.3 0 32S14.3 64 32 64V75c0 42.4 16.9 83.1 46.9 113.1L146.7 256 78.9 323.9C48.9 353.9 32 394.6 32 437v11c-17.7 0-32 14.3-32 32s14.3 32 32 32H64 320h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V437c0-42.4-16.9-83.1-46.9-113.1L237.3 256l67.9-67.9c30-30 46.9-70.7 46.9-113.1V64c17.7 0 32-14.3 32-32s-14.3-32-32-32H320 64 32zM96 75V64H288V75c0 19-5.6 37.4-16 53H112c-10.3-15.6-16-34-16-53zm16 309c3.5-5.3 7.6-10.3 12.1-14.9L192 301.3l67.9 67.9c4.6 4.6 8.6 9.6 12.1 14.9H112z"/>
                            </svg>
                            <small><?php echo __('Pending', 'cnrs-data-manager') ?>...</small>
                        </span>
                    <?php elseif ($item['status'] === 'VALIDATED'): ?>
                        <span aria-hidden="true" class="cnrs-dm-status-icon cnrs-dm-status-icon-validated">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                            </svg>
                        </span>
                    <?php elseif ($item['status'] === 'EXCEPTION'): ?>
                        <span aria-hidden="true" class="cnrs-dm-status-icon cnrs-dm-status-icon-exception">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
                                <path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zM305 305c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-47 47-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l47 47-47 47c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l47-47 47 47c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-47-47 47-47z"/>
                            </svg>
                        </span>
                    <?php elseif ($item['status'] === 'CANCELED'): ?>
                        <span aria-hidden="true" class="cnrs-dm-status-icon cnrs-dm-status-icon-canceled">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/>
                            </svg>
                        </span>
                    <?php endif; ?>
                </td>
                <td data-colname="<?php echo $head[4] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span aria-hidden="true" class="cnrs-dm-revision-details">
                        <?php echo Forms::getRevisionsCountByFormId($item['id']); ?>
                        <?php $managers = Forms::getRevisionManagers($item['id']); ?>
                        <?php if (!empty($managers)): ?>
                            &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/>
                            </svg>
                        <ol>
                            <?php foreach ($managers as $manager): ?>
                                <li>
                                    <?php if ($manager['sender'] === 'MANAGER'): ?>
                                        <a href="mailto:<?php echo $manager['email'] ?>" target="_blank"><?php echo $manager['name'] ?></a>
                                    <?php else: ?>
                                        <a href="mailto:<?php echo $manager['funder_email'] ?>" target="_blank"><?php echo __('Credit manager') ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php endif; ?>
                    </span>
                </td>
                <td data-colname="<?php echo $head[5] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span class="cnrs-dm-mission-form-list-title" aria-hidden="true" title="<?php echo __('Mission form', 'cnrs-data-manager') ?>-<?php echo str_replace(['-', ' ', ':'], '', $item['created_at']) ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <path d="M64 464l48 0 0 48-48 0c-35.3 0-64-28.7-64-64L0 64C0 28.7 28.7 0 64 0L229.5 0c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3L384 304l-48 0 0-144-80 0c-17.7 0-32-14.3-32-32l0-80L64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/>
                        </svg>
                        <?php echo __('Mission form', 'cnrs-data-manager') ?>-<?php echo str_replace(['-', ' ', ':'], '', $item['created_at']) ?>
                    </span>
                    <div class="cnrs-dm-mission-form-links">
                        <a class="cnrs-dm-mission-form-link" href="/cnrs-umr/mission-form-print?cdm-pdf=<?php echo $item['uuid'] ?>" target="_blank">
                            <svg class="cnrs-dm-mission-form-svg-link cnrs-dm-mission-form-svg-link-print" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                            </svg>
                        </a>
                        <a class="cnrs-dm-mission-form-link" href="/cnrs-umr/mission-form-download?cdm-pdf=<?php echo $item['uuid'] ?>" download="<?php echo __('Mission form', 'cnrs-data-manager') ?>-<?php echo str_replace(['-', ' ', ':'], '', $item['created_at']) ?>.pdf">
                            <svg class="cnrs-dm-mission-form-svg-link cnrs-dm-mission-form-svg-link-download" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/>
                            </svg>
                        </a>
                    </div>
                </td>
                <td data-colname="<?php echo $head[6] ?>">
                    <span class="screen-reader-text"><?php echo __('No description', 'cnrs-data-manager') ?></span>
                    <span class="cnrs-dm-actions-triggers button<?php echo !in_array($item['status'], ['VALIDATED', 'CANCELED'], true) ? ' button-danger' : ' disabled' ?>" data-form="<?php echo $item['id'] ?>" data-action="abandon">
                        <?php echo __('Abandon', 'cnrs-data-manager') ?>
                    </span>
                    <span class="cnrs-dm-actions-triggers button<?php echo $item['status'] === 'EXCEPTION' ? ' button-primary' : ' disabled' ?>" data-form="<?php echo $item['id'] ?>" data-action="validate">
                        <?php echo __('Validate', 'cnrs-data-manager') ?>
                    </span>
                </td>
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
                <option <?php echo $limit === 5 ? 'selected' : '' ?> value="5"><?php echo __('5 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 10 ? 'selected' : '' ?> value="10"><?php echo __('10 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 20 ? 'selected' : '' ?> value="20"><?php echo __('20 items per page', 'cnrs-data-manager') ?></option>
                <option <?php echo $limit === 50 ? 'selected' : '' ?> value="50"><?php echo __('50 items per page', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-status-2" class="screen-reader-text"><?php echo __('Select status.', 'cnrs-data-manager') ?></label>
            <select id="cnrs-data-manager-status-2">
                <option <?php echo $status === 'All' ? 'selected' : '' ?> value="ALL"><?php echo __('All status', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'PENDING' ? 'selected' : '' ?> value="PENDING"><?php echo __('Pending', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'EXCEPTION' ? 'selected' : '' ?> value="EXCEPTION"><?php echo __('Delayed', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'CANCELED' ? 'selected' : '' ?> value="CANCELED"><?php echo __('Abandoned', 'cnrs-data-manager') ?></option>
                <option <?php echo $status === 'VALIDATED' ? 'selected' : '' ?> value="VALIDATED"><?php echo __('Validated', 'cnrs-data-manager') ?></option>
            </select>
            <label for="cnrs-data-manager-order-2" class="screen-reader-text"><?php echo __('Select mission start date order.', 'cnrs-data-manager') ?></label>
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
                        <span class="first-page button cnrs-dm-mission-form-pagination-btn" data-page="1">
                            <span class="screen-reader-text"><?php echo __('First page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">«</span>
                        </span>
                        <span class="prev-page button cnrs-dm-mission-form-pagination-btn" data-page="<?php echo $previous ?>">
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
                        <span class="next-page button cnrs-dm-mission-form-pagination-btn" data-page="<?php echo $next ?>">
                            <span class="screen-reader-text"><?php echo __('Next page', 'cnrs-data-manager') ?></span>
                            <span aria-hidden="true">›</span>
                        </span>
                        <span class="last-page button cnrs-dm-mission-form-pagination-btn" data-age="<?php echo $pages ?>">
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
