<?php

namespace CnrsDataManager\Core\Controllers;

use Error;
use ErrorException;
use JsonException;
use ZipArchive;
use CnrsDataManager\Excel\IOFactory;
use CnrsDataManager\Excel\RichText\RichText;
use CnrsDataManager\Core\Models\Projects;
use CnrsDataManager\Core\Models\Forms;
use CnrsDataManager\Core\Models\Collaborators;
use CnrsDataManager\Core\Controllers\Emails;
use CnrsDataManager\Core\Controllers\Manager;

class Ajax
{
    private static array $adminActions = [
        'check_xml_file' => 'inspectXLSFile',
        'import_xml_file' => 'importXLSFile',
        'set_form_tool' => 'setFormTool',
        'get_form_tool' => 'getFormTool',
        'get_agents_list' => 'getAgentsList',
        'get_forms_list' => 'getFormsList',
        'get_new_manager' => 'getNewManager',
        'form_list_action' => 'formListAction',
        'get_form_toggles' => 'getFormToggles',
        'get_collaborators_list' => 'getCollaboratorsList',
        'get_collaborator_modal' => 'getCollaboratorModal',
        'collaborator_action' => 'collaboratorAction',
        'get_attachments' => 'getAttachments',
        'search_publications' => 'searchPublications'
    ];

    private static array $publicActions = [];

    private static string $timestamp = '';

    private static array $mapper = ["ACRONYME", "INTITULE", "RESPONSABLE_SCIENTIFIQUE", "EQUIPE", "FINANCEUR", "RESUME", "LIEN_SITE", "IMAGE"];

    private static array $btnText = ['fr' => 'Voir le projet', 'en' => 'See project'];
    private static array $optionals = ["INTITULE", "FINANCEUR", "LIEN_SITE", "IMAGE"];

