<?php
use CnrsDataManager\Core\Models\Forms;

if (isset($_POST['cnrs-dm-mission-form']) && strlen($_POST['cnrs-dm-mission-form']) > 0) {
    $missionForm = $_POST['cnrs-dm-mission-form'];
    Forms::setCurrentForm($missionForm);
}
$form = Forms::getCurrentForm();
$decodedForm = json_decode($form, true);
?>

<div class="wrap cnrs-data-manager-page">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_FORM_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Mission form', 'cnrs-data-manager'); ?>
        </h1>
        <p id="cnrs-dm-first-text"><?php echo __('You will be able to design a template for the mission form. The <b>Builder</b> part will allow you to assemble and customize the elements of the form. You will find in <b>List</b> all the forms completed by the agents. And finally the <b>Settings</b> tab will give you access to the configuration of the process governing the life cycle of the form after its completion.', 'cnrs-data-manager') ?></p>
        <br/>
        <div id="cnrs-dm-tabs-container">
            <div class="cnrs-dm-tabs-container active" data-tab="builder">
                <span><?php echo __('Builder', 'cnrs-data-manager') ?></span>
            </div>
            <div class="cnrs-dm-tabs-container" data-tab="list">
                <span><?php echo __('List', 'cnrs-data-manager') ?></span>
            </div>
            <div class="cnrs-dm-tabs-container" data-tab="settings">
                <span><?php echo __('Settings', 'cnrs-data-manager') ?></span>
            </div>
        </div>
        <div id="cnrs-dm-tab-separator-container">
            <div class="cnrs-dm-tab-separator active" data-tab="builder"></div>
            <span></span>
            <div class="cnrs-dm-tab-separator" data-tab="list"></div>
            <span></span>
            <div class="cnrs-dm-tab-separator" data-tab="settings"></div>
        </div>
        <div id="cnrs-dm-tab-content-wrapper">
            <div class="cnrs-dm-tab-content active" data-content="builder">
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
                        <input type="hidden" name="cnrs-dm-mission-form" value="<?php echo $form ?>">
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
            <div class="cnrs-dm-tab-content" data-content="list">
                List
            </div>
            <div class="cnrs-dm-tab-content" data-content="settings">
                settings
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
            'textarea': '<?php echo __("The text field label is required", "cnrs-data-manager") ?>'
        };
        let missionForm = JSON.parse('<?php echo stripslashes($form) ?>');
    </script>
</div>