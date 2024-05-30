<table style="width: 100%;">
    <tr data-type="mission-title">
        <th colspan="2" align="left">
            <span style="font-family: <?php echo $boldFont1; ?>, sans-serif; font-size: 46px; color: #58595A; width: 100%"><?php echo $form['title'] ?></span>
            <br>
            <span style="font-family: <?php echo $mediumFont2; ?>, sans-serif; font-size: 24px; color: #A9AAAD;"><?php echo sprintf(__('Form validated on %s', 'cnrs-data-manager'), str_starts_with(get_locale(), 'fr_') ? date("d/m/Y") : date("Y-m-d")) ?></span>
        </th>
    </tr>
    <tr><td colspan="2"></td></tr>
    <tr data-type="agent">
        <td style="width: 1.5cm">
            &nbsp;<img src="<?php echo $data->agent_avatar ?>" style="width: 45px; height: 45px;" alt="<?php echo $data->agent_name ?>">
        </td>
        <td style="width: 17.5cm">
            <span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 14px; color: #6A6666;"><?php echo $data->agent_name ?><br>
                <a href="mailto:<?php echo $data->agent_email ?>" style="font-family: <?php echo $italicFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB; font-style: italic;"><?php echo $data->agent_email ?></a></span>
        </td>
    </tr>
    <tr><td colspan="2"></td></tr>
    <tr><td colspan="2"></td></tr>
    <tr>
        <td colspan="2">
            <span style="font-family: <?php echo $boldFont1; ?>, sans-serif; font-size: 20px; color: #A9AAAD; width: 100%"><?php echo __('Manager who validated the form', 'cnrs-data-manager') ?></span>
        </td>
    </tr>
    <tr><td colspan="2"></td></tr>
    <tr>
        <td style="width: 1.5cm">
            &nbsp;<img src="<?php echo $data->manager_avatar ?>" style="width: 45px; height: 45px;" alt="<?php echo $data->agent_name ?>">
        </td>
        <td style="width: 17.5cm">
            <span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 14px; color: #6A6666;"><?php echo $data->manager_name ?><br>
                <a href="mailto:<?php echo $data->manager_email ?>" style="font-family: <?php echo $italicFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB; font-style: italic;"><?php echo $data->manager_email ?></a></span>
        </td>
    </tr>
    <tr><td colspan="2"></td></tr>
    <tr><td colspan="2"></td></tr>
    <?php foreach ($form['elements'] as $element): ?>
        <tr><td colspan="2"></td></tr>
        <?php if ($element['type'] === 'input'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-value">
                <td colspan="2" style="background-color: #edf7f6;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 0.5%;"></td>
                            <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo $element['data']['value'][0] ?></div>
                            </td>
                            <td style="width: 0.5%;"></td>
                        </tr>
                        <tr><td colspan="3"></td></tr>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'number'): ?>
            <?php $split = explode(';', $element['label']) ?>
            <tr data-type="<?php echo $element['type'] ?>-label" style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $split[0] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-value">
                <td colspan="2" style="background-color: #edf7f6;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 0.5%;"></td>
                            <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo $element['data']['value'][0] ?><?php echo isset($split[1]) ? ' ' . $split[1] : '' ?></div>
                            </td>
                            <td style="width: 0.5%;"></td>
                        </tr>
                        <tr><td colspan="3"></td></tr>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'time'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-value">
                <td colspan="2" style="background-color: #edf7f6;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 0.5%;"></td>
                            <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo formatDateForPDF($element['data']['value'][0], 'time') ?></div>
                            </td>
                            <td style="width: 0.5%;"></td>
                        </tr>
                        <tr><td colspan="3"></td></tr>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'date'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-value">
                <td colspan="2" style="background-color: #edf7f6;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 0.5%;"></td>
                            <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo formatDateForPDF($element['data']['value'][0], 'date') ?></div>
                            </td>
                            <td style="width: 0.5%;"></td>
                        </tr>
                        <tr><td colspan="3"></td></tr>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'datetime'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-value">
                <td colspan="2" style="background-color: #edf7f6;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 0.5%;"></td>
                            <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo formatDateForPDF($element['data']['value'][0]) ?></div>
                            </td>
                            <td style="width: 0.5%;"></td>
                        </tr>
                        <tr><td colspan="3"></td></tr>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'textarea'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-value">
                <td colspan="2" style="background-color: #edf7f6;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 0.5%;"></td>
                            <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                <?php if (isset($element['data']['value'][0])): ?>
                                    <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo str_replace("\n", '<br>', $element['data']['value'][0]) ?></div>
                                <?php else: ?>
                                    <div><br><br><br></div>
                                <?php endif; ?>
                            </td>
                            <td style="width: 0.5%;"></td>
                        </tr>
                        <tr><td colspan="3"></td></tr>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'checkbox'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-values">
                <td colspan="2">
                    <table style="width: 100%">
                        <?php foreach($element['data']['choices'] as $index => $choice): ?>
                            <tr>
                                <?php $c = str_replace('-opt-comment', '', $choice); ?>
                                <td style="width: 0.7cm;"><img src="<?php echo isCheckedPDF($c, $element['data']['values']) ? $checked : $unchecked; ?>" alt="checkbox" width="24" height="24"></td>
                                <td style="width: 17.9cm;">
                                    <span style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 13px; color: #58595a;"><?php echo $c ?></span>
                                </td>
                            </tr>
                            <?php $comment = hasChoiceComment($index, $element['data']['options']); ?>
                            <?php if ($comment !== null): ?>
                                <tr style="background-color: #edf7f6;">
                                    <td style="width: 2%;"></td>
                                    <td style="width: 96%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                        <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo str_replace("\n", '<br>', $comment) ?></div>
                                    </td>
                                    <td style="width: 2%;"></td>
                                </tr>
                                <tr style="background-color: #edf7f6;"><td colspan="3"></td></tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'radio'): ?>
            <tr data-type="<?php echo $element['type'] ?>-label">
                <th align="left" colspan="2"><br><span style="font-family: <?php echo $boldFont2 ?>, sans-serif; font-size: 12px; color: #0C85BB;"><?php echo $element['label'] ?><?php if ($element['data']['required'] === true): ?><img src="<?php echo $require; ?>" width="14" alt="require"><?php endif; ?></span></th>
            </tr>
            <tr data-type="<?php echo $element['type'] ?>-values">
                <td colspan="2">
                    <table style="width: 100%">
                        <?php foreach($element['data']['choices'] as $index => $choice): ?>
                            <tr>
                                <?php $c = str_replace('-opt-comment', '', $choice); ?>
                                <td style="width: 0.7cm;"><img src="<?php echo isCheckedPDF($c, $element['data']['values']) ? $radioChecked : $radioUnchecked; ?>" alt="checkbox" width="24" height="24"></td>
                                <td style="width: 17.9cm;">
                                    <span style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 13px; color: #58595a;"><?php echo $c ?></span>
                                </td>
                            </tr>
                            <?php $comment = hasChoiceComment($index, $element['data']['options']); ?>
                            <?php if ($comment !== null): ?>
                                <tr>
                                    <td style="width: 0.5%;"></td>
                                    <td style="width: 99%; font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 12px;">
                                        <div style="font-family: <?php echo $mediumFont1 ?>, sans-serif; font-size: 14px; color: #58595A;"><?php echo str_replace("\n", '<br>', $comment) ?></div>
                                    </td>
                                    <td style="width: 0.5%;"></td>
                                </tr>
                                <tr><td colspan="3"></td></tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'separator'): ?>
            <tr data-type="<?php echo $element['type'] ?>"><td colspan="2"><div style="border-bottom: 1px solid #e1e1e1;"></div></td></tr>
        <?php elseif ($element['type'] === 'title' && strlen($element['label']) > 0): ?>
            <tr data-type="<?php echo $element['type'] ?>"><th align="left" colspan="2"><span style="font-family: <?php echo $boldFont1; ?>, sans-serif; font-size: 20px; color: #0C85BB;"><?php echo $element['label'] ?></span></th></tr>
        <?php elseif ($element['type'] === 'comment'): ?>
            <tr data-type=<?php echo $element['type'] ?>><td colspan="2" style="font-family: <?php echo $lightFont1 ?>, sans-serif;"><?php echo $element['data']['value'][0] ?></td></tr>
        <?php elseif ($element['type'] === 'signs'): $pads = $element['data']['values']; ?>
            <?php for($i = 0; $i < count($pads); $i += 2): ?>
                <tr data-type="<?php echo $element['type'] ?>">
                    <td colspan="2">
                        <br><br><br>
                        <table>
                            <tr><td colspan="2"></td></tr>
                            <tr>
                                <?php if (isset($pads[$i])): ?>
                                    <td align="center" colspan="1">
                                        <?php $data = $pads[$i]['data']; $sign = $data['sign']; unset($data['sign']); ?>
                                        <?php $fontSize1 = 14; foreach ($data as $item): ?>
                                            <?php if ($fontSize1 === 14): ?>
                                                <span style="font-family: <?php echo $mediumFont1; ?>, sans-serif; font-size: <?php echo $fontSize1; ?>px; color: #0C86BB;"><?php echo $item ?></span>
                                            <?php else: ?>
                                                <span style="font-family: <?php echo $italicFont1; ?>, sans-serif; font-size: <?php echo $fontSize1; ?>px; color: #68C0B5;"><?php echo $item ?></span>
                                            <?php endif; ?>
                                            <br/>
                                        <?php $fontSize1 -= 2; endforeach; ?>
                                        <img style="width: 6cm;" src="@<?php echo str_replace('data:image/png;base64,', '', $sign) ?>" alt="sign">
                                    </td>
                                <?php endif; ?>
                                <?php if (isset($pads[$i+1])): ?>
                                    <td align="center" colspan="1">
                                        <?php $data = $pads[$i+1]['data']; $sign = $data['sign']; unset($data['sign']); ?>
                                        <?php $fontSize2 = 14; foreach ($data as $item): ?>
                                            <?php if ($fontSize2 === 14): ?>
                                                <span style="font-family: <?php echo $mediumFont1; ?>, sans-serif; font-size: <?php echo $fontSize2; ?>px; color: #0C86BB;"><?php echo $item ?></span>
                                            <?php else: ?>
                                                <span style="font-family: <?php echo $italicFont1; ?>, sans-serif; font-size: <?php echo $fontSize2; ?>px; color: #68C0B5;"><?php echo $item ?></span>
                                            <?php endif; ?>

                                            <br/>
                                        <?php $fontSize2 -= 2; endforeach; ?>
                                        <img style="width: 6cm;" src="@<?php echo str_replace('data:image/png;base64,', '', $sign) ?>" alt="sign">
                                    </td>
                                <?php endif; ?>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?php endfor; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
