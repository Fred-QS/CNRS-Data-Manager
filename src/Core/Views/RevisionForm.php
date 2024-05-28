<?php if ($validated === false): ?>

    <div class="cnrs-dm-mission-form">
        <script>
            const missionForm = <?php echo $json ?>;
        </script>
        <h2 id="cnrs-dm-front-mission-form-title">
            <?php if ($form['revisions'] < 1): echo __('Form check', 'cnrs-data-manager'); else: echo __('Revision n°', 'cnrs-data-manager') . $form['revisions']; endif; ?>
        </h2>
        <p class="cnrs-dm-front-mission-form-subtitles"><?php echo $type === 'revision-manager' ? __('Please control the form', 'cnrs-data-manager') : __('Please correct the fields with comments', 'cnrs-data-manager') ?></p>
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
            <form method="post" id="cnrs-dm-front-mission-form-wrapper" action="<?php echo add_query_arg(NULL, NULL)  ?>">
        <?php endif; ?>

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
                <?php $index = 0; ?>
                <?php foreach ($form['elements'] as $element): ?>
                    <?php $data = $element['data'] ?>
                    <?php if ($element['type'] === 'checkbox'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-checkbox-wrapper" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                                <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <?php foreach ($data['choices'] as $choice): ?>
                                <label class="cnrs-dm-front-checkbox-label" data-option="<?php echo stripos($choice, '-opt-comment') !== false ? 'option' : 'normal' ?>">
                                    <input disabled class="checkbox__trigger visuallyHidden" value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="checkbox" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>[]"<?php echo in_array(str_replace('-opt-comment', '', $choice), $data['values'], true) ? ' checked' : '' ?>>
                                    <span class="checkbox__symbol">
                                        <svg aria-hidden="true" class="icon-checkbox" width="28px" height="28px" viewBox="0 0 28 28" version="1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 14l8 7L24 7"></path>
                                        </svg>
                                    </span>
                                    <span class="checkbox__text-wrapper"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <?php $comment = extractOptionComment($checkboxIndex, $data['options']); ?>
                                    <textarea readonly spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment" data-type="opt-comment"<?php echo strlen($comment) > 0 ? ' required' : '' ?>><?php echo $comment ?></textarea>
                                <?php endif; ?>
                                <?php $checkboxIndex++; ?>
                            <?php endforeach; ?>
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'radio'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                                <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <?php foreach ($data['choices'] as $choice): ?>
                                <label>
                                    <input disabled value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="radio" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>"<?php echo in_array(str_replace('-opt-comment', '', $choice), $data['values'], true) ? ' checked' : '' ?>>
                                    <span class="design"></span>
                                    <span class="text"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <?php $comment = extractOptionComment($checkboxIndex, $data['options']); ?>
                                    <textarea readonly spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment" data-type="opt-comment"<?php echo strlen($comment) > 0 ? ' required' : '' ?>><?php echo $comment ?></textarea>
                                <?php endif; ?>
                                <?php $radioIndex++; ?>
                            <?php endforeach; ?>
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'input'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                                <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'number'): ?>
                        <?php $split = explode(';', $element['label']); $label = $split[0]; ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'date'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'time'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'datetime'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
                        </div>
                    <?php elseif ($element['type'] === 'textarea'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?>">
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
                            <div class="cnrs-dm-front-revision-observation-wrapper" data-index="<?php echo $index ?>">
                                <label class="cnrs-dm-front-checkbox-label">
                                    <button type="button" data-add="<?php echo __('Add an observation', 'cnrs-data-manager') ?>" data-remove="<?php echo __('Remove observation', 'cnrs-data-manager') ?>" class="cnrs-dm-front-revision-observation cnrs-dm-front-btn add">
                                        <?php echo __('Add an observation', 'cnrs-data-manager') ?>
                                    </button>
                                </label>
                            </div>
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
                <div id="cnrs-dm-front-mission-form-submit-button-container">
                    <button type="submit" id="cnrs-dm-front-revision-form-submit-button" class="cnrs-dm-front-btn cnrs-dm-front-btn-save"><?php echo __('Save', 'cnrs-data-manager') ?></button>
                </div>

            <?php elseif ($type === 'revision-agent'): ?>



            <?php endif; ?>
        </form>
    </div>

<?php else: ?>

    <div class="cnrs-dm-mission-form">
        <h2 id="cnrs-dm-front-mission-form-title"><?php echo $form['title'] ?></h2>
        <p class="cnrs-dm-front-mission-form-subtitles"><?php echo $type === 'revision-agent' ? __('The form has been saved successfully', 'cnrs-data-manager') : __('The form revision has been sent successfully', 'cnrs-data-manager') ?></p>
        <div id="cnrs-dm-front-mission-form-submit-button-container">
            <a href="<?php echo get_home_url(); ?>" id="cnrs-dm-front-mission-form-home-button" class="cnrs-dm-front-btn"><?php echo __('Back to home', 'cnrs-data-manager') ?></a>
        </div>
    </div>

<?php endif; ?>
