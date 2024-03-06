<?php

namespace CnrsDataManager\Core\Controllers;

use JsonException;
use ZipArchive;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use CnrsDataManager\Core\Models\Projects;

class Ajax
{
    private static array $adminActions = [
        'check_xml_file' => 'inspectXLSFile',
        'import_xml_file' => 'importXLSFile'
    ];

    private static array $publicActions = [];

    private static string $timestamp = '';

    private static array $mapper = ["ACRONYME", "INTITULE", "RESPONSABLE_SCIENTIFIQUE", "EQUIPE", "FINANCEUR", "RESUME", "LIEN_SITE", "IMAGE"];

    private static array $optionals = ["INTITULE", "FINANCEUR", "LIEN_SITE", "IMAGE"];

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
        $error = __('Bad file type. Only <b>.zip</b> file is allowed.', 'cnrs-data-manager');
        ob_start();
        include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-result.php');
        $html = ob_get_clean();
        $response = ['error' => $error, 'data' => null, 'html' => $html];
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
                        $error = __('There is more than one Excel file in the zip archive. The archive must contain a single Excel file.', 'cnrs-data-manager');
                        ob_start();
                        include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-result.php');
                        $html = ob_get_clean();
                        return ['error' => $error, 'data' => null, 'html' => $html];
                    }
                    $fileToExtract = $zip->getFromName($filename);
                    $excel = self::moveFileAndTransformToArray($fileinfo, $fileToExtract);
                } else if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                    $images[] = $fileinfo['basename'];
                }
            }
            if (is_bool($excel) && $excel === false) {
                $error = __('The Excel file does not have the expected structure.', 'cnrs-data-manager');
                ob_start();
                include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-result.php');
                $html = ob_get_clean();
                return ['error' => $error, 'data' => null, 'html' => $html];
            }
            if (is_array($excel) && empty($excel)) {
                $error = __('No Excel file found in the zip archive.', 'cnrs-data-manager');
                ob_start();
                include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-result.php');
                $html = ob_get_clean();
                return ['error' => $error, 'data' => null, 'html' => $html];
            }
            if (self::checkImagesIntegrity($images, $excel) === false) {
                $error = __('There are photos missing from the zip file.', 'cnrs-data-manager');
                ob_start();
                include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-result.php');
                $html = ob_get_clean();
                return ['error' => $error, 'data' => null, 'html' => $html];
            }
            $zip->close();
        }
        $error = null;
        ob_start();
        include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-result.php');
        $html = ob_get_clean();
        return ['error' => $error, 'data' => $excel, 'html' => $html];
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
     * @return array|bool The transformed array.
     */
    private static function moveFileAndTransformToArray(array $fileinfo, string $fileToExtract): array|bool
    {
        rrmdir(CNRS_DATA_MANAGER_IMPORT_TMP_PATH);
        @mkdir(CNRS_DATA_MANAGER_IMPORT_TMP_PATH, 0755);
        file_put_contents(CNRS_DATA_MANAGER_IMPORT_TMP_PATH . '/' . $fileinfo['basename'], $fileToExtract);
        $spreadsheet = IOFactory::load(CNRS_DATA_MANAGER_IMPORT_TMP_PATH . '/' . $fileinfo['basename']);
        $worksheet = $spreadsheet->getActiveSheet();
        $array = [];
        $cnt = 0;
        foreach ($worksheet->getRowIterator() as $row) {
            if ($cnt > 0) {
                $rowArray = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(TRUE);
                $deadCellsCnt = 0;
                $cellsCnt = 0;
                foreach ($cellIterator as $cell) {
                    if ($cell->getValue() === null && !in_array(self::$mapper[$cellsCnt], self::$optionals, true)) {
                        $deadCellsCnt++;
                    }
                    $value = $cell->getValue();
                    $richText = '';
                    if ($value instanceof RichText) {
                        foreach ($value->getRichTextElements() as $richTextElement) {
                            $richText .= $richTextElement->getText();
                        }
                    }
                    $value = strlen($richText) < 1 ? $value : $richText;
                    $rowArray[self::$mapper[$cellsCnt]] = $value;
                    $cellsCnt++;
                }
                if ($deadCellsCnt <= 2) {
                    $array[] = $rowArray;
                }
            } else {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(TRUE);
                foreach ($cellIterator as $cell) {
                    if (!in_array($cell->getValue(), self::$mapper, true)) {
                        return false;
                    }
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
            if ($row['IMAGE'] !== null) {
                $xlsImages[] = $row['IMAGE'];
            }
        }
        $xlsImages = array_unique($xlsImages);
        $xlsImages = array_values($xlsImages);
        $images = array_unique($images);
        $images = array_values($images);
        $diff = array_diff($xlsImages, $images);
        return count($diff) === 0;
    }

    /**
     * Imports an XLS file and processes the data.
     *
     * @return void
     * @throws JsonException
     */
    public static function importXLSFile(): void
    {
        $json = ['error' => null, 'data' => null];
        try {
            if (isset($_FILES['file']) && $_FILES['file']['type'] === 'application/zip' && isset($_POST['data']) && isset($_POST['team'])) {
                $team = (int) $_POST['team'];
                $strip = str_replace('\\n', '<br>', $_POST['data']);
                $strip = str_replace('\\', '', $strip);
                $data = json_decode($strip, true, 512, JSON_THROW_ON_ERROR);
                $path = wp_upload_dir()['path'];
                $dir = $path . '/';
                $moved = self::importImagesInTmpDir($dir);
                if ($moved === false) {
                    $json['error'] = __('The images could not be processed.', 'cnrs-data-manager');
                } else {
                    $json['data'] = __('The projects were imported successfully.', 'cnrs-data-manager');
                    $import = self::importProjectsToDB($data, $dir, $team);
                    if ($import === false) {
                        $json = ['error' => __('Importing projects into the database failed.', 'cnrs-data-manager'), 'data' => null];
                    }
                    $json['data'] = $import;
                }
            }
        } catch (JsonException $e) {
            $json['error'] = __('The import failed.', 'cnrs-data-manager');
        }
        wp_send_json_success($json);
        exit;
    }


    /**
     * Imports images from a ZIP file into a temporary directory.
     *
     * @param string $dir The path of the temporary directory to store the extracted images.
     * @return bool Returns true if the ZIP file was opened successfully and the images were extracted,
     *              or false if there was an error opening the ZIP file.
     */
    private static function importImagesInTmpDir(string $dir): bool
    {
        $zip = new ZipArchive;
        $path = $_FILES['file']['tmp_name'];
        $test = $zip->open($path);
        self::$timestamp = date("YmdHis");
        if ($test === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $fileinfo = pathinfo($filename);
                $ext = $fileinfo['extension'] ?? null;
                if (in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
                    $fileToExtract = $zip->getFromName($filename);
                    file_put_contents($dir . self::$timestamp . '-' . $fileinfo['basename'], $fileToExtract);
                }
            }
            $zip->close();
        }
        return $test;
    }

    /**
     * Imports projects into the database with their associated images.
     *
     * @param array $data The data of the projects to be imported.
     * @param string $uploadPath The path where the images are uploaded.
     * @return bool|string Returns false if any image is missing or a string of the HTML list.
     */
    private static function importProjectsToDB(array $data, string $uploadPath, int $teamId): bool|string
    {
        $posts = [];
        foreach ($data as $row) {
            $id = null;
            $url = site_url() . '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg';
            if ($row['IMAGE'] !== null) {
                $imagePath = $uploadPath . self::$timestamp . '-' . $row['IMAGE'];
                if (!file_exists($imagePath)) {
                    return false;
                }
                $imageData = exif_read_data($imagePath);
                $url = site_url() . '/wp-content' . explode('wp-content', $imagePath)[1];
                $fileName = str_replace(['.jpg', '.jpeg', '.png'], '', $imageData['FileName']);
                $wpImageToDB = [
                    'guid' => $url,
                    'post_mime_type' => $imageData['MimeType'],
                    'post_title' => $fileName,
                    'post_name' => $fileName,
                    'post_status' => 'inherit',
                ];
                $id = wp_insert_attachment($wpImageToDB, $imagePath, 0);
                $attach_data = wp_generate_attachment_metadata($id, $imagePath);
                wp_update_attachment_metadata($id, $attach_data);
            }
            $wpProjectToDB = [
                'post_author' => get_current_user_id(),
                'post_content' => self::preparePostContent($row),
                'post_title' => $row['ACRONYME'],
                'post_status' => 'publish',
                'post_type' => 'project',
                'comment_status' => 'open',
                'ping_status' => 'closed'
            ];
            if ($id !== null) {
                $wpProjectToDB['_thumbnail_id'] = $id;
            }
            $postId = wp_insert_post($wpProjectToDB);
            Projects::setTeamProjectRelation($postId, $teamId);
            $recorded = get_post($postId, ARRAY_A);
            $posts[] = [
                'url' => $recorded['guid'],
                'image' => $url,
                'excerpt' => $row['INTITULE'],
                'title' => $recorded['post_title']
            ];
        }
        ob_start();
        include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-list.php');
        return ob_get_clean();
    }

    /**
     * Prepare the post content based on the given data.
     *
     * @param array $data The data used to generate the post content.
     * @return string The prepared post content.
     */
    private static function preparePostContent(array $data): string
    {
        $content = '';
        if ($data['INTITULE'] !== null) {
            $content = "<h4>{$data['INTITULE']}</h4>" . "\n";
        }
        if ($data['RESUME'] !== null) {
            $content .= $data['RESUME'] . "\n";
            $content .= "&nbsp;" . "\n";
        }
        $content .= "<h6><em>{$data['RESPONSABLE_SCIENTIFIQUE']}";
        if ($data['EQUIPE'] !== null) {
            $content .= ", {$data['EQUIPE']}";
        }
        if ($data['FINANCEUR'] !== null) {
            $finance = __('financier', 'cnrs-data-manager');
            $content .= ", {$finance} {$data['FINANCEUR']}";
        }
        $content .= "</em></h6>";
        if ($data['LIEN_SITE'] !== null) {
            $content .= "&nbsp;" . "\n";
            $content .= `<a href="{$data['LIEN_SITE']}" target="_blank">{$data['LIEN_SITE']}</a>`;
        }
        return str_replace('<br>', "\n", $content);
    }
}