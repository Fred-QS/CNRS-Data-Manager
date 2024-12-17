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
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part(404);
    exit();
}

$form = json_decode($data->form, true);
$title = __('Mission form', 'cnrs-data-manager') . '-' . str_replace(['-', ' ', ':'], '', $data->validated_at);

require_once(CNRS_DATA_MANAGER_TCPDF_PATH . '/tcpdf.php');

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('UMR');
$pdf->SetTitle($title);
$pdf->SetSubject(__('Mission form', 'cnrs-data-manager'));
$pdf->SetKeywords('Mission form, CNRS, UMR');

$pdf->SetPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$lightFont1 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/josefin/JosefinSans-Light.ttf', 'TrueTypeUnicode', '', 96);
$mediumFont1 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/josefin/JosefinSans-Medium.ttf', 'TrueTypeUnicode', '', 96);
$italicFont1 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/josefin/JosefinSans-Italic.ttf', 'TrueTypeUnicode', '', 96);
$regularFont1 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/josefin/JosefinSans-Regular.ttf', 'TrueTypeUnicode', '', 96);
$boldFont1 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/josefin/JosefinSans-Bold.ttf', 'TrueTypeUnicode', '', 96);

$lightFont2 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/open/OpenSans-Light.ttf', 'TrueTypeUnicode', '', 96);
$mediumFont2 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/open/OpenSans-Medium.ttf', 'TrueTypeUnicode', '', 96);
$italicFont2 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/open/OpenSans-Italic.ttf', 'TrueTypeUnicode', '', 96);
$regularFont2 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/open/OpenSans-Regular.ttf', 'TrueTypeUnicode', '', 96);
$boldFont2 = TCPDF_FONTS::addTTFfont(CNRS_DATA_MANAGER_PATH . '/assets/fonts/open/OpenSans-Bold.ttf', 'TrueTypeUnicode', '', 96);

$pdf->AddPage();
$require = CNRS_DATA_MANAGER_PATH . '/assets/media/require.png';
$unchecked = CNRS_DATA_MANAGER_PATH . '/assets/media/square_empty.png';
$checked = CNRS_DATA_MANAGER_PATH . '/assets/media/square_full.png';
$radioUnchecked = CNRS_DATA_MANAGER_PATH . '/assets/media/check_empty.png';
$radioChecked = CNRS_DATA_MANAGER_PATH . '/assets/media/check_full.png';

ob_start();
include CNRS_DATA_MANAGER_PATH . '/templates/includes/pdf/html.php';
$html = ob_get_clean();

$pdf->lastPage();
$pdf->writeHTML($html, true, true, true, false, '');

$expose = $mode === 'print' ? 'I' : 'D';
$pdf->Output($title . '.pdf', $expose);
