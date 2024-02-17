<?php
$pages = $pagination['pages'];
$rowsCount = $pagination['displayed_items'];
$count = $pagination['count'];
$current = $pagination['current'];
$next = $pagination['next'];
$previous = $pagination['previous'];
$mode = isset($fromModule) ? 'front' : 'all';
?>

<?php if ($count > 0): ?>
    <div class="cnrs-dm-front-pagination-wrapper">
        <div class="cnrs-dm-front-pagination<?= $pages < 2 ? ' one-page' : '' ?>">
            <span class="cnrs-dm-front-pagination-displaying-num"><?= $rowsCount === 1 ? sprintf('%d element on %d', $rowsCount, $count) : sprintf('%d elements on %d', $rowsCount, $count) ?></span>
            <span class="cnrs-dm-front-pagination-pagination-links">
                <?php if($previous !== null): ?>
                    <a class="cnrs-dm-front-pagination-first-page button" href="<?= sanitizeURIForPagination(1, $mode) ?>">
                        <span class="screen-reader-text"><?= __('First page', 'cnrs-data-manager') ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-jumper-svg" viewBox="0 0 512 512">
                            <path d="M459.5 440.6c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4L288 214.3V256v41.7L459.5 440.6zM256 352V256 128 96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4l-192 160C4.2 237.5 0 246.5 0 256s4.2 18.5 11.5 24.6l192 160c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V352z"/>
                        </svg>
                    </a>
                    <a class="cnrs-dm-front-pagination-prev-page button" href="<?= sanitizeURIForPagination($previous, $mode) ?>">
                        <span class="screen-reader-text"><?= __('Previous page', 'cnrs-data-manager') ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-stepper-svg reverse" viewBox="0 0 384 512">
                            <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <span class="cnrs-dm-front-pagination-pages-link-like button disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-jumper-svg" viewBox="0 0 512 512">
                            <path d="M459.5 440.6c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4L288 214.3V256v41.7L459.5 440.6zM256 352V256 128 96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4l-192 160C4.2 237.5 0 246.5 0 256s4.2 18.5 11.5 24.6l192 160c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V352z"/>
                        </svg>
                    </span>
                    <span class="cnrs-dm-front-pagination-pages-link-like button disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-stepper-svg reverse" viewBox="0 0 384 512">
                            <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/>
                        </svg>
                    </span>
                <?php endif; ?>
                <span class="cnrs-dm-front-pagination-paging">
                    <span class="cnrs-dm-front-pagination-paging-text"><?= $current ?>
                        <?= __('of', 'cnrs-data-manager') ?>
                        <span class="cnrs-dm-front-pagination-total-pages"><?= $pages ?></span>
                    </span>
                </span>
                <?php if($next !== null): ?>
                    <a class="cnrs-dm-front-pagination-next-page button" href="<?= sanitizeURIForPagination($next, $mode) ?>">
                        <span class="screen-reader-text"><?= __('Next page', 'cnrs-data-manager') ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-stepper-svg" viewBox="0 0 384 512">
                            <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/>
                        </svg>
                    </a>
                    <a class="cnrs-dm-front-pagination-last-page button" href="<?= sanitizeURIForPagination($pages, $mode) ?>">
                        <span class="screen-reader-text"><?= __('Last page', 'cnrs-data-manager') ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-jumper-svg" viewBox="0 0 512 512">
                            <path d="M52.5 440.6c-9.5 7.9-22.8 9.7-34.1 4.4S0 428.4 0 416V96C0 83.6 7.2 72.3 18.4 67s24.5-3.6 34.1 4.4L224 214.3V256v41.7L52.5 440.6zM256 352V256 128 96c0-12.4 7.2-23.7 18.4-29s24.5-3.6 34.1 4.4l192 160c7.3 6.1 11.5 15.1 11.5 24.6s-4.2 18.5-11.5 24.6l-192 160c-9.5 7.9-22.8 9.7-34.1 4.4s-18.4-16.6-18.4-29V352z"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <span class="cnrs-dm-front-pagination-pages-link-like button disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-stepper-svg" viewBox="0 0 384 512">
                            <path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/>
                        </svg>
                    </span>
                    <span class="cnrs-dm-front-pagination-pages-link-like button disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="cnrs-dm-front-jumper-svg" viewBox="0 0 512 512">
                            <path d="M52.5 440.6c-9.5 7.9-22.8 9.7-34.1 4.4S0 428.4 0 416V96C0 83.6 7.2 72.3 18.4 67s24.5-3.6 34.1 4.4L224 214.3V256v41.7L52.5 440.6zM256 352V256 128 96c0-12.4 7.2-23.7 18.4-29s24.5-3.6 34.1 4.4l192 160c7.3 6.1 11.5 15.1 11.5 24.6s-4.2 18.5-11.5 24.6l-192 160c-9.5 7.9-22.8 9.7-34.1 4.4s-18.4-16.6-18.4-29V352z"/>
                        </svg>
                    </span>
                <?php endif; ?>
            </span>
        </div>
    </div>
<?php endif; ?>
