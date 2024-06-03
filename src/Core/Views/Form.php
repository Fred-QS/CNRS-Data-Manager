<?php

use CnrsDataManager\Core\Models\Forms;

if (isset($_POST['cnrs-dm-admin-email-init']) && strlen($_POST['cnrs-dm-admin-email-init']) > 0) {
    Forms::setAdminEmail($_POST['cnrs-dm-admin-email-init']);
}
if (isset($_POST['cnrs-dm-mission-form']) && strlen($_POST['cnrs-dm-mission-form']) > 0) {
    $missionForm = $_POST['cnrs-dm-mission-form'];
    Forms::setCurrentForm($missionForm);
}
if (isset($_POST['cnrs-dm-mission-form-settings']) && strlen($_POST['cnrs-dm-mission-form-settings']) > 0) {
    if ($_POST['cnrs-dm-manager'] === null) {
        Forms::setConventions([]);
    }
    Forms::setSettings($_POST);
}
if (!empty($_POST['cnrs-dm-manager'])) {
    Forms::setConventions($_POST['cnrs-dm-manager']);
}

$form = Forms::getCurrentForm();
$settings = Forms::getSettings();
$managersList = Forms::getConventions();
$decodedForm = json_decode($form, true);
$formLink = get_site_url() . '/cnrs-umr/mission-form';

?>

