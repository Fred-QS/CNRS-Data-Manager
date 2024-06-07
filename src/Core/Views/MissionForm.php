<?php

use CnrsDataManager\Core\Controllers\Emails;
use CnrsDataManager\Core\Controllers\Manager;

if ($validated === false): ?>

    <div class="cnrs-dm-mission-form">
        <script>
            const missionForm = <?php echo $json ?>;
            const daysLimit = <?php echo $days_limit ?>;
            const monthLimit = <?php echo $month_limit ?>;
            const daysLimitAlert = "<?php echo sprintf(__('<b>Warning !</b> The mission start date is less <b>than %d days</b>. Your request will be rejected if deemed not urgent.', 'cnrs-data-manager'), $days_limit) ?>";
            const monthLimitAlert = "<?php echo sprintf(__('<b>Warning !</b> The mission start date is less <b>than %d days</b>. Your request will be rejected if deemed not urgent.', 'cnrs-data-manager'), $month_limit) ?>";
            let isInternational = null;
            const foreignMessage = "<?php echo __('Foreign mission', 'cnrs-data-manager') ?>";
            const franceMessage = "<?php echo __('Mission in France', 'cnrs-data-manager') ?>";
            const togglesObject = <?php echo json_encode($toggles) ?>;
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
        <div id="cnrs-dm-front-mission-dest-button-container">
            <button type="button" class="cnrs-dm-front-btn cnrs-dm-front-btn-choose-dest" data-choice="0"><?php echo __('Mission in France', 'cnrs-data-manager') ?></button>
            <button type="button" class="cnrs-dm-front-btn cnrs-dm-front-btn-choose-dest" data-choice="1"><?php echo __('Foreign mission', 'cnrs-data-manager') ?></button>
        </div>
        <form method="post" id="cnrs-dm-front-mission-form-wrapper" class="cnrs-dm-front-mission-form-wrapper-init" action="<?php echo add_query_arg(NULL, NULL)  ?>">
            <p id="cnrs-dm-front-mission-intl"></p>
            <input type="hidden" value="0" name="cnrs-dm-front-mission-intl">
            <input type="hidden" value='<?php echo htmlentities($json) ?>' name="cnrs-dm-front-mission-form-original">
            <input type="hidden" value="<?php echo wp_generate_uuid4() ?>" name="cnrs-dm-front-mission-uuid">
            <?php if (count($conventions) > 1): ?>
                <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="radio-convention" data-state="light">
                        <span class="cnrs-dm-front-mission-form-element-label required">
                            <?php echo __('Choose a convention', 'cnrs-data-manager') ?>
                        </span>
                    <?php $conventionIndex = 0; ?>
                    <?php foreach ($conventions as $convention): ?>
                        <label>
                            <input<?php if ($conventionIndex === 0): echo ' checked'; endif; ?> value="<?php echo $convention['id'] ?>" type="radio" name="cnrs-dm-front-convention">
                            <span class="design"></span>
                            <span class="text"><?php echo $convention['name'] ?></span>
                        </label>
                        <?php $conventionIndex++; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <input type="hidden" value="<?php echo $conventions[0]['id'] ?>" name="cnrs-dm-front-convention">
            <?php endif; ?>
            <hr>
            <br>
            <?php foreach (Manager::getOriginalToggle() as $key => $originalToggle): ?>
                <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="radio-toggle" data-state="light">
                    <span class="cnrs-dm-front-mission-form-element-label required">
                        <?php echo $originalToggle['label'] ?>
                    </span>
                    <label>
                        <input value="1" checked type="radio" name="cnrs-dm-front-toggle-<?php echo $originalToggle['id'] ?>" data-uuid="<?php echo $originalToggle['id'] ?>">
                        <span class="design"></span>
                        <span class="text"><?php echo $originalToggle['values'][0] ?></span>
                    </label>
                    <label>
                        <input value="0" type="radio" name="cnrs-dm-front-toggle-<?php echo $originalToggle['id'] ?>" data-uuid="<?php echo $originalToggle['id'] ?>">
                        <span class="design"></span>
                        <span class="text"><?php echo $originalToggle['values'][1] ?></span>
                    </label>
                </div>
                <?php if ($originalToggle['label'] === __('Fees', 'cnrs-data-manager')): ?>
                    <div class="cnrs-dm-front-mission-form-element" data-type="input" data-state="light">
                        <span class="cnrs-dm-front-mission-form-element-label required">
                            <?php echo __('Credit manager email', 'cnrs-data-manager') ?>
                            <span class="cnrs-dm-front-tooltip-container required">
                                <span class="cnrs-dm-front-tooltip-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"/>
                                    </svg>
                                </span>
                                <span class="cnrs-dm-front-tooltip-text"><?php echo __('If your request involves fees, please enter the email of the credit manager', 'cnrs-data-manager') ?></span>
                            </span>
                        </span>
                        <label>
                            <input required type="email" spellcheck="false" autocomplete="off" name="cnrs-dm-front-funder-email">
                        </label>
                    </div>
                    <hr>
                    <br>
                <?php endif; ?>
            <?php endforeach; ?>
            <div id="cnrs-dm-front-dynamic-elements-wrapper">
                <?php $index = 0; ?>
                <?php foreach ($form['elements'] as $element): ?>
                    <?php $data = $element['data'] ?>
                    <?php if ($element['type'] === 'toggle'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="radio-<?php echo $element['type'] ?>" data-state="light" data-uuid="<?php echo $element['data']['value'][0] ?>">
                            <span class="cnrs-dm-front-mission-form-element-label required<?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                            <?php $toggleIndex = 0; ?>
                            <?php foreach ($data['values'] as $value): ?>
                                <label>
                                    <input<?php if ($toggleIndex === 0): echo ' checked'; endif; ?> value="<?php echo $toggleIndex ?>" type="radio" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>" data-uuid="<?php echo $element['data']['value'][0] ?>">
                                    <span class="design"></span>
                                    <span class="text"><?php echo $value ?></span>
                                </label>
                                <?php $toggleIndex++; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php elseif ($element['type'] === 'checkbox'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-checkbox-wrapper" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="text" spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                            </label>
                        </div>
                    <?php elseif ($element['type'] === 'number'): ?>
                        <?php $split = explode(';', $element['label']); $label = $split[0]; ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="number" min="0" step="0.01" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                                <?php if (isset($split[1]) && strlen($split[1]) > 0): ?>
                                    <span class="cnrs-dm-front-mission-form-unit"><?php echo $split[1] ?></span>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php elseif ($element['type'] === 'date'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="date" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>"<?php echo $data['isReference'] === true ? ' id="cnrs-dm-front-reference-input"' : '' ?>>
                                <?php if ($data['isReference'] === true): ?>
                                    <span id="cnrs-dm-front-reference-alert"></span>
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php elseif ($element['type'] === 'time'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="time" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                            </label>
                        </div>
                    <?php elseif ($element['type'] === 'datetime'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="datetime-local" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                            </label>
                        </div>
                    <?php elseif ($element['type'] === 'textarea'): ?>
                        <div class="cnrs-dm-front-mission-form-element" data-type="<?php echo $element['type'] ?>" data-state="<?php echo $data['required'] === true ? 'light' : 'black' ?>">
                            <span class="cnrs-dm-front-mission-form-element-label<?php echo $data['required'] === true ? ' required' : '' ?><?php echo strlen(trim($element['label'])) === 0 ? ' no-label' : '' ?>">
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
                            <?php echo $data['value'][0] ?>
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
            </div>
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
