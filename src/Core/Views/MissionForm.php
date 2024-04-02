<?php
use CnrsDataManager\Core\Models\Forms;

if (isset($_POST['cnrs-dm-front-mission-form-original']) && strlen($_POST['cnrs-dm-front-mission-form-original']) > 0) {
    Forms::newFilledForm($_POST);
}
?>

<div class="cnrs-dm-mission-form" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <script>
        const missionForm = JSON.parse('<?php echo stripslashes($json) ?>');
    </script>
    <h2 id="cnrs-dm-front-mission-form-title"><?php echo $form['title'] ?></h2>
    <p class="cnrs-dm-front-mission-form-subtitles"><?php echo __('Please fill out the form', 'cnrs-data-manager') ?></p>
    <form method="post" id="cnrs-dm-front-mission-form-wrapper">
        <input type="hidden" value='<?php echo stripslashes($json) ?>' name="cnrs-dm-front-mission-form-original">
        <?php $index = 0; ?>
        <?php foreach ($form['elements'] as $element): ?>
            <?php $data = $element['data'] ?>
            <?php if ($element['type'] === 'checkbox'): ?>
                <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-checkbox-wrapper" data-type="<?php echo $element['type'] ?>">
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
                            <textarea class="cnrs-dm-front-mission-form-opt-comment" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>-opt-comment-<?php echo $checkboxIndex; ?>"></textarea>
                        <?php endif; ?>
                        <?php $checkboxIndex++; ?>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($element['type'] === 'radio'): ?>
                <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="<?php echo $element['type'] ?>">
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
                            <textarea class="cnrs-dm-front-mission-form-opt-comment" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>-opt-comment-<?php echo $radioIndex; ?>"></textarea>
                        <?php endif; ?>
                        <?php $radioIndex++; ?>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($element['type'] === 'input'): ?>
                <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>">
                    <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                        <?php echo $element['label'] ?>
                    </span>
                    <label>
                        <input<?php echo $data['required'] === true ? ' required' : '' ?> type="text" spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                    </label>
                </div>
            <?php elseif ($element['type'] === 'textarea'): ?>
                <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>">
                    <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                        <?php echo $element['label'] ?>
                    </span>
                    <label>
                        <textarea<?php echo $data['required'] === true ? ' required' : '' ?> spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>"></textarea>
                    </label>
                </div>
            <?php elseif ($element['type'] === 'title'): ?>
                <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>">
                    <h3 class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                        <?php echo $element['label'] ?>
                    </h3>
                </div>
            <?php elseif ($element['type'] === 'comment' && $data['value'] !== null && isset($data['value'][0])): ?>
                <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>">
                    <p><?php echo str_replace("\n", '<br/>', $data['value'][0]) ?></p>
                </div>
            <?php elseif ($element['type'] === 'signs'): ?>
                <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>">
                    <h3 class="cnrs-dm-front-mission-form-element-label"><?php echo __('Singing pads', 'cnrs-data-manager') ?></h3>
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
                <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>">
                    <hr/>
                </div>
            <?php endif; ?>
            <?php $index++; ?>
        <?php endforeach; ?>
        <hr/>
        <ul id="cnrs-dm-front-mission-form-errors"></ul>
        <div id="cnrs-dm-front-mission-form-submit-button-container">
            <button type="submit" id="cnrs-dm-front-mission-form-submit-button" class="cnrs-dm-front-btn cnrs-dm-front-btn-save"><?php echo __('Save', 'cnrs-data-manager') ?></button>
        </div>
    </form>
</div>