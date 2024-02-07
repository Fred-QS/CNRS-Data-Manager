<?php

use CnrsDataManager\Core\Models\Projects;

Projects::uploadFile();

?>

<div class="wrap" style="position: relative">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_IMPORT_ICON, '#5d5d5d', 26) ?>
        <?= __('Import projects', 'cnrs-data-manager'); ?>
    </h1>

</div>
