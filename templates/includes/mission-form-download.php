<?php

use CnrsDataManager\Core\Models\Forms;

$mode = explode(
    '?',
    str_replace(
        ['/cnrs-umr/mission-form-', '/'],
        '',
        $_SERVER['REQUEST_URI']
    )
)[0];

$uuid = html_entity_decode(urldecode($_GET['cdm-pdf']));
$data = Forms::getFormsByUuid($uuid);

if (!in_array($mode, ['print', 'download'], true) || $data === null) {
    header('Location: /404');
}

$form = json_decode($data->form, true);
$title = __('Mission form', 'cnrs-data-manager') . '-' . str_replace(['-', ' ', ':'], '', $data->created_at);

require_once(CNRS_DATA_MANAGER_TCPDF_PATH . '/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('UMR');
$pdf->SetTitle($title);
$pdf->SetSubject(__('Mission form', 'cnrs-data-manager'));
$pdf->SetKeywords('Mission form, CNRS, UMR');

// remove default header/footer
$pdf->SetPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
//$pdf->SetFont('dejavusans', '', 16);

$pdf->AddPage();

ob_start(); ?>
<table>
    <tr><th><h1><?php echo $form['title'] ?></h1></th></tr>
    <?php foreach ($form['elements'] as $element): ?>
        <?php if ($element['type'] === 'input'): ?>
            <tr><th><h5><?php echo $element['label'] ?></h5></th></tr>
            <?php if (isset($element['data']['value'][0])): ?>
                <tr><td><?php echo $element['data']['value'][0] ?></td></tr>
            <?php else: ?>
                <tr><td><div style="border-bottom: 1px dashed #000000; margin: 0;"></div></td></tr>
            <?php endif; ?>
        <?php elseif ($element['type'] === 'textarea'): ?>
            <tr><th><h5><?php echo $element['label'] ?></h5></th></tr>
            <?php if (isset($element['data']['value'][0])): ?>
                <tr><td><?php echo $element['data']['value'][0] ?></td></tr>
            <?php else: ?>
                <tr>
                    <td>
                        <div style="border-bottom: 1px dashed #000000; margin: 0;"></div>
                        <div style="border-bottom: 1px dashed #000000; margin: 0;"></div>
                        <div style="border-bottom: 1px dashed #000000; margin: 0;"></div>
                        <div style="border-bottom: 1px dashed #000000; margin: 0;"></div>
                    </td>
                </tr>
            <?php endif; ?>
        <?php elseif ($element['type'] === 'checkbox'): ?>
            <tr><th><h5><?php echo $element['label'] ?></h5></th></tr>
            <tr>
                <td>
                <pre>
                    <?php var_export($element) ?>
                </pre>
                </td>
            </tr>
        <?php elseif ($element['type'] === 'radio'): ?>
            <tr><th><h5><?php echo $element['label'] ?></h5></th></tr>
            <tr><td>
                <pre>
                    <?php var_export($element) ?>
                </pre>
                </td></tr>
        <?php elseif ($element['type'] === 'separator'): ?>
            <tr><td colspan="2"><div style="border-bottom: 1px solid #c1c1c1;"></div></td></tr>
        <?php elseif ($element['type'] === 'title' && strlen($element['label']) > 0): ?>
            <tr><th colspan="2"><h3><?php echo $element['label'] ?></h3></th></tr>
        <?php elseif ($element['type'] === 'comment' && isset($element['value'][0])): ?>
            <tr><td colspan="2"><i><?php echo $element['value'][0] ?></i></td></tr>
        <?php elseif ($element['type'] === 'signs'): ?>
            <tr>
                <td>
                    <table>
                        <?php $cnt = 0; ?>
                        <?php foreach ($element['data']['values'] as $pad): ?>
                            <?php if ($cnt % 2 === 0): ?>
                                <tr>
                            <?php endif; ?>
                            <td>
                                <?php $data = $pad['data']; $sign = $data['sign']; unset($data['sign']); ?>
                                <?php foreach ($data as $item): ?>
                                    <span><?php echo $item ?></span>
                                    <br/>
                                <?php endforeach; ?>
                                <img style="width: 6cm;" src="@<?php echo str_replace('data:image/png;base64,', '', $sign) ?>" alt="sign">
                            </td>
                            <?php if ($cnt % 2 !== 0): ?>
                                </tr>
                            <?php endif; ?>
                            <?php $cnt++; endforeach; ?>
                    </table>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
</table>
<?php $html = ob_get_clean();
$pdf->writeHTML($html, true, false, true, false, '');

//echo $html;

$expose = $mode === 'print' ? 'I' : 'D';
$pdf->Output($title . '.pdf', $expose);