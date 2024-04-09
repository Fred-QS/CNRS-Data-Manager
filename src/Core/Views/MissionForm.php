<?php
use CnrsDataManager\Core\Controllers\Manager;
use CnrsDataManager\Core\Models\Forms;

$cookieName = "w40S4s2sfdSfg02SgfsF";
$cookieValue = "John Doe";

if (isset($_COOKIE['w40S4s2sfdSfg02SgfsF'])) {

    if (isset($_POST['cnrs-dm-front-mission-form-original']) && strlen($_POST['cnrs-dm-front-mission-form-original']) > 0) {
        $uuid = $_POST['cnrs-dm-front-mission-uuid'];
        $jsonForm = Manager::newFilledForm($_POST);
        Forms::recordNewForm($jsonForm, stripslashes($json), 'fred.geffray@gmail.com', $uuid);
    }

    $errors = [
        'simple' => __('must not be empty', 'cnrs-data-manager'),
        'checkbox' => __('must at least have one selection', 'cnrs-data-manager'),
        'radio' => __('must have one selection', 'cnrs-data-manager'),
        'signs' => __('must have been correctly filled out', 'cnrs-data-manager'),
        'option' => __('comment must not be empty', 'cnrs-data-manager'),
    ];
    ?>

    <div class="cnrs-dm-mission-form" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
        <script>
            const missionForm = JSON.parse('<?php echo stripslashes($json) ?>');
        </script>
        <h2 id="cnrs-dm-front-mission-form-title"><?php echo $form['title'] ?></h2>
        <p class="cnrs-dm-front-mission-form-subtitles"><?php echo __('Please fill out the form', 'cnrs-data-manager') ?></p>
        <form method="post" id="cnrs-dm-front-mission-form-wrapper">
            <input type="hidden" value='<?php echo stripslashes($json) ?>' name="cnrs-dm-front-mission-form-original">
            <input type="hidden" value="<?php echo wp_generate_uuid4() ?>" name="cnrs-dm-front-mission-uuid">
            <?php $index = 0; ?>
            <?php foreach ($form['elements'] as $element): ?>
                <?php $data = $element['data'] ?>
                <?php if ($element['type'] === 'checkbox'): ?>
                    <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-checkbox-wrapper" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                            <?php echo $element['label'] ?>
                        </span>
                        <?php $checkboxIndex = 0; ?>
                        <?php foreach ($data['choices'] as $choice): ?>
                            <label class="cnrs-dm-front-checkbox-label" data-option="<?php echo stripos($choice, '-opt-comment') !== false ? 'option' : 'normal' ?>">
                                <input class="checkbox__trigger visuallyHidden" value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="checkbox" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>[]">
                                <span class="checkbox__symbol">
                                    <svg aria-hidden="true" class="icon-checkbox" width="28px" height="28px" viewBox="0 0 28 28" version="1" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 14l8 7L24 7"></path>
                                    </svg>
                                </span>
                                <span class="checkbox__text-wrapper"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                            </label>
                            <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                <textarea spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>-opt-comment-<?php echo $checkboxIndex; ?>" data-type="opt-comment"></textarea>
                            <?php endif; ?>
                            <?php $checkboxIndex++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($element['type'] === 'radio'): ?>
                    <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                            <?php echo $element['label'] ?>
                        </span>
                        <?php $radioIndex = 0; ?>
                        <?php foreach ($data['choices'] as $choice): ?>
                            <label>
                                <input<?php if ($radioIndex === 0): echo ' checked'; endif; ?> value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="radio" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                                <span class="design"></span>
                                <span class="text"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                            </label>
                            <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                <textarea spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>-opt-comment-<?php echo $radioIndex; ?>" data-type="opt-comment"></textarea>
                            <?php endif; ?>
                            <?php $radioIndex++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($element['type'] === 'input'): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                            <?php echo $element['label'] ?>
                        </span>
                        <label>
                            <input<?php echo $data['required'] === true ? ' required' : '' ?> type="text" spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                        </label>
                    </div>
                <?php elseif ($element['type'] === 'textarea'): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                            <?php echo $element['label'] ?>
                        </span>
                        <label>
                            <textarea<?php echo $data['required'] === true ? ' required' : '' ?> spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>"></textarea>
                        </label>
                    </div>
                <?php elseif ($element['type'] === 'title'): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <h3 class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                            <?php echo $element['label'] ?>
                        </h3>
                    </div>
                <?php elseif ($element['type'] === 'comment' && $data['value'] !== null && isset($data['value'][0])): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <p><?php echo str_replace("\n", '<br/>', $data['value'][0]) ?></p>
                    </div>
                <?php elseif ($element['type'] === 'signs'): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="light">
                        <h3 class="cnrs-dm-front-mission-form-element-label" data-error="<?php echo __('Signing pad', 'cnrs-data-manager') ?>"><?php echo __('Signing pads', 'cnrs-data-manager') ?></h3>
                        <?php $padIndex = 0; ?>
                        <?php foreach ($data['choices'] as $choice): ?>
                            <div class="cnrs-dm-front-sign-pad-preview-wrapper">
                                <input type="hidden" required name="cnrs-dm-front-mission-form-element-signs-<?php echo $index; ?>-pad-<?php echo $padIndex; ?>" value="{}">
                                <div class="cnrs-dm-front-sign-pad-preview" id="cnrs-dm-front-sign-pad-preview-<?php echo $index; ?>-pad-<?php echo $padIndex; ?>"></div>
                                <button type="button" class="cnrs-dm-front-sign-pad-button cnrs-dm-front-btn cnrs-dm-front-btn-white" data-labels="<?php echo $choice ?>" data-index="<?php echo $padIndex; ?>" data-iteration="<?php echo $index; ?>" data-sign="<?php echo __('Signature', 'cnrs-data-manager') ?>" data-clear="<?php echo __('Clear', 'cnrs-data-manager') ?>" data-cancel="<?php echo __('Cancel', 'cnrs-data-manager') ?>" data-save="<?php echo __('Save', 'cnrs-data-manager') ?>"><?php echo __('Get signing pad', 'cnrs-data-manager') ?></button>
                            </div>
                            <?php $padIndex++; ?>
                        <?php endforeach; ?>
                    </div>
                <?php elseif ($element['type'] === 'separator'): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                        <hr/>
                    </div>
                <?php endif; ?>
                <?php $index++; ?>
            <?php endforeach; ?>
            <hr/>
            <ul id="cnrs-dm-front-mission-form-errors" data-messages='<?php echo json_encode($errors) ?>'></ul>
            <div id="cnrs-dm-front-mission-form-submit-button-container">
                <button type="button" id="cnrs-dm-front-mission-form-submit-button" class="cnrs-dm-front-btn cnrs-dm-front-btn-save"><?php echo __('Save', 'cnrs-data-manager') ?></button>
            </div>
        </form>
    </div>

<?php } else {
    $agents = json_encode(Manager::defineArrayFromXML()['agents']);
?>

    <div class="cnrs-dm-mission-form" id="identification-form" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
        <script>
            const xmlAgents = JSON.parse(`<?php echo stripslashes($agents) ?>`);
        </script>
        <div id="cnrs-dm-front-mission-form-login-wrapper">
            <form method="post" class="cnrs-dm-front-mission-form-login" data-action="login">
                <h2><?php echo __('Connexion', 'cnrs-data-manager') ?></h2>
                <?php if (isset($_POST['cnrs-dm-front-mission-form-login-action']) && $_POST['cnrs-dm-front-mission-form-login-action'] === 'reset'): ?>
                    <p class="cnrs-dm-front-mission-form-confirm-reset"><?php echo __('An email has been sent to your mail box.', 'cnrs-data-manager') ?></p>
                <?php endif; ?>
                <input type="hidden" name="cnrs-dm-front-mission-form-login-action" value="login">
                <div class="cnrs-dm-front-mission-form-login-container">
                    <label class="cnrs-dm-front-mission-form-login-label">
                        <span><?php echo __('Your email', 'cnrs-data-manager') ?></span>
                        <input spellcheck="false" autocomplete="off" type="email" name="cnrs-dm-front-mission-form-login-email" required>
                    </label>
                    <label class="cnrs-dm-front-mission-form-login-label">
                        <span><?php echo __('Your password', 'cnrs-data-manager') ?></span>
                        <input spellcheck="false" autocomplete="off" type="email" name="cnrs-dm-front-mission-form-login-email" required>
                    </label>
                    <div class="cnrs-dm-front-mission-form-login-email-reset-container">
                        <p id="cnrs-dm-front-mission-form-reset-link"><?php echo __('Reset password', 'cnrs-data-manager') ?></p>
                    </div>
                    <div class="cnrs-dm-front-mission-form-submit-login-container">
                        <button type="submit" class="cnrs-dm-front-btn"><?php echo __('Login', 'cnrs-data-manager') ?></button>
                    </div>
                </div>
            </form>
            <form method="post" class="cnrs-dm-front-mission-form-login hide" data-action="reset" action="#identification-form">
                <h2><?php echo __('Reset password', 'cnrs-data-manager') ?></h2>
                <input type="hidden" name="cnrs-dm-front-mission-form-login-action" value="reset">
                <div class="cnrs-dm-front-mission-form-login-container">
                    <label class="cnrs-dm-front-mission-form-login-label">
                        <span><?php echo __('Your email', 'cnrs-data-manager') ?></span>
                        <input spellcheck="false" autocomplete="off" type="email" name="cnrs-dm-front-mission-form-reset-email" required>
                    </label>
                    <div class="cnrs-dm-front-mission-form-login-email-reset-container">
                        <p id="cnrs-dm-front-mission-form-login-link"><?php echo __('Back to  login', 'cnrs-data-manager') ?></p>
                    </div>
                    <div class="cnrs-dm-front-mission-form-submit-login-container">
                        <button type="submit" class="cnrs-dm-front-btn"><?php echo __('Reset password', 'cnrs-data-manager') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>