<?php

$dataContent = $data->content;

if (isset($agentUuid)) {
    $dataContent = str_replace(
        '{{uuid}}',
        '<a href="' . get_site_url() .'/cnrs-umr/mission-form-revision/agent?r=' . $agentUuid .'" id="cnrs-dm-email-code">' . __('Access the form', 'cnrs-data-manager') .'</a>',
        $dataContent
    );
}

if (isset($funderUuid)) {
    $dataContent = str_replace(
        '{{uuid}}',
        '<a href="' . get_site_url() .'/cnrs-umr/mission-form-revision/funder?r=' . $funderUuid .'" id="cnrs-dm-email-code">' . __('Access the form', 'cnrs-data-manager') .'</a>',
        $dataContent
    );
}

if (isset($revisionUuid)) {
    $dataContent = str_replace(
        '{{uuid}}',
        '<a href="' . get_site_url() .'/cnrs-umr/mission-form-revision/manager?r=' . $revisionUuid .'" id="cnrs-dm-email-code">' . __('Access the form', 'cnrs-data-manager') .'</a>',
        $dataContent
    );
}

if (isset($validateUuid)) {
    $dataContent = str_replace(
        '{{pdf}}',
        '<a href="' . get_site_url() .'/cnrs-umr/mission-form-print?cdm-pdf=' . $validateUuid .'" id="cnrs-dm-email-code">PDF</a>',
        $dataContent
    );
}

if (isset($pwd)) {
    $dataContent = str_replace(
        '{{pwd}}',
        '<span id="cnrs-dm-email-code">' . $pwd . '</span>',
        $dataContent
    );
}

?>

<div id="cnrs-dm-email-body-wrapper-<?php echo $data->file ?>">
    <h2 id="cnrs-dm-email-title">
        <?php echo $data->title ?>
        <?php if($data->title_logo !== null): ?>
            <span id="cnrs-dm-email-title-mark-wrapper">
                <img id="cnrs-dm-email-title-mark" src="<?php echo get_home_url() . $data->title_logo ?>" alt="sub symbol">
            </span>
        <?php endif; ?>
    </h2>
    <div id="cnrs-dm-email-content-wrapper">
        <?php echo $dataContent ?>
    </div>
</div>