    private static array $formModules = ['input', 'checkbox', 'radio', 'textarea', 'title', 'comment', 'signs', 'number', 'date', 'time', 'datetime', 'toggle'];

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
            if (isset($_FILES['file']) && $_FILES['file']['type'] === 'application/zip' && isset($_POST['data']) && isset($_POST['teams'])) {
                $teams = json_decode(stripslashes($_POST['teams']), true);
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
                    $import = self::importProjectsToDB($data, $dir, $teams);
                    if ($import === false) {
                        $json = ['error' => __('Importing projects into the database failed.', 'cnrs-data-manager'), 'data' => null];
                    }
                    $json['data'] = $import;
                }
            }
        } catch (Error|ErrorException|JsonException $e) {
            $json['error'] = __(/*'The import failed.'*/$e->getMessage(), 'cnrs-data-manager');
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
     * @param array $teams Array of teams Ids.
     * @return bool|string Returns false if any image is missing or a string of the HTML list.
     */
    private static function importProjectsToDB(array $data, string $uploadPath, array $teams): bool|string
    {
        $posts = [];
        foreach ($data as $row) {
            $id = null;
            $translated = [];
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

            $projectFrId = $teams['fr'];

            $wpProjectToDB = [
                'post_author' => get_current_user_id(),
                'post_content' => $row['RESUME'],
                'post_title' => $row['INTITULE'],
                'post_status' => 'publish',
                'post_type' => 'project',
                'comment_status' => 'closed',
                'post_excerpt' => cnrsCreateExcerpt($row['RESUME']),
                'ping_status' => 'closed',
                'meta_input'   => [
                    'cnrs_project_acronym' => $row['ACRONYME'],
                    'cnrs_project_leaders_and_team' => $row['RESPONSABLE_SCIENTIFIQUE'] . '; ' . $row['EQUIPE'],
                    'cnrs_project_link' => $row['LIEN_SITE'] ?? '',
                    'cnrs_project_link_text' => self::$btnText['fr']
                ]
            ];

            if ($id !== null) {
                $wpProjectToDB['_thumbnail_id'] = $id;
            }

            $postId = wp_insert_post($wpProjectToDB);
            Projects::setTeamProjectRelation($postId, $projectFrId, 'fr');

            if (function_exists('pll_set_post_language')) {
                pll_set_post_language($postId, 'fr');
            }

            $recorded = get_post($postId, ARRAY_A);
            $translated['fr'] = $postId;

            $posts[] = [
                'url' => $recorded['guid'],
                'image' => $url,
                'lang' => 'fr',
                'excerpt' => $recorded['post_excerpt'],
                'title' => $recorded['post_title']
            ];

            foreach ($teams as $lang => $teamId) {

                if ($lang !== 'fr') {

                    $wpProjectToDB = [
                        'post_author' => get_current_user_id(),
                        'post_content' => $row['RESUME'],
                        'post_title' => $row['INTITULE'],
                        'post_status' => 'publish',
                        'post_type' => 'project',
                        'comment_status' => 'closed',
                        'post_excerpt' => cnrsCreateExcerpt($row['RESUME']),
                        'ping_status' => 'closed',
                        'meta_input'   => [
                            'cnrs_project_acronym' => $row['ACRONYME'],
                            'cnrs_project_leaders_and_team' => $row['RESPONSABLE_SCIENTIFIQUE'] . '; ' . $row['EQUIPE'],
                            'cnrs_project_link' => $row['LIEN_SITE'] ?? '',
                            'cnrs_project_link_text' => self::$btnText[$lang] ?? self::$btnText['en']
                        ]
                    ];

                    if ($id !== null) {
                        $wpProjectToDB['_thumbnail_id'] = $id;
                    }

                    $postId = wp_insert_post($wpProjectToDB);
                    Projects::setTeamProjectRelation($postId, $teamId, $lang);

                    if (function_exists('pll_set_post_language')) {
                        pll_set_post_language($postId, $lang);
                    }

                    $translated[$lang] = $postId;
                    $recorded = get_post($postId, ARRAY_A);

                    $posts[] = [
                        'url' => $recorded['guid'],
                        'image' => $url,
                        'lang' => $lang,
                        'excerpt' => $recorded['post_excerpt'],
                        'title' => $recorded['post_title']
                    ];

                    if (count($translated) > 1 && function_exists('pll_save_post_translations')) {
                        pll_save_post_translations($translated);
                    }
                }
            }
        }

        ob_start();
        include(CNRS_DATA_MANAGER_PATH . '/templates/includes/import-list.php');
        return ob_get_clean();
    }

    /**
     * Sets the form tool based on the user's selection and sends the updated tool data as a JSON response.
     *
     * @return void
     * @throws JsonException
     */
    public static function setFormTool(): void
    {
        $data = null;
        $html = null;
        $json = ['error' => null, 'data' => $data, 'html' => $html, 'json' => '[]'];
        if (!isset($_POST['tool']) || !isset($_POST['iteration'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            $tool = $_POST['tool'];
            $iteration = (int) $_POST['iteration'];
            try {
                if (in_array($tool, self::$formModules, true) || $tool === 'separator') {
                    $uuid = $tool === 'toggle' ? wp_generate_uuid4() : null;
                    $json['json'] = Manager::formToolsInit($tool, $uuid);
                    ob_start();
                    include(CNRS_DATA_MANAGER_PATH . '/templates/includes/form-tools/tools/' . $tool . '.php');
                    $json['data'] = ob_get_clean();
                    if ($tool !== 'separator') {
                        ob_start();
                        include(CNRS_DATA_MANAGER_PATH . '/templates/includes/form-tools/modals/' . $tool . '.php');
                        $json['html'] = ob_get_clean();
                    }
                }
            } catch (JsonException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves the HTML content of a form tool based on the provided parameters and sends it as a JSON response.
     *
     * @return void
     */
    public static function getFormTool(): void
    {
        $data = null;
        $json = ['error' => null, 'data' => $data];
        if (!isset($_POST['tool']) || !isset($_POST['iteration']) || !isset($_POST['json'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            $tool = $_POST['tool'];
            $iteration = (int) $_POST['iteration'];
            $data = json_decode(stripslashes($_POST['json']), true);
            try {
                if (in_array($tool, self::$formModules, true)) {
                    ob_start();
                    include(CNRS_DATA_MANAGER_PATH . '/templates/includes/form-tools/modals/' . $tool . '.php');
                    $json['data'] = ob_get_clean();
                }
            } catch (JsonException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    public static function getFormToggles(): void
    {
        $data = null;
        $json = ['error' => null, 'data' => $data];
        if (!isset($_POST['label']) || !isset($_POST['iteration']) || !isset($_POST['toggles'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            $label = $_POST['label'];
            $toggles = json_decode(stripslashes($_POST['toggles']), true);
            $iteration = (int) $_POST['iteration'];
            try {
                ob_start();
                include(CNRS_DATA_MANAGER_PATH . '/templates/includes/form-tools/modals/toggles-settings.php');
                $json['data'] = ob_get_clean();
            } catch (JsonException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves the list of agents from an XML file and sends it as a JSON response.
     *
     * @return void
     */
    public static function getAgentsList(): void
    {
        $json = ['error' => null, 'data' => []];
        try {
            $json['data'] = Manager::defineArrayFromXML()['agents'];
        } catch (ErrorException $e) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves the list of forms and sends it as a JSON response.
     *
     * @return void
     */
    public static function getFormsList(): void
    {
        $json = ['error' => null, 'data' => ['total' => 0], 'html' => ''];
        if (!isset($_POST['agents']) || !isset($_POST['page']) || !isset($_POST['search']) || !isset($_POST['results_per_page'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            try {
                $agents = json_decode(stripslashes($_POST['agents']), true);
                $current = ctype_digit((string) $_POST['page']) ? (int) $_POST['page'] : 1;
                $search = trim(html_entity_decode($_POST['search']));
                $limit = (int) $_POST['results_per_page'];
                $status = $_POST['status_filter'];
                $orderBy = $_POST['date_order_by'];
                $count = Forms::getFormsCount($search, $limit, $current, $status);
                $pages = 0;
                $rows = [];
                $previous = null;
                $next = null;
                if ($count > 0 && $limit > 0) {
                    $pages = $count / $limit < 1 ? 1 : ceil($count / $limit);
                    if ($current < 1) {
                        $current = 1;
                    } else if ($current > $pages) {
                        $current = $pages;
                    }
                    $rows = Forms::getPaginatedFormsList($search, $limit, $current, $status, $orderBy);
                    $previous = $current > 1 ? $current - 1 : null;
                    $next = $current < $pages ? $current + 1 : null;
                }
                ob_start();
                include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/mission-form-list.php');
                $json['html'] = ob_get_clean();
                $json['data'] = ['total' => $count];
            } catch (ErrorException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves the HTML content for the new manager form and sends it as a JSON response.
     *
     * @return void
     */
    public static function getNewManager(): void
    {
        $json = ['error' => null, 'data' => []];
        if (!isset($_POST['iteration'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            try {
                $iteration = (int) $_POST['iteration'];
                ob_start();
                include(CNRS_DATA_MANAGER_PATH . '/templates/includes/new-manager.php');
                $json['data'] = ob_get_clean();
            } catch (ErrorException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Updates the status of a form based on the trigger and form ID provided in the $_POST data,
     * and sends the updated status as a JSON response.
     *
     * @return void
     */
    public static function formListAction(): void
    {
        $json = ['error' => null, 'data' => 'ko'];
        if (!isset($_POST['trigger']) || !isset($_POST['form_id'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            $state = true;
            try {
                if ($_POST['trigger'] === 'abandon') {
                    $email = Forms::setAbandonForm((int) $_POST['form_id']);
                    $state = Emails::sendAbandonForm($email);
                } else if ($_POST['trigger'] === 'validate') {
                    $data = Forms::setPendingForm((int) $_POST['form_id']);
                    $state = Emails::sendToManager($data['email'], $data['uuid']);
                }
                $json['data'] = $state === true ? 'ok' : 'ko';
                $json['error'] = $state === false ? __('An error as occurred.', 'cnrs-data-manager') : null;
            } catch (ErrorException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves the list of collaborators and sends it as a JSON response.
     *
     * @return void
     */
    public static function getCollaboratorsList(): void
    {
        $json = ['error' => null, 'data' => ['total' => 0], 'html' => ''];
        if (!isset($_POST['page']) || !isset($_POST['search']) || !isset($_POST['results_per_page'])) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        } else {
            try {
                $current = ctype_digit((string) $_POST['page']) ? (int) $_POST['page'] : 1;
                $search = trim(html_entity_decode($_POST['search']));
                $limit = (int) $_POST['results_per_page'];
                $entityType = $_POST['type_filter'];
                $orderBy = $_POST['date_order_by'];
                $count = Collaborators::getCollaboratorsCount($search, $limit, $current, $entityType);
                $pages = 0;
                $rows = [];
                $previous = null;
                $next = null;
                if ($count > 0 && $limit > 0) {
                    $pages = $count / $limit < 1 ? 1 : ceil($count / $limit);
                    if ($current < 1) {
                        $current = 1;
                    } else if ($current > $pages) {
                        $current = $pages;
                    }
                    $rows = Collaborators::getPaginatedCollaboratorsList($search, $limit, $current, $entityType, $orderBy);
                    $previous = $current > 1 ? $current - 1 : null;
                    $next = $current < $pages ? $current + 1 : null;
                }
                ob_start();
                include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/collaborators-list.php');
                $json['html'] = ob_get_clean();
                $json['data'] = ['total' => $count];
            } catch (ErrorException $e) {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves the collaborator modal HTML and sends it as a JSON response.
     *
     * @return void
     */
    public static function getCollaboratorModal(): void
    {
        $json = ['error' => null, 'data' => null];
        try {
            $data = null;
            ob_start();
            include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/collaborator-modal.php');
            $json['html'] = ob_get_clean();
        } catch (ErrorException $e) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        }
        wp_send_json_success($json);
        exit;
    }

    public static function collaboratorAction(): void
    {
        $json = ['error' => null, 'data' => null];
        try {
            if ($_POST['trigger'] === 'edit') {
                $data = Collaborators::getCollaboratorById((int) $_POST['id']);
                ob_start();
                include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/collaborator-modal.php');
                $json['html'] = ob_get_clean();
            } else {
                Collaborators::deleteCollaboratorById((int) $_POST['id']);
            }
        } catch (ErrorException $e) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Retrieves all image attachments and sends them as a JSON response.
     *
     * @return void
     */
    public static function getAttachments(): void
    {
        $json = ['error' => null, 'data' => null, 'html' => ''];
        try {
            if (isset($_POST['project_id'])) {
                $args = array(
                    'post_type' => 'attachment',
                    'numberposts' => -1,
                    'post_mime_type' => 'image',
                    'post_status' => null,
                    'post_parent' => null,
                );
                $projectId = (int) $_POST['project_id'];
                $imagesFromProject = Projects::getImagesFromProject($projectId);
                $images = get_posts($args);
                ob_start();
                include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/attachments-modal.php');
                $json['html'] = ob_get_clean();
                $json['data'] = $images;
            } else {
                $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
            }
        } catch (ErrorException $e) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        }
        wp_send_json_success($json);
        exit;
    }

    /**
     * Return filtered publications.
     *
     * @return void
     */
    public static function searchPublications(): void
    {
        $json = ['error' => null, 'data' => null];
        try {
            $oskar = Manager::getPublications();
            $formatted = cnrsFormatPublications($oskar);
            $publications = cnrsApplyFilters($formatted['publications'], $_POST);
            $totalCount = count($formatted['publications']);
            $filteredCount = count($publications);
            ob_start();
            include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/publications.php');
            $json['data'] = ob_get_clean();
        } catch (ErrorException $e) {
            $json['error'] = __('An error as occurred.', 'cnrs-data-manager');
        }
        wp_send_json_success($json);
        exit;
    }
}
