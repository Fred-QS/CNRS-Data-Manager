<?php if ($validated === false): ?>

    <div class="cnrs-dm-mission-form">
        <script>
            const missionForm = <?php echo $json ?>;
            const daysLimit = <?php echo $days_limit ?>;
            const monthLimit = <?php echo $month_limit ?>;
            const daysLimitAlert = "<?php echo sprintf(__('<b>Warning !</b> The mission start date is less <b>than %d days</b>. Your request will be rejected if deemed not urgent.', 'cnrs-data-manager'), $days_limit) ?>";
            const monthLimitAlert = "<?php echo sprintf(__('<b>Warning !</b> The mission start date is less <b>than %d days</b>. Your request will be rejected if deemed not urgent.', 'cnrs-data-manager'), $month_limit) ?>";
            const isInternational = missionForm.international;
        </script>
        <?php if ($type === 'revision-funder'): ?>
            <h2 id="cnrs-dm-front-mission-form-title">
                <?php echo __('Credit manager approval', 'cnrs-data-manager') ?>
            </h2>
            <p class="cnrs-dm-front-mission-form-subtitles"><?php echo __('Please validate or refuse the request', 'cnrs-data-manager') ?></p>
        <?php else: ?>
            <h2 id="cnrs-dm-front-mission-form-title">
                <?php if ($form['revisions'] < 1): echo __('Form check', 'cnrs-data-manager'); else: echo __('Revision n°', 'cnrs-data-manager') . $form['revisions']; endif; ?>
            </h2>
            <p class="cnrs-dm-front-mission-form-subtitles"><?php echo $type === 'revision-manager' ? __('Please control the form', 'cnrs-data-manager') : __('Please correct the fields with comments', 'cnrs-data-manager') ?></p>
        <?php endif; ?>
        <div id="cnrs-dm-front-mission-user-wrapper">
            <div id="cnrs-dm-front-mission-user-avatar" style="background-image: url(<?php echo $agent['photo'] ?>)"></div>
            <div id="cnrs-dm-front-mission-user-info">
                <p><?php echo ucfirst($agent['prenom']) ?> <?php echo strtoupper($agent['nom']) ?></p>
                <a href="mailto:<?php echo $agent['email_pro'] ?>"><?php echo $agent['email_pro'] ?></a>
            </div>
        </div>
        <?php if ($type === 'revision-agent'): ?>
            <div id="cnrs-dm-front-mission-manager-wrapper">
                <h3><?php echo __('Manager who revised this version', 'cnrs-data-manager') ?></h3>
                <div id="cnrs-dm-front-mission-manager-avatar"></div>
                <div id="cnrs-dm-front-mission-manager-info">
                    <p><?php echo ucfirst($data->manager_name) ?></p>
                    <a href="mailto:<?php echo $data->manager_email ?>"><?php echo $data->manager_email ?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($type === 'revision-manager'): ?>
            <p id="cnrs-dm-front-mission-observation-guide"><?php echo sprintf(__('Please check the form.<br><b>"%s"</b> buttons are located under each type of field so that you can display a text field and then enter an observation.<br>Once the form has been revised, click on <b>"%s"</b> to return the form to the agent if observations have been made, or to validation if the form has no observations.', 'cnrs-data-manager'), __('Add an observation', 'cnrs-data-manager'), __('Save', 'cnrs-data-manager')) ?></p>
        <?php endif; ?>
        <form method="post" id="cnrs-dm-front-mission-form-wrapper" action="<?php echo add_query_arg(NULL, NULL)  ?>">
            <p id="cnrs-dm-front-mission-intl"><?php echo $form['international'] === true ? __('Foreign mission', 'cnrs-data-manager') : __('Mission in France', 'cnrs-data-manager') ?> <small class="cnrs-dm-front-revision-fees-info">(<i><?php echo (int) $data->has_fees === 1 ? __('With fees', 'cnrs-data-manager') : __('No charge', 'cnrs-data-manager') ?></i>)</small></p>

            <?php if ($type === 'revision-manager'): ?>

                <input type="hidden" name="cnrs-dm-front-manager-revision" value="ok">
                <div class="cnrs-dm-front-mission-form-element" data-state="light">
                    <span class="cnrs-dm-front-mission-form-element-label required"><?php echo __('Manager name', 'cnrs-data-manager') ?></span>
                    <label>
                        <input name="cnrs-dm-front-revision-manager-name" required type="text" spellcheck="false" autocomplete="off">
                    </label>
                </div>
                <div class="cnrs-dm-front-mission-form-element" data-state="light">
                    <span class="cnrs-dm-front-mission-form-element-label required"><?php echo __('Manager email', 'cnrs-data-manager') ?></span>
                    <label>
                        <input name="cnrs-dm-front-revision-manager-email" required type="email" spellcheck="false" autocomplete="off">
                    </label>
                </div>

            <?php endif; ?>

            <?php if ($type === 'revision-manager' || $type === 'revision-funder'): ?>
                <?php $index = 0; ?>
                <?php foreach ($form['elements'] as $element): ?>
                    <?php $data = $element['data'] ?>
                    <?php if ($element['type'] === 'toggle'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="<?php echo $element['type'] ?>" data-state="light">
                        <span class="cnrs-dm-front-mission-form-element-label required<?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                            <?php echo $element['label'] ?>
                            <?php if (strlen($data['tooltip']) > 0): ?>
                                <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                    <span class="cnrs-dm-front-tooltip-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                        </svg>
                                    </span>
                                    <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                </span>
                            <?php endif; ?>
                        </span>
                            <?php foreach ($data['values'] as $key => $value): ?>
                                <label>
                                    <input value="<?php echo $value ?>" type="radio" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>" data-uuid="<?php echo $element['data']['value'][0] ?>" disabled<?php echo $key === $data['choice'] ? ' checked' : '' ?>>
                                    <span class="design"></span>
                                    <span class="text"><?php echo $value ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($element['type'] === 'checkbox'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-checkbox-wrapper" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <?php $checkboxIndex = 0; ?>
                            <?php foreach ($data['choices'] as $key => $choice): ?>
                                <label class="cnrs-dm-front-checkbox-label<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-option="<?php echo stripos($choice, '-opt-comment') !== false ? 'option' : 'normal' ?>">
                                    <input disabled class="checkbox__trigger visuallyHidden" value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="checkbox"<?php echo in_array(str_replace('-opt-comment', '', $choice), stripArrayValuesSlashes($data['values']), true) ? ' checked' : '' ?>>
                                    <span class="checkbox__symbol">
                                        <svg aria-hidden="true" class="icon-checkbox" width="28px" height="28px" viewBox="0 0 28 28" version="1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 14l8 7L24 7"></path>
                                        </svg>
                                    </span>
                                    <span class="checkbox__text-wrapper"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <?php $comment = extractOptionComment($checkboxIndex, $data['options']); ?>
                                    <textarea readonly spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-type="opt-comment"<?php echo strlen($comment) > 0 ? ' required' : '' ?>><?php echo $comment ?></textarea>
                                <?php endif; ?>
                                <?php $checkboxIndex++; ?>
                            <?php endforeach; ?>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'radio'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <?php $radioIndex = 0; ?>
                            <?php foreach ($data['choices'] as $key => $choice): ?>
                                <label class="cnrs-dm-front-radio-label<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-option="<?php echo stripos($choice, '-opt-comment') !== false ? 'option' : 'normal' ?>">
                                    <input disabled value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="radio"<?php echo in_array(str_replace('-opt-comment', '', $choice), stripArrayValuesSlashes($data['values']), true) ? ' checked' : '' ?> class="cnrs-dm-front-radio-revision-input">
                                    <span class="design"></span>
                                    <span class="text"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <?php $comment = extractOptionComment($radioIndex, $data['options']); ?>
                                    <textarea readonly spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-type="opt-comment"<?php echo strlen($comment) > 0 ? ' required' : '' ?>><?php echo $comment ?></textarea>
                                <?php endif; ?>
                                <?php $radioIndex++; ?>
                            <?php endforeach; ?>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'input'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="text" spellcheck="false" autocomplete="off" value="<?php echo $data['value'][0] ?>" readonly>
                            </label>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'number'): ?>
                        <?php $split = explode(';', $element['label']); $label = $split[0]; ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $label ?>">
                                <?php echo $label ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label<?php echo isset($split[1]) && strlen($split[1]) > 0 ? ' class="is-unit"' : '' ?>>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="number" min="0" step="0.01" autocomplete="off" value="<?php echo $data['value'][0] ?>" readonly>
                                <?php if (isset($split[1]) && strlen($split[1]) > 0): ?>
                                    <span class="cnrs-dm-front-mission-form-unit"><?php echo $split[1] ?></span>
                                <?php endif; ?>
                            </label>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'date'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="date" value="<?php echo $data['value'][0] ?>" readonly>
                            </label>
                            <?php if ($data['required'] === true && $data['isReference'] === false && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'time'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="time" value="<?php echo $data['value'][0] ?>" readonly>
                            </label>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'datetime'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="datetime-local" value="<?php echo $data['value'][0] ?>" readonly>
                            </label>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'textarea'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <textarea<?php echo $data['required'] === true ? ' required' : '' ?> spellcheck="false" autocomplete="off" readonly><?php echo $data['value'][0] ?></textarea>
                            </label>
                            <?php if ($data['required'] === true && $type !== 'revision-funder'): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                    <label class="cnrs-dm-front-checkbox-label">
                                        <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                            <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                        </button>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'title'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <h3 class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                                <?php echo $element['label'] ?>
                            </h3>
                        </div>
                    <?php elseif ($element['type'] === 'comment' && $data['value'] !== null && isset($data['value'][0])): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <?php echo $data['value'][0] ?>
                        </div>
                    <?php elseif ($element['type'] === 'separator'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <hr/>
                        </div>
                    <?php endif; ?>
                    <?php $index++; ?>
                <?php endforeach; ?>
                <hr/>
                <?php if ($type === 'revision-funder'): ?>
                    <div id="cnrs-dm-front-mission-form-submit-button-container">
                        <button type="submit" id="cnrs-dm-front-funder-form-cancel-button" class="cnrs-dm-front-btn cnrs-dm-front-btn-abandon" name="cnrs-dm-front-funder-revision" value="ko"><?php echo __('Reject', 'cnrs-data-manager') ?></button>
                        <button type="submit" id="cnrs-dm-front-funder-form-validate-button" class="cnrs-dm-front-btn cnrs-dm-front-btn-save" name="cnrs-dm-front-funder-revision" value="ok"><?php echo __('Save', 'cnrs-data-manager') ?></button>
                    </div>
                <?php else: ?>
                    <div id="cnrs-dm-front-mission-form-submit-button-container">
                        <button type="submit" id="cnrs-dm-front-revision-form-submit-button" class="cnrs-dm-front-btn cnrs-dm-front-btn-save"><?php echo __('Save', 'cnrs-data-manager') ?></button>
                    </div>
                <?php endif; ?>

            <?php elseif ($type === 'revision-agent'): ?>

                <input type="hidden" name="cnrs-dm-front-agent-revision" value="ok">
                <?php $index = 0; ?>
                <?php foreach ($form['elements'] as $element): ?>
                    <?php $data = $element['data'] ?>
                    <?php if ($element['type'] === 'toggle'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="<?php echo $element['type'] ?>" data-state="light">
                        <span class="cnrs-dm-front-mission-form-element-label required<?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                            <?php echo $element['label'] ?>
                            <?php if (strlen($data['tooltip']) > 0): ?>
                                <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                    <span class="cnrs-dm-front-tooltip-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                        </svg>
                                    </span>
                                    <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                </span>
                            <?php endif; ?>
                        </span>
                            <?php foreach ($data['values'] as $key => $value): ?>
                                <label>
                                    <input value="<?php echo $value ?>" type="radio" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>" data-uuid="<?php echo $element['data']['value'][0] ?>" disabled<?php echo $key === $data['choice'] ? ' checked' : '' ?>>
                                    <span class="design"></span>
                                    <span class="text"><?php echo $value ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($element['type'] === 'checkbox'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision cnrs-dm-front-checkbox-wrapper<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <?php $checkboxIndex = 0; ?>
                            <?php foreach ($data['choices'] as $key => $choice): ?>
                                <label class="cnrs-dm-front-checkbox-label<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-option="<?php echo stripos($choice, '-opt-comment') !== false ? 'option' : 'normal' ?>">
                                    <input<?php echo $obs === null ? ' disabled' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '[]"' ?> class="checkbox__trigger visuallyHidden" value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="checkbox"<?php echo in_array(str_replace('-opt-comment', '', $choice), stripArrayValuesSlashes($data['values']), true) ? ' checked' : '' ?>>
                                    <span class="checkbox__symbol">
                                        <svg aria-hidden="true" class="icon-checkbox" width="28px" height="28px" viewBox="0 0 28 28" version="1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 14l8 7L24 7"></path>
                                        </svg>
                                    </span>
                                    <span class="checkbox__text-wrapper"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <?php $comment = extractOptionComment($checkboxIndex, $data['options']); ?>
                                    <textarea<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '-opt-comment-' . $checkboxIndex . '"' ?> spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-type="opt-comment"<?php echo strlen($comment) > 0 ? ' required' : '' ?>><?php echo $comment ?></textarea>
                                <?php endif; ?>
                                <?php $checkboxIndex++; ?>
                            <?php endforeach; ?>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'radio'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision cnrs-dm-front-radio-references<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <?php $radioIndex = 0; ?>
                            <?php foreach ($data['choices'] as $key => $choice): ?>
                                <label class="cnrs-dm-front-radio-label<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-option="<?php echo stripos($choice, '-opt-comment') !== false ? 'option' : 'normal' ?>">
                                    <input<?php echo $obs === null ? ' disabled class="cnrs-dm-front-radio-revision-input"' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?> value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="radio"<?php echo in_array(str_replace('-opt-comment', '', $choice), stripArrayValuesSlashes($data['values']), true) ? ' checked' : '' ?>>
                                    <span class="design"></span>
                                    <span class="text"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <?php $comment = extractOptionComment($radioIndex, $data['options']); ?>
                                    <textarea<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '-opt-comment-' . $radioIndex . '"' ?> spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" data-type="opt-comment"<?php echo strlen($comment) > 0 ? ' required' : '' ?>><?php echo $comment ?></textarea>
                                <?php endif; ?>
                                <?php $radioIndex++; ?>
                            <?php endforeach; ?>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'input'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="text" spellcheck="false" autocomplete="off" value="<?php echo $data['value'][0] ?>"<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?>>
                            </label>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'number'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <?php $split = explode(';', $element['label']); $label = $split[0]; ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $label ?>">
                                <?php echo $label ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label<?php echo isset($split[1]) && strlen($split[1]) > 0 ? ' class="is-unit"' : '' ?>>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="number" min="0" step="0.01" autocomplete="off" value="<?php echo $data['value'][0] ?>"<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?>>
                                <?php if (isset($split[1]) && strlen($split[1]) > 0): ?>
                                    <span class="cnrs-dm-front-mission-form-unit"><?php echo $split[1] ?></span>
                                <?php endif; ?>
                            </label>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'date'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="date" value="<?php echo $data['value'][0] ?>"<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?>>
                            </label>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'time'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="time" value="<?php echo $data['value'][0] ?>"<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?>>
                            </label>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'datetime'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="datetime-local" value="<?php echo $data['value'][0] ?>"<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?>>
                            </label>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'textarea'): ?>
                        <?php $obs = hasObservation($observations, $index); ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-mission-form-element-revision<?php echo $obs !== null ? ' cnrs-dm-front-revision-obs' : '' ?>" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>" data-label="<?php echo $element['label'] ?>">
                                <?php echo $element['label'] ?>
                                <?php if (strlen($data['tooltip']) > 0 && $obs !== null): ?>
                                    <span class="cnrs-dm-front-tooltip-container<?php echo $data['required'] === true ? ' required' : '' ?>">
                                        <span class="cnrs-dm-front-tooltip-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                            </svg>
                                        </span>
                                        <span class="cnrs-dm-front-tooltip-text"><?php echo $data['tooltip'] ?></span>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <label>
                                <textarea<?php echo $data['required'] === true ? ' required' : '' ?> spellcheck="false" autocomplete="off"<?php echo $obs === null ? ' readonly' : ' name="cnrs-dm-front-mission-form-element-' . $element['type'] . '-' . $index . '"' ?>><?php echo $data['value'][0] ?></textarea>
                            </label>
                            <?php if ($obs !== null): ?>
                                <div class="cnrs-dm-front-revision-observation-wrapper">
                                    <pre class="cnrs-dm-front-observation-textarea"><?php echo $obs ?></pre>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($element['type'] === 'title'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <h3 class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
                                <?php echo $element['label'] ?>
                            </h3>
                        </div>
                    <?php elseif ($element['type'] === 'comment' && $data['value'] !== null && isset($data['value'][0])): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <?php echo $data['value'][0] ?>
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
            <?php endif; ?>
        </form>
    </div>

<?php else: ?>

    <?php
        $message = __('The form has been saved successfully', 'cnrs-data-manager');
        if ($type === 'revision-manager') {
            $message = __('The form revision has been sent successfully', 'cnrs-data-manager');
        } else if ($type === 'revision-funder') {
            $message = __('The form validation has been sent successfully', 'cnrs-data-manager');
        }
    ?>

    <div class="cnrs-dm-mission-form">
        <h2 id="cnrs-dm-front-mission-form-title"><?php echo $form['title'] ?></h2>
        <p class="cnrs-dm-front-mission-form-subtitles"><?php echo $message ?></p>
        <div id="cnrs-dm-front-mission-form-submit-button-container">
            <a href="<?php echo get_home_url(); ?>" id="cnrs-dm-front-mission-form-home-button" class="cnrs-dm-front-btn"><?php echo __('Back to home', 'cnrs-data-manager') ?></a>
        </div>
    </div>

<?php endif; ?>
