<?php if ($validated === false): ?>

    <div class="cnrs-dm-mission-form">
        <script>
            const missionForm = JSON.parse('<?php echo stripslashes($json) ?>');
        </script>
        <h2 id="cnrs-dm-front-mission-form-title"><?php echo $form['title'] ?></h2>
        <p class="cnrs-dm-front-mission-form-subtitles"><?php echo __('Please fill out the form', 'cnrs-data-manager') ?></p>
        <div id="cnrs-dm-front-mission-user-wrapper">
            <div id="cnrs-dm-front-mission-user-avatar" style="background-image: url(<?php echo $agent['photo'] ?>)"></div>
            <div id="cnrs-dm-front-mission-user-info">
                <p><?php echo ucfirst($agent['prenom']) ?> <?php echo strtoupper($agent['nom']) ?></p>
                <a href="mailto:<?php echo $agent['email_pro'] ?>"><?php echo $agent['email_pro'] ?></a>
            </div>
            <button type="button" id="cnrs-dm-front-mission-user-logout">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V256c0 17.7 14.3 32 32 32s32-14.3 32-32V32zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z"/>
                </svg>
            </button>
        </div>
        <form method="post" id="cnrs-dm-front-mission-form-wrapper" action="<?php echo add_query_arg(NULL, NULL)  ?>">
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

<?php else: ?>

    <div class="cnrs-dm-mission-form">
        <h2 id="cnrs-dm-front-mission-form-title"><?php echo $form['title'] ?></h2>
        <p class="cnrs-dm-front-mission-form-subtitles"><?php echo __('The form has been saved successfully', 'cnrs-data-manager') ?></p>
        <div id="cnrs-dm-front-mission-form-submit-button-container">
            <a href="<?php echo get_home_url(); ?>" id="cnrs-dm-front-mission-form-home-button" class="cnrs-dm-front-btn"><?php echo __('Back to home', 'cnrs-data-manager') ?></a>
        </div>
    </div>

<?php endif; ?>
