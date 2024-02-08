<?php

namespace CnrsDataManager\Core\Controllers;

use ZipArchive;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Ajax
{
    private static array $adminActions = [
        'check_xml_file' => 'inspectXLSFile'
    ];

    private static array $publicActions = [];

    /**
     * Registers hooks for AJAX actions in WordPress.
     *
     * This method registers hooks for both admin and public AJAX actions. It loops through the
     * predefined list of admin actions and public actions, and adds appropriate WordPress
     * action hooks for each of them.
     *
     * @return void
     */
    public static function registerHooks(): void
    {
        foreach (self::$adminActions as $adminHook => $adminAction) {
            add_action("wp_ajax_{$adminHook}", array(__CLASS__, $adminAction));
        }

        foreach (self::$publicActions as $publicHook => $publicAction) {
            add_action("wp_ajax_nopriv_{$publicHook}", array(__CLASS__, $publicAction));
        }
    }

    /**
     * Inspects the uploaded XLS file and sends the analysis result as a JSON response.
     *
     * @return void
     */
    public static function inspectXLSFile(): void
    {
        $response = ['error' => __('Bad file type. Only <b>.zip</b> file is allowed.', 'cnrs-data-manager'), 'data' => null];
        if (isset($_FILES['file']) && $_FILES['file']['type'] === 'application/zip') {
            $response = self::analyseFiles($_FILES['file']['tmp_name']);
        }
        wp_send_json_success($response);
        exit;
    }

    /**
     * Analyzes the files in a given zip archive and returns the analysis result as an array.
     *
     * @param string $path The path to the zip archive.
     * @return array The analysis result as an array. If there is an error, the 'error' key will be populated with the error message.
     *               If the analysis is successful, the 'data' key will be populated with the Excel file data.
     */
    private static function analyseFiles(string $path): array
    {
        $excel = null;
        $images = [];
        $xlsFiles = 0;
        $zip = new ZipArchive;
        if ($zip->open($path) === true) {
            for($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fileinfo = pathinfo($filename);
                $ext = $fileinfo['extension'] ?? null;
                if (in_array($ext, ['xls', 'xlsx'], true)) {
                    $xlsFiles++;
                    if ($xlsFiles > 1) {
                        return ['error' => __('There is more than one Excel file in the zip archive. The archive must contain a single Excel file.', 'cnrs-data-manager'), 'data' => null];
                    }
                    $fileToExtract = $zip->getFromName($filename);
                    $excel = self::moveFileAndTransformToArray($fileinfo, $fileToExtract);
                } else if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                    $images[] = $fileinfo['basename'];
                }
            }
            if (self::checkImagesIntegrity($images, $excel) === false) {
                return ['error' => __('There are photos missing from the zip file.', 'cnrs-data-manager'), 'data' => null];
            }
            $zip->close();
        }
        return ['error' => null, 'data' => $excel];
    }

    /**
     * Moves a file to a specified directory, extracts its contents, and transforms them into an array.
     *
     * This method moves a file to the specified directory, deletes the directory if it already exists, creates a new directory,
     * and saves the contents of the file in the directory. It then loads the spreadsheet from the file, gets the active worksheet,
     * and iterates through each row. For each row, it retrieves the values of the cells and maps them to the corresponding keys
     * in the array using a predefined mapper. If a row has less than 7 non-null cells, it adds the row array to the result array.
     * Finally, it deletes the directory and returns the result array.
     *
     * @param array $fileinfo An array containing information about the file.
     * @param string $fileToExtract The contents of the file to be extracted.
     * @return array The transformed array.
     */
    private static function moveFileAndTransformToArray(array $fileinfo, string $fileToExtract): array
    {
        rrmdir(CNRS_DATA_MANAGER_IMPORT_TMP_PATH);
        @mkdir(CNRS_DATA_MANAGER_IMPORT_TMP_PATH, 0755);
        file_put_contents(CNRS_DATA_MANAGER_IMPORT_TMP_PATH . '/' . $fileinfo['basename'], $fileToExtract);
        $spreadsheet = IOFactory::load(CNRS_DATA_MANAGER_IMPORT_TMP_PATH . '/' . $fileinfo['basename']);
        $worksheet = $spreadsheet->getActiveSheet();
        $array = [];
        $mapper = ["TITRE", "ACRONYME", "RESUME", "CONTENU", "RESPONSABLE", "EQUIPE", "PORTEUR", "FINANCEUR", "PHOTO"];
        $cnt = 0;
        foreach ($worksheet->getRowIterator() as $row) {
            if ($cnt > 0) {
                $rowArray = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(TRUE);
                $deadCellsCnt = 0;
                $cellsCnt = 0;
                foreach ($cellIterator as $cell) {
                    if ($cell->getValue() === null) {
                        $deadCellsCnt++;
                    }
                    $rowArray[$mapper[$cellsCnt]] = $cell->getValue();
                    $cellsCnt++;
                }
                if ($deadCellsCnt < 7) {
                    $array[] = $rowArray;
                }
            }
            $cnt++;
        }
        rrmdir(CNRS_DATA_MANAGER_IMPORT_TMP_PATH);
        return $array;
    }

    /**
     * Checks the integrity of the images uploaded against the images listed in the Excel file.
     *
     * @param array $images The array of uploaded images.
     * @param array $excel The array representing the Excel data.
     * @return bool True if the images uploaded match the images listed in the Excel file, false otherwise.
     */
    private static function checkImagesIntegrity(array $images, array $excel): bool
    {
        $xlsImages = [];
        foreach ($excel as $row) {
            $xlsImages[] = $row['PHOTO'];
        }
        $xlsImages = array_unique($xlsImages);
        $xlsImages = array_values($xlsImages);
        $images = array_unique($images);
        $images = array_values($images);
        $diff = array_diff($xlsImages, $images);
        return count($diff) === 0;
    }
}