<div id="cnrs-dm-email-body-wrapper-revision">
    <h2 id="cnrs-dm-email-title"><?php echo __('Mission form validated', 'cnrs-data-manager') ?></h2>
    <p id="cnrs-dm-email-p1"><?php echo __('Your mission has been validated !', 'cnrs-data-manager') ?></p>
    <p id="cnrs-dm-email-p2"><?php echo __('Find your request in PDF by clicking on the link below.', 'cnrs-data-manager') ?></p>
    <a href="<?php echo get_site_url() ?>/cnrs-umr/mission-form-print?cdm-pdf=<?php echo $uuid ?>" id="cnrs-dm-email-code">PDF</a>
</div>
