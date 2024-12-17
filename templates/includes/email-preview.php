<?php

use CnrsDataManager\Core\Models\Emails;

if (isset($_GET['template'], $_GET['lang']) && in_array($_GET['template'], array_keys(getAllTemplateModel()), true)) {
    $data = Emails::getEmailFromFileAndLang($_GET['template'], $_GET['lang']);
    if ($data !== null) {
        $agentUuid = wp_generate_uuid4();
        $funderUuid = wp_generate_uuid4();
        $revisionUuid = wp_generate_uuid4();
        $validateUuid = wp_generate_uuid4();
        $pwd = '1234567890';
        include CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php';
    } else {
        echo __('Invalid email template or language.', 'cnrs-data-manager');
    }
} else {
    echo __('Invalid email template or language.', 'cnrs-data-manager');
}
