<?php

use CnrsDataManager\Core\Models\Agents;

$head = [
    __('Identity', 'cnrs-data-manager'),
    __('Created at', 'cnrs-data-manager'),
    __('Form', 'cnrs-data-manager')
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
                    <span class="cnrs-dm-mission-form-list-title" aria-hidden="true">
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
