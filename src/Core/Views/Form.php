<?php
use CnrsDataManager\Core\Models\Forms;

if (isset($_POST['cnrs-dm-mission-form']) && strlen($_POST['cnrs-dm-mission-form']) > 0) {
    $missionForm = $_POST['cnrs-dm-mission-form'];
    Forms::setCurrentForm($missionForm);
}
if (isset($_POST['cnrs-dm-mission-form-settings']) && strlen($_POST['cnrs-dm-mission-form-settings']) > 0) {
    Forms::setSettings($_POST);
}
$form = Forms::getCurrentForm();
$settings = Forms::getSettings();
$decodedForm = json_decode($form, true);
?>

<div class="wrap cnrs-data-manager-page" id="cnrs-data-manager-mission-form-page">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_FORM_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Mission form', 'cnrs-data-manager'); ?>
        </h1>
        <p id="cnrs-dm-first-text"><?php echo __('You will be able to design a template for the mission form. The <b>Builder</b> part will allow you to assemble and customize the elements of the form. You will find in <b>List</b> all the forms completed by the agents. And finally the <b>Settings</b> tab will give you access to the configuration of the process governing the life cycle of the form after its completion.', 'cnrs-data-manager') ?></p>
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
                    <h3 class="cnrs-dm-tools-h2"><?php echo __('Workflow', 'cnrs-data-manager') ?></h3>
                    <p><?php echo __('You can configure the flow and process of sending emails, as well as the steps to carry out to finalize the actions to be carried out in order to validate a form.', 'cnrs-data-manager') ?></p>
                    <i>TODO</i>
                    <table class="form-table" role="presentation">

                    </table>
                    <input type="submit" id="cnrs-dm-mission-form-submit-settings" class="button button-primary" value="<?php echo __('Save', 'cnrs-data-manager') ?>">
                </form>
            </div>
        </div>
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