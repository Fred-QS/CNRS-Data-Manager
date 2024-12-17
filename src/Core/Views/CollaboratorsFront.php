<div class="cnrs-dm-front-collaborators-container" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <?php if(count($funders) > 0): ?>
        <div style="width: <?php echo $width ?>%;" class="cnrs-dm-front-collaborators-wrapper" data-type="funders">
            <h3><?php echo count($funders) > 1 ? __('Funders', 'cnrs-data-manager') : __('Funder', 'cnrs-data-manager') ?></h3>
            <div class="cnrs-dm-front-collaborators-list">
                <?php foreach ($funders as $funder): ?>
                    <div class="cnrs-dm-front-collaborator">
                        <p class="cnrs-dm-front-collaborator-name"><?php echo $funder['entity_name'] ?></p>
                        <?php if ($funder['entity_logo'] !== null): ?>
                            <img class="cnrs-dm-front-collaborator-logo" src="<?php echo wp_get_attachment_url($funder['entity_logo']) ?>" alt="<?php echo __('Funder', 'cnrs-data-manager') ?>: <?php echo $funder['entity_name'] ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if(count($partners) > 0): ?>
        <div style="width: <?php echo $width ?>%;" class="cnrs-dm-front-collaborators-wrapper" data-type="partners">
            <h3><?php echo count($partners) > 1 ? __('Partners', 'cnrs-data-manager') : __('Partner', 'cnrs-data-manager') ?></h3>
            <div class="cnrs-dm-front-collaborators-list">
                <?php foreach ($partners as $partner): ?>
                    <div class="cnrs-dm-front-collaborator">
                        <p class="cnrs-dm-front-collaborator-name"><?php echo $partner['entity_name'] ?></p>
                        <?php if ($partner['entity_logo'] !== null): ?>
                            <img class="cnrs-dm-front-collaborator-logo" src="<?php echo wp_get_attachment_url($partner['entity_logo']) ?>" alt="<?php echo __('Partner', 'cnrs-data-manager') ?>: <?php echo $partner['entity_name'] ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
