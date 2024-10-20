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
            const foreignMessage = "<?php echo __('Abroad mission', 'cnrs-data-manager') ?>";
            const foreignLabel = "<?php echo __('Abroad', 'cnrs-data-manager') ?>";
            const franceMessage = "<?php echo __('Mission in France', 'cnrs-data-manager') ?>";
            const franceLabel = "<?php echo __('France', 'cnrs-data-manager') ?>";
            const missionLocationToggleUuid = "<?php echo Manager::LOCATION_UUID ?>"
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
        <div id="cnrs-dm-front-mission-dest-disclaimer">
            <p><?php echo __('This form is to be completed by the missionary and sent to the unit managers for editing of the mission order.', 'cnrs-data-manager') ?></p>
            <p><?php echo __('Mission requests must be sent to the management department', 'cnrs-data-manager') ?>:</p>
            <ul>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                        <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                    </svg>
                    <?php echo __('In France: 1 week minimum before departure (5 working days)', 'cnrs-data-manager') ?>
                </li>
                <li>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M266.3 48.3L232.5 73.6c-5.4 4-8.5 10.4-8.5 17.1l0 9.1c0 6.8 5.5 12.3 12.3 12.3c2.4 0 4.8-.7 6.8-2.1l41.8-27.9c2-1.3 4.4-2.1 6.8-2.1l1 0c6.2 0 11.3 5.1 11.3 11.3c0 3-1.2 5.9-3.3 8l-19.9 19.9c-5.8 5.8-12.9 10.2-20.7 12.8l-26.5 8.8c-5.8 1.9-9.6 7.3-9.6 13.4c0 3.7-1.5 7.3-4.1 10l-17.9 17.9c-6.4 6.4-9.9 15-9.9 24l0 4.3c0 16.4 13.6 29.7 29.9 29.7c11 0 21.2-6.2 26.1-16l4-8.1c2.4-4.8 7.4-7.9 12.8-7.9c4.5 0 8.7 2.1 11.4 5.7l16.3 21.7c2.1 2.9 5.5 4.5 9.1 4.5c8.4 0 13.9-8.9 10.1-16.4l-1.1-2.3c-3.5-7 0-15.5 7.5-18l21.2-7.1c7.6-2.5 12.7-9.6 12.7-17.6c0-10.3 8.3-18.6 18.6-18.6l29.4 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-20.7 0c-7.2 0-14.2 2.9-19.3 8l-4.7 4.7c-2.1 2.1-3.3 5-3.3 8c0 6.2 5.1 11.3 11.3 11.3l11.3 0c6 0 11.8 2.4 16 6.6l6.5 6.5c1.8 1.8 2.8 4.3 2.8 6.8s-1 5-2.8 6.8l-7.5 7.5C386 262 384 266.9 384 272s2 10 5.7 13.7L408 304c10.2 10.2 24.1 16 38.6 16l7.3 0c6.5-20.2 10-41.7 10-64c0-111.4-87.6-202.4-197.7-207.7zm172 307.9c-3.7-2.6-8.2-4.1-13-4.1c-6 0-11.8-2.4-16-6.6L396 332c-7.7-7.7-18-12-28.9-12c-9.7 0-19.2-3.5-26.6-9.8L314 287.4c-11.6-9.9-26.4-15.4-41.7-15.4l-20.9 0c-12.6 0-25 3.7-35.5 10.7L188.5 301c-17.8 11.9-28.5 31.9-28.5 53.3l0 3.2c0 17 6.7 33.3 18.7 45.3l16 16c8.5 8.5 20 13.3 32 13.3l21.3 0c13.3 0 24 10.7 24 24c0 2.5 .4 5 1.1 7.3c71.3-5.8 132.5-47.6 165.2-107.2zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zM187.3 100.7c-6.2-6.2-16.4-6.2-22.6 0l-32 32c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0l32-32c6.2-6.2 6.2-16.4 0-22.6z"/>
                    </svg>
                    <?php echo __('Abroad: 1 month minimum before departure', 'cnrs-data-manager') ?>
                </li>
            </ul>
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
                    <input required id="cnrs-dm-front-convention-id" name="cnrs-dm-front-convention" type="number" step="1" min="0" value="<?php echo $conventions[0]['id'] ?>">
                    <label>
                        <input id="cnrs-dm-front-convention-text" type="text" spellcheck="false" autocomplete="off" value="<?php echo $conventions[0]['name'] ?>">
                    </label>
                    <ul id="cnrs-dm-front-conventions-list">
                        <?php $conventionIndex = 0; ?>
                        <?php foreach ($conventions as $i => $convention): ?>
                            <li class="cnrs-dm-front-convention<?php echo $i === 0 ? ' selected' : '' ?>" data-id="<?php echo $convention['id'] ?>" style="display: none;">
                                <?php echo $convention['name'] ?>
                            </li>
                            <?php $conventionIndex++; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php else: ?>
                <input type="hidden" value="<?php echo $conventions[0]['id'] ?>" name="cnrs-dm-front-convention">
            <?php endif; ?>
            <hr>
            <br>
            <?php foreach (Manager::getOriginalToggle() as $key => $originalToggle): ?>
                <?php if ($originalToggle['hidden'] === false): ?>
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
                        <?php if (isset($originalToggle['values'][2])): ?>
                            <label>
                                <input value="0" type="radio" name="cnrs-dm-front-toggle-<?php echo $originalToggle['id'] ?>" data-uuid="<?php echo $originalToggle['id'] ?>">
                                <span class="design"></span>
                                <span class="text"><?php echo $originalToggle['values'][2] ?></span>
                            </label>
                        <?php endif; ?>
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
                <?php endif; ?>
            <?php endforeach; ?>
            <div id="cnrs-dm-front-dynamic-elements-wrapper">
                <?php $index = 0; ?>
                <?php foreach ($form['elements'] as $element): ?>
                    <?php $data = $element['data'] ?>
                    <?php if ($element['type'] === 'toggle'): ?>
                        <div class="cnrs-dm-front-mission-form-element cnrs-dm-front-radio-references" data-type="radio-<?php echo $element['type'] ?>" data-state="light" data-uuid="<?php echo $element['data']['value'][0] ?>" data-label="<?php echo $element['label'] ?>">
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
                                    <input class="checkbox__trigger visuallyHidden" value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="checkbox" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>[]">
                                    <span class="checkbox__symbol">
                                        <svg aria-hidden="true" class="icon-checkbox" width="28px" height="28px" viewBox="0 0 28 28" version="1" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 14l8 7L24 7"></path>
                                        </svg>
                                    </span>
                                    <span class="checkbox__text-wrapper"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <textarea spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>-opt-comment-<?php echo $checkboxIndex; ?>" data-type="opt-comment"></textarea>
                                <?php endif; ?>
                                <?php $checkboxIndex++; ?>
                            <?php endforeach; ?>
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
                                    <input<?php if ($radioIndex === 0): echo ' checked'; endif; ?> value="<?php echo str_replace('-opt-comment', '', $choice) ?>" type="radio" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                                    <span class="design"></span>
                                    <span class="text"><?php echo str_replace('-opt-comment', '', $choice) ?></span>
                                </label>
                                <?php if (stripos($choice, '-opt-comment') !== false): ?>
                                    <textarea spellcheck="false" autocomplete="off" class="cnrs-dm-front-mission-form-opt-comment<?php echo strlen(trim($element['label'])) === 0 && $key > 0 ? ' no-label-margin-left-required' : '' ?>" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>-opt-comment-<?php echo $radioIndex; ?>" data-type="opt-comment"></textarea>
                                <?php endif; ?>
                                <?php $radioIndex++; ?>
                            <?php endforeach; ?>
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="text" spellcheck="false" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                            </label>
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="number" min="0" step="0.01" autocomplete="off" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                                <?php if (isset($split[1]) && strlen($split[1]) > 0): ?>
                                    <span class="cnrs-dm-front-mission-form-unit"><?php echo $split[1] ?></span>
                                <?php endif; ?>
                            </label>
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="date" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>"<?php echo $data['isReference'] === true ? ' id="cnrs-dm-front-reference-input"' : '' ?>>
                                <?php if ($data['isReference'] === true): ?>
                                    <span id="cnrs-dm-front-reference-alert"></span>
                                <?php endif; ?>
                            </label>
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="time" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                            </label>
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
                                <input<?php echo $data['required'] === true ? ' required' : '' ?> type="datetime-local" name="cnrs-dm-front-mission-form-element-<?php echo $element['type']; ?>-<?php echo $index; ?>">
                            </label>
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
                            <?php echo str_replace('<br/><li', '<li', $data['value'][0]) ?>
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
