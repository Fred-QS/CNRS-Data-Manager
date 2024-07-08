<!-- Start CNRS Data Manager render -->
<?php dump($publications) ?>
<!--<div class="cnrs-dm-front-container" style="display: none" data-shortcode="cnrs-data-manager-shortcode-<?php /*echo $shortCodesCounter */?>">
    <?php /*foreach ($publications as $publication): */?>
        <?php /*if (strlen($publication['introductoryText'] > 0)): */?>
            <div data-uuid="<?php /*echo $publication['uuid'] */?>" class="cnrs-dm-front-publication-wrapper">
                <h3><?php /*echo $publication['name'] */?></h3>
                <?php /*echo !str_starts_with($publication['introductoryText'], '<p>')
                    ? '<p>' . str_replace('<p><a ', '<p class="cnrs-dm-front-publication-link-container"><a ', $publication['introductoryText']) . '</p>'
                    : str_replace('<p><a ', '<p class="cnrs-dm-front-publication-link-container"><a ', $publication['introductoryText']) */?>
            </div>
        <?php /*endif; */?>
    <?php /*endforeach; */?>
</div>-->
<!-- End CNRS Data Manager render -->
