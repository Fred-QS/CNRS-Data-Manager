<?php $avatarLogo = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>'; ?>

<div id="cnrs-dm-front-publication-total-container" class="cnrs-dm-front-filters-publications">
    <p id="cnrs-dm-front-publication-total"><?php echo __('Total', 'cnrs-data-manager') ?>: <?php echo $totalCount === $filteredCount ? '<span class="cnrs-dm-front-publication-total-span">' . $totalCount . '</span>' : '<span class="cnrs-dm-front-publication-total-span">' . $filteredCount . '</span> ' . __('filtered on', 'cnrs-data-manager') . ' <span class="cnrs-dm-front-publication-total-span">' . $totalCount . '</span>' ?></p>
</div>
<?php foreach ($publications as $index => $publication): ?>
    <div class="cnrs-dm-front-publication-wrapper" data-index="<?php echo $index ?>">
        <h3 class="cnrs-dm-front-publication-title"><a href="<?php echo $publication['url'] ?>" target="_blank"><?php echo $publication['title'] ?></a></h3>
        <p class="cnrs-dm-front-publication-authors" title="<?php echo implode(', ', $publication['authors']) ?>"><?php echo $avatarLogo ?><?php echo implode(', ' . $avatarLogo, $publication['authors']) ?></p>
        <p class="cnrs-dm-front-publication-reference">(<?php echo cnrsBuildPublicationReference($publication['reference']) ?>)</p>
        <div class="cnrs-dm-front-publication-footer">
            <ul class="cnrs-dm-front-publication-footer-ul">
                <li class="cnrs-dm-front-publication-type"><?php echo $publication['type'] ?></li>
                <li class="cnrs-dm-front-publication-category"><?php echo $publication['category'] !== null ? $publication['category'] : __('Unclassified', 'cnrs-data-manager') ?></li>
            </ul>
            <?php if (!empty($publication['guardianships'])): ?>
                <ul class="cnrs-dm-front-publication-footer-ul">
                    <?php foreach ($publication['guardianships'] as $guardianship): ?>
                        <li class="cnrs-dm-front-publication-guardianship"><?php echo $guardianship ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<div class="cnrs-dm-front-publication-wrapper<?php echo !empty($publications) ? ' hidden' : '' ?>" data-index="empty">
    <p class="cnrs-dm-front-publication-none"><?php echo __('No publication available yet.', 'cnrs-data-manager') ?></p>
</div>