<div class="wrap cnrs-data-manager-page" id="cnrs-data-manager-mission-form-page">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_FORM_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Mission form', 'cnrs-data-manager'); ?>
        </h1>
        <p id="cnrs-dm-first-text"><?php echo __('You will be able to design a template for the mission form. The <b>Builder</b> part will allow you to assemble and customize the elements of the form. You will find in <b>List</b> all the forms completed by the agents. And finally the <b>Settings</b> tab will give you access to the configuration of the process governing the life cycle of the form after its completion.', 'cnrs-data-manager') ?></p>
        <?php if (empty($managersList)): ?>
            <p class="cnrs-dm-warning-note"><?php echo __('<b>Warning !</b> As long as at least one manager has not been entered in the <b>Settings</b> tab, the form page will return a <b>404 error</b>.', 'cnrs-data-manager') ?></p>
        <?php endif; ?>
        <?php if (empty($settings->admin_emails)): ?>
            <hr/>
            <p id="cnrs-dm-first-text"><?php echo __('You must provide an <b>administrator email</b> in order to be able to use the form builder in the event that a form needs to be <b>approved by an administrator</b> regarding a too short <b>form submission deadline</b>.', 'cnrs-data-manager') ?></p>
            <form method="post">
                <table class="form-table" role="presentation">
                    <tbody>
                    <tr>
                        <th scope="row">
                            <label for="cnrs-dm-admin-email-init"><?php echo __('Administrator email', 'cnrs-data-manager') ?></label>
                        </th>
                        <td>
                            <p>
                                <input required name="cnrs-dm-admin-email-init" autocomplete="off" spellcheck="false" type="email" id="cnrs-dm-admin-email-init" class="regular-text">
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <input type="submit" id="cnrs-dm-mission-form-submit-init" class="button button-primary" value="<?php echo __('Save', 'cnrs-data-manager') ?>">
            </form>
        <?php else: ?>
            <p id="cnrs-dm-form-link-to-form">
                <?php echo __('Link to form', 'cnrs-data-manager') ?>:
                <a href="<?php echo $formLink ?>" target="_blank"><?php echo $formLink ?></a>
                <span class="cnrs-dm-tool-button" data-link="<?php echo $formLink ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
                        <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z"/>
                    </svg>
                </span>
            </p>
            <br/>
            <div id="cnrs-dm-tabs-container">
                <div class="cnrs-dm-tabs-container<?php echo isActiveTab() ? ' active' : '' ?>" data-tab="builder">
                    <span><?php echo __('Builder', 'cnrs-data-manager') ?></span>
                </div>
                <div class="cnrs-dm-tabs-container<?php echo isActiveTab('list') ? ' active' : '' ?>" data-tab="list">
                    <span><?php echo __('List', 'cnrs-data-manager') ?></span>
                </div>
                <div class="cnrs-dm-tabs-container<?php echo isActiveTab('settings') ? ' active' : '' ?>" data-tab="settings">
                    <span><?php echo __('Settings', 'cnrs-data-manager') ?></span>
                </div>
            </div>
            <div id="cnrs-dm-tab-separator-container">
                <div class="cnrs-dm-tab-separator<?php echo isActiveTab() ? ' active' : '' ?>" data-tab="builder"></div>
                <span></span>
                <div class="cnrs-dm-tab-separator<?php echo isActiveTab('list') ? ' active' : '' ?>" data-tab="list"></div>
                <span></span>
                <div class="cnrs-dm-tab-separator<?php echo isActiveTab('settings') ? ' active' : '' ?>" data-tab="settings"></div>
            </div>
            <div id="cnrs-dm-tab-content-wrapper">
                <div class="cnrs-dm-tab-content<?php echo isActiveTab() ? ' active' : '' ?>" data-content="builder">
                    <div id="cnrs-dm-form-tools" data-error="<?php echo __('An error as occurred.', 'cnrs-data-manager') ?>">
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Input field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to fill one line field', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="input">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Multi choice', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to choose multiple options', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="checkbox">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Single choice', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to choose a single option', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="radio">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Text field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to fill a multiline field', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="textarea">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Numeric field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to fill a numeric field', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="number">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Date & time field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to fill a date & time field', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="datetime">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Date field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to fill a date field', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="date">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Time field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow user to fill a time field', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="time">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Title field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow author to write a static title', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="title">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Comment field', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow author to write a static text', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="comment">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Separator', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow author to insert a separator between elements', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="separator">+</span>
                        </div>
                        <div class="cnrs-dm-form-tool">
                            <h4><?php echo __('Sign pads', 'cnrs-data-manager') ?></h4>
                            <small><?php echo __('Allow author to insert sign pads', 'cnrs-data-manager') ?></small>
                            <span class="cnrs-dm-add-tool" data-tool="signs">+</span>
                        </div>
                        <form method="post" id="cnrs-dm-mission-form-final">
                            <input type="hidden" name="cnrs-dm-mission-form" value='<?php echo $form ?>'>
                            <input type="button" id="cnrs-dm-mission-form-submit" class="button button-primary" value="<?php echo __('Save', 'cnrs-data-manager') ?>">
                        </form>
                    </div>
                    <div id="cnrs-dm-form-structure">
                        <?php $iteration = 0;
                        foreach ($decodedForm['elements'] as $json):
                            ob_start();
                            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/form-tools/tools/' . $json['type'] . '.php');
                            echo ob_get_clean();
                            $iteration++;
                        endforeach; ?>
                    </div>
                    <div id="cnrs-dm-form-preview">
                        <label>
                            <textarea spellcheck="false" name="cnrs-dm-form-title" placeholder="<?php echo __('Enter a title', 'cnrs-data-manager') ?>"><?php echo $decodedForm['title'] ?></textarea>
                        </label>
                        <div id="cnrs-dm-form-preview-container" data-choices="<?php echo __('Add some choices.', 'cnrs-data-manager') ?>" data-pads="<?php echo __('Add some signing pads.', 'cnrs-data-manager') ?>" data-sign="<?php echo __('Sign<br/>here', 'cnrs-data-manager') ?>"></div>
                    </div>
                </div>
                <div class="cnrs-dm-tab-content<?php echo isActiveTab('list') ? ' active' : '' ?>" data-content="list">
                    <div id="cnrs-dm-mission-form-loader-container" class="show">
                        <div id="cnrs-dm-mission-form-loader">
                            <?php include_once(CNRS_DATA_MANAGER_PATH . '/templates/svg/loader.svg'); ?>
                        </div>
                    </div>
                    <form method="post" id="cnrs-dm-mission-form-list-wrapper">
                        <div class="subsubsub cnrs-data-manager-subsubsub">
                            <p id="cnrs-dm-mission-form-total" class="current" aria-current="page"><?php echo __('Total', 'cnrs-data-manager') ?> <span class="count">(0)</span></p>
                        </div>
                        <div id="cnrs-dm-search-box-form">
                            <div class="search-box">
                                <label class="screen-reader-text" for="cnrs-data-manager-mission-search"><?php echo __('Search', 'cnrs-data-manager') ?>:</label>
                                <input type="search" id="cnrs-data-manager-mission-search" name="cnrs-data-manager-mission-search">
                                <input type="button" id="cnrs-data-manager-search-submit" class="button" value="<?php echo __('Search', 'cnrs-data-manager') ?>">
                            </div>
                        </div>
                        <div id="cnrs-dm-mission-form-list-container"></div>
                    </form>
                </div>
                <div class="cnrs-dm-tab-content<?php echo isActiveTab('settings') ? ' active' : '' ?>" data-content="settings">
                    <form method="post">
                        <h3 class="cnrs-dm-tools-h2"><?php echo __('Debug mode', 'cnrs-data-manager') ?></h3>
                        <p><?php echo __('If debugging mode is activated, the email address chosen for the debugging address will be the one that will receive all emails sent by the <b>CNRS Data Manager</b> extension.', 'cnrs-data-manager') ?></p>
                        <input type="hidden" name="cnrs-dm-mission-form-settings" value="settings">
                        <table class="form-table" role="presentation">
                            <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="cnrs-dm-debug-mode"><?php echo __('Active', 'cnrs-data-manager') ?></label>
                                </th>
                                <td>
                                    <p class="cnrs-dm-switch">
                                        <input type="checkbox"<?php echo (int) $settings->debug_mode === 1 ? ' checked' : '' ?> name="cnrs-dm-debug-mode" id="cnrs-dm-debug-mode">
                                        <span class="cnrs-dm-slider"></span>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="cnrs-dm-debug-email"><?php echo __('Debug email', 'cnrs-data-manager') ?></label>
                                </th>
                                <td>
                                    <p>
                                        <input required name="cnrs-dm-debug-email" autocomplete="off" spellcheck="false" type="email" id="cnrs-dm-debug-email" value="<?php echo $settings->debug_email ?>" class="regular-text">
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h3 class="cnrs-dm-tools-h2"><?php echo __('Administration', 'cnrs-data-manager') ?></h3>
                        <table class="form-table" role="presentation">
                            <tbody>
                            <tr>
                                <th scope="row">
                                    <label class="cnrs-dm-row-label">
                                        <?php echo __('Administrators emails', 'cnrs-data-manager') ?>
                                        <span id="cnrs-dm-admin-emails-button" class="cnrs-dm-tool-button" data-action="add-email">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
                                                <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                            </svg>
                                        </span>
                                    </label>
                                </th>
                                <td id="cnrs-dm-admin-emails-wrapper">
                                    <?php foreach ($settings->admin_emails as $key => $email): ?>
                                        <label for="cnrs-dm-admin-email-<?php echo $key ?>"<?php echo $key > 0 ? ' class="cnrs-dm-admin-email-with-trash"' : '' ?>>
                                            <input<?php echo $key === 0 ? ' required' : '' ?> name="cnrs-dm-admin-email[]" autocomplete="off" spellcheck="false" type="email" id="cnrs-dm-admin-email-<?php echo $key ?>" value="<?php echo $email ?>" class="regular-text">
                                            <?php if ($key > 0): ?>
                                                <span class="cnrs-dm-remove-admin-emails-button cnrs-dm-tool-button" data-action="remove-email">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20"><path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/>
                                                    </svg>
                                                </span>
                                            <?php endif; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="cnrs-dm-generic-email"><?php echo __('Generic email', 'cnrs-data-manager') ?></label>
                                </th>
                                <td>
                                    <p>
                                        <input required name="cnrs-dm-generic-email" autocomplete="off" spellcheck="false" type="email" id="cnrs-dm-generic-email" value="<?php echo $settings->generic_email ?>" class="regular-text">
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="cnrs-dm-generic-active"><?php echo __('Allow form validation generic confirmation', 'cnrs-data-manager') ?></label>
                                </th>
                                <td>
                                    <p class="cnrs-dm-switch">
                                        <input type="checkbox"<?php echo (int) $settings->generic_active === 1 ? ' checked' : '' ?> name="cnrs-dm-generic-active" id="cnrs-dm-generic-active">
                                        <span class="cnrs-dm-slider"></span>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="cnrs-dm-days-limit"><?php echo __('Limit in days for a request in France', 'cnrs-data-manager') ?></label>
                                </th>
                                <td>
                                    <p>
                                        <input required name="cnrs-dm-days-limit" autocomplete="off" spellcheck="false" type="number" id="cnrs-dm-days-limit" value="<?php echo $settings->days_limit ?>" min="0" step="1" class="regular-text">
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="cnrs-dm-month-limit"><?php echo __('Limit in days for a request outside France', 'cnrs-data-manager') ?></label>
                                </th>
                                <td>
                                    <p>
                                        <input required name="cnrs-dm-month-limit" autocomplete="off" spellcheck="false" type="number" id="cnrs-dm-month-limit" value="<?php echo $settings->month_limit ?>" min="0" step="1" class="regular-text">
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h3 class="cnrs-dm-tools-h2"><?php echo __('Managers', 'cnrs-data-manager') ?></h3>
                        <p><?php echo __('You can add managers attached to conventions by naming the convention and then adding the email of the main manager as well as that of a secondary manager if the first is not available. The unavailability of the main manager is validated by checking the box next to their email address..', 'cnrs-data-manager') ?></p>
                        <p id="cnrs-dm-add-manager">
                            <?php echo __('Add a convention with its managers', 'cnrs-data-manager') ?>
                            <span class="cnrs-dm-tool-button" data-action="add-manager">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
                                    <path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                </svg>
                            </span>
                        </p>
                        <div id="cnrs-dm-managers-list">
                            <?php foreach ($managersList as $manager): ?>
                                <div class="cnrs-dm-manager-block">
                                    <input type="hidden" name="cnrs-dm-manager[<?php echo $manager['id'] ?>][id]" value="<?php echo $manager['id'] ?>">
                                    <span class="cnrs-dm-tool-button cnrs-dm-manager-delete-button">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20" height="20">
                                            <path d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"></path>
                                        </svg>
                                    </span>
                                    <div>
                                        <label for="cnrs-dm-manager-convention-<?php echo $manager['id'] ?>"><?php echo __('Convention name', 'cnrs-data-manager') ?></label>
                                        <input required type="text" spellcheck="false" id="cnrs-dm-manager-convention-<?php echo $manager['id'] ?>" name="cnrs-dm-manager[<?php echo $manager['id'] ?>][name]" value="<?php echo $manager['name'] ?>">
                                    </div>
                                    <div class="cnrs-dm-manager-wrapper-with-availability">
                                        <label for="cnrs-dm-manager-primary-<?php echo $manager['id'] ?>"><?php echo __('Main email', 'cnrs-data-manager') ?></label>
                                        <div>
                                            <label for="cnrs-dm-manager-available-<?php echo $manager['id'] ?>"><?php echo __('Not available', 'cnrs-data-manager') ?></label>
                                            <input type="checkbox" id="cnrs-dm-manager-available-<?php echo $manager['id'] ?>" name="cnrs-dm-manager[<?php echo $manager['id'] ?>][available]"<?php echo $manager['available'] === '0' ? ' checked' : '' ?>>
                                        </div>
                                        <input required type="email" spellcheck="false" id="cnrs-dm-manager-primary-<?php echo $manager['id'] ?>" name="cnrs-dm-manager[<?php echo $manager['id'] ?>][primary_email]" value="<?php echo $manager['primary_email'] ?>">
                                    </div>
                                    <div>
                                        <label for="cnrs-dm-manager-secondary-<?php echo $manager['id'] ?>"><?php echo __('Fallback email', 'cnrs-data-manager') ?></label>
                                        <input required type="email" spellcheck="false" id="cnrs-dm-manager-secondary-<?php echo $manager['id'] ?>" name="cnrs-dm-manager[<?php echo $manager['id'] ?>][secondary_email]" value="<?php echo $manager['secondary_email'] ?>">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="submit" id="cnrs-dm-mission-form-submit-settings" class="button button-primary" value="<?php echo __('Save', 'cnrs-data-manager') ?>">
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <script>
        const errorMessagesMissionForm = {
            'form-title': '<?php echo __("The form title is required", "cnrs-data-manager") ?>',
            'checkbox': '<?php echo __("The multi choice label is required", "cnrs-data-manager") ?>',
            'checkbox-choices': '<?php echo __("A least one choice is required for multi choice bloc", "cnrs-data-manager") ?>',
            'radio': '<?php echo __("The single choice label is required", "cnrs-data-manager") ?>',
            'radio-choices': '<?php echo __("A least one choice is required for single choice bloc", "cnrs-data-manager") ?>',
            'signs-choices': '<?php echo __("A least one pad is required for signing pads bloc", "cnrs-data-manager") ?>',
            'input': '<?php echo __("The input field label is required", "cnrs-data-manager") ?>',
            'textarea': '<?php echo __("The text field label is required", "cnrs-data-manager") ?>',
            'number': '<?php echo __("The numeric field label is required", "cnrs-data-manager") ?>',
            'datetime': '<?php echo __("The date & time field label is required", "cnrs-data-manager") ?>',
            'date': '<?php echo __("The date field label is required", "cnrs-data-manager") ?>',
            'time': '<?php echo __("The time field label is required", "cnrs-data-manager") ?>'
        };
        let missionForm = <?php echo $form ?>;
    </script>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
