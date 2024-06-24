<div class="cnrs-dm-front-slider" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <h3 class="cnrs-dm-front-slider-title"><?php echo sprintf(__('%s in pictures', 'cnrs-data-manager'), get_post_meta($project->ID, 'cnrs_project_acronym', true)) ?></h3>
    <?php if (count($images) < 3): ?>
        <div class="cnrs-dm-front-slider-wrapper static" data-count="<?php echo count($images) ?>">
            <?php foreach ($images as $image): ?>
                <a href="<?php echo $image ?>" target="_blank" class="cnrs-dm-front-slider-item" style="background-image: url(<?php echo $image ?>)"></a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="cnrs-dm-front-slider-wrapper owl-carousel" data-count="<?php echo count($images) ?>">
            <?php foreach ($images as $image): ?>
                <a href="<?php echo $image ?>" target="_blank" class="item cnrs-dm-front-slider-item" style="background-image: url(<?php echo $image ?>)"></a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
