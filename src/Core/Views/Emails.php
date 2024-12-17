<?php

use CnrsDataManager\Core\Controllers\Emails;

Emails::initEmailsTemplates();
Emails::saveEmailsTemplates();
$emails = Emails::getEmailsList();
$languages = function_exists('pll_languages_list') ? array_unique(array_merge(['fr'], pll_languages_list())) : ['fr'];

?>

<div class="wrap cnrs-data-manager-page">
    <h1 class="wp-heading-inline title-and-logo">
        <?php echo svgFromBase64(CNRS_DATA_MANAGER_EMAIL_ICON, '#5d5d5d', 22) ?>
        <?php echo __('Emails', 'cnrs-data-manager'); ?>
    </h1>
    <?php cnrs_polylang_installed() ?>
    <p><?php echo __('You can edit emails templates used by the extension\'s workflows. For some emails, shortcodes are available to automate the rendering of certain elements powered by specialized datasets.', 'cnrs-data-manager') ?></p>
    <div id="cnrs-dm-">
        <ul id="cnrs-dm-emails-edition-list">
            <?php foreach ($emails as $email): ?>
                <li class="cnrs-dm-email-type-wrapper">
                    <span class="cnrs-dm-email-type-template"><b><?php echo __('Template', 'cnrs-data-manager') ?>: </b><i><?php echo ucfirst(str_replace('-', ' ', $email['fr']->file)) ?></i></span>
                    <?php foreach ($languages as $language): ?>
                        <?php $em = $email[$language] ?>
                        <div class="cnrs-dm-email-type-container">
                            <div class="cnrs-dm-email-type-header">
                                <h4>
                                    <?php echo $em->flag ?>
                                    <span><?php echo $em->title ?></span>
                                    <a class="cnrs-dm-email-link" href="<?php echo $em->url ?>" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="cnrs-dm-email-link-li-svg">
                                            <path fill="currentColor" d="M64 464c-8.8 0-16-7.2-16-16L48 64c0-8.8 7.2-16 16-16l160 0 0 80c0 17.7 14.3 32 32 32l80 0 0 288c0 8.8-7.2 16-16 16L64 464zM64 0C28.7 0 0 28.7 0 64L0 448c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-293.5c0-17-6.7-33.3-18.7-45.3L274.7 18.7C262.7 6.7 246.5 0 229.5 0L64 0zm97 289c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0L79 303c-9.4 9.4-9.4 24.6 0 33.9l48 48c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-31-31 31-31zM257 255c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l31 31-31 31c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l48-48c9.4-9.4 9.4-24.6 0-33.9l-48-48z"/>
                                        </svg>
                                    </a>
                                </h4>
                                <span class="cnrs-dm-email-type-header-chevron">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path fill="currentColor" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/>
                                    </svg>
                                </span>
                            </div>
                            <form method="POST" class="cnrs-dm-email-type-body hidden">
                                <div class="cnrs-dm-email-type-side">
                                    <input type="hidden" name="cnrs_dm_email_id" value="<?php echo $em->id ?>"/>
                                    <label>
                                        <span><?php echo __('Subject', 'cnrs-data-manager') ?></span>
                                        <input required spellcheck="false" autocomplete="off" type="text" name="cnrs_dm_email_subject" value="<?php echo $em->subject ?>">
                                    </label>
                                    <br>
                                    <label>
                                        <span><?php echo __('Title', 'cnrs-data-manager') ?></span>
                                        <input required spellcheck="false" autocomplete="off" type="text" name="cnrs_dm_email_title" value="<?php echo $em->title ?>">
                                    </label>
                                    <br>
                                    <label>
                                        <span><?php echo __('Title icon', 'cnrs-data-manager') ?></span>
                                        <input spellcheck="false" autocomplete="off" type="text" name="cnrs_dm_email_icon" value="<?php echo $em->title_logo ?>">
                                    </label>
                                </div>
                                <div class="cnrs-dm-email-type-side">
                                    <?php $shortcodes = json_decode($em->shortcodes, true) ?>
                                    <?php if(!empty($shortcodes)): ?>
                                        <div class="cnrs-dm-email-type-shortcodes-container">
                                            <b><?php echo count($shortcodes) > 1 ? __('Shortcodes', 'cnrs-data-manager') : __('Shortcode', 'cnrs-data-manager') ?></b>
                                            <?php foreach($shortcodes as $shortcode): ?>
                                                <span class="cnrs-dm-email-type-shortcode">
                                                    <?php if($shortcode === '{{uuid}}'): ?>
                                                        <i><?php echo __('Link to form', 'cnrs-data-manager') ?>: </i>
                                                    <?php elseif($shortcode === '{{pwd}}'): ?>
                                                        <i><?php echo __('Password', 'cnrs-data-manager') ?>: </i>
                                                    <?php elseif($shortcode === '{{pdf}}'): ?>
                                                        <i><?php echo __('Link to PDF', 'cnrs-data-manager') ?>: </i>
                                                    <?php endif  ?>
                                                    &nbsp;
                                                    <b><?php echo $shortcode ?></b>
                                                    &nbsp;
                                                    <span class="cnrs-dm-email-type-copy-shortcode" data-shortcode="<?php echo $shortcode ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                            <path fill="currentColor" d="M384 336l-192 0c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l140.1 0L400 115.9 400 320c0 8.8-7.2 16-16 16zM192 384l192 0c35.3 0 64-28.7 64-64l0-204.1c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1L192 0c-35.3 0-64 28.7-64 64l0 256c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64L0 448c0 35.3 28.7 64 64 64l192 0c35.3 0 64-28.7 64-64l0-32-48 0 0 32c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256c0-8.8 7.2-16 16-16l32 0 0-48-32 0z"/>
                                                        </svg>
                                                    </span>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <label>
                                        <span><?php echo __('Content', 'cnrs-data-manager') ?></span>
                                        <textarea required id="cnrs-dm-email-type-content-<?php echo $em->file ?>-<?php echo $em->lang ?>" spellcheck="false" autocomplete="off" class="cnrs-dm-email-type-content" name="cnrs_dm_email_content"><?php echo $em->content ?></textarea>
                                    </label>
                                    <br>
                                    <input type="submit" class="button button-primary" value="<?php echo __('Update') ?>">
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
