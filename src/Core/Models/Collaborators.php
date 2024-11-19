<?php

namespace CnrsDataManager\Core\Models;

class Collaborators
{
    /**
     * Retrieves the number of collaborators based on the given search criteria.
     *
     * @param string $search The search string to filter the collaborators by.
     * @param int $limit The maximum number of collaborators to retrieve per page.
     * @param int $current The current page number.
     * @param string $type The type of collaborators to retrieve ("ALL" for all types).
     * @return int The total number of collaborators that match the search criteria.
     */
    public static function getCollaboratorsCount(string $search, int $limit, int $current, string $type): int
    {
        global $wpdb;
        $where = strlen($search) > 0 ? "WHERE entity_name LIKE '%{$search}%'" : '';
        if (strlen($where) > 0) {
            $where = $type !== 'ALL' ? "{$where} AND entity_type='{$type}'" : $where;
        } else {
            $where = $type !== 'ALL' ? "WHERE entity_type='{$type}'" : '';
        }
        $offset = ($current*$limit) - $limit;
        $count = $wpdb->get_row("SELECT COUNT(*) as nb FROM {$wpdb->prefix}cnrs_data_manager_project_entities {$where}");
        return $count->nb;
    }

    /**
     * Retrieves a paginated list of collaborators based on the given search criteria.
     *
     * @param string $search The search string to filter the collaborators by.
     * @param int $limit The maximum number of collaborators to retrieve per page.
     * @param int $current The current page number.
     * @param string $type The type of collaborators to retrieve ("ALL" for all types).
     * @param string $orderBy The sorting order of the collaborators (optional, default is "DESC").
     * @return array Returns an array of collaborator data that matches the search criteria.
     */
    public static function getPaginatedCollaboratorsList(string $search, int $limit, int $current, string $type, string $orderBy = 'DESC'): array
    {
        global $wpdb;
        $where = strlen($search) > 0 ? "WHERE entity_name LIKE '%{$search}%'" : '';
        if (strlen($where) > 0) {
            $where = $type !== 'ALL' ? "{$where} AND entity_type='{$type}'" : $where;
        } else {
            $where = $type !== 'ALL' ? "WHERE entity_type='{$type}'" : '';
        }
        $offset = ($current*$limit) - $limit;

        $results = $wpdb->get_results("SELECT id, entity_logo, entity_name, entity_type FROM {$wpdb->prefix}cnrs_data_manager_project_entities {$where} ORDER BY id {$orderBy} LIMIT {$offset}, {$limit}", ARRAY_A);

        foreach ($results as $index => $result) {
            if ($result['entity_logo'] !== null) {
                $results[$index]['entity_logo'] = wp_get_attachment_url((int) $result['entity_logo']);
            }
        }
        return $results;
    }

    /**
     * Updates or creates a collaborator based on the submitted form data.
     *
     * @return void
     */
    public static function updateOrCreate(): void
    {
        if (isset($_POST['cnrs-data-manager-collaborator-name'])
            && isset($_POST['cnrs-data-manager-collaborator-type'])
            && isset($_POST['cnrs-data-manager-collaborator-logo-hidden'])
        ) {
            $mediaID = null;
            if ($_POST['cnrs-data-manager-collaborator-logo-hidden'] === 'loaded'
                && $_FILES['cnrs-data-manager-collaborator-logo']['error'] === 0) {
                $mediaID = media_handle_upload('cnrs-data-manager-collaborator-logo', 0);
            }
            global $wpdb;
            if (isset($_POST['cnrs-data-manager-collaborator-id'])) {
                $update = [
                    'entity_type' => stripslashes($_POST['cnrs-data-manager-collaborator-type']),
                    'entity_name' => stripslashes($_POST['cnrs-data-manager-collaborator-name'])
                ];
                if ($mediaID !== null) {
                    $attachment = $wpdb->get_row("SELECT entity_logo FROM {$wpdb->prefix}cnrs_data_manager_project_entities WHERE id = {$_POST['cnrs-data-manager-collaborator-id']}");
                    if ($attachment->entity_logo !== null) {
                        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE ID = {$attachment->entity_logo}");
                    }
                    $update['entity_logo'] = $mediaID;
                }
                if ($_POST['cnrs-data-manager-collaborator-logo-hidden'] === 'none') {
                    $attachment = $wpdb->get_row("SELECT entity_logo FROM {$wpdb->prefix}cnrs_data_manager_project_entities WHERE id = {$_POST['cnrs-data-manager-collaborator-id']}");
                    if ($attachment->entity_logo !== null) {
                        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE ID = {$attachment->entity_logo}");
                    }
                    $update['entity_logo'] = null;
                }
                $wpdb->update(
                    "{$wpdb->prefix}cnrs_data_manager_project_entities",
                    $update,
                    array(
                        'id' => (int) $_POST['cnrs-data-manager-collaborator-id']
                    )
                );
            } else {
                $wpdb->insert(
                    "{$wpdb->prefix}cnrs_data_manager_project_entities",
                    array(
                        'entity_type' => stripslashes($_POST['cnrs-data-manager-collaborator-type']),
                        'entity_name' => stripslashes($_POST['cnrs-data-manager-collaborator-name']),
                        'entity_logo' => $mediaID
                    )
                );
            }
        }
    }

    /**
     * Retrieves the collaborator with the specified ID.
     *
     * @param int $id The ID of the collaborator to retrieve.
     * @return array|null The details of the collaborator as an associative array, or null if no collaborator is found with the given ID.
     */
    public static function getCollaboratorById(int $id): array|null
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT id, entity_logo, entity_name, entity_type FROM {$wpdb->prefix}cnrs_data_manager_project_entities WHERE id = {$id}");
        if ($row === null) {
            return ['toto' => $row];
        }
        $row = (array) $row;
        if ($row['entity_logo'] !== null) {
            $row['entity_logo'] = wp_get_attachment_url((int) $row['entity_logo']);
        }
        return $row;
    }

    /**
     * Deletes a collaborator based on the given ID.
     *
     * @param int $id The ID of the collaborator to delete.
     * @return void
     */
    public static function deleteCollaboratorById(int $id): void
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT entity_logo FROM {$wpdb->prefix}cnrs_data_manager_project_entities WHERE id = {$id}");
        if ($row !== null && $row->entity_logo !== null) {
            wp_delete_attachment($row->entity_logo);
        }
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_entity_relation WHERE project_id = {$id}");
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_entities WHERE id = {$id}");
    }

    /**
     * Retrieves the collaborators associated with the given project ID.
     *
     * @param int $projectId The ID of the project to retrieve collaborators from.
     * @return array An array of collaborators, where each collaborator is represented as an associative array with keys representing the columns of the cnrs_data_manager_project_entities table.
     */
    public static function getCollaboratorsFromProjectId(int $projectId): array
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT {$wpdb->prefix}cnrs_data_manager_project_entities.* FROM {$wpdb->prefix}cnrs_data_manager_project_entities INNER JOIN {$wpdb->prefix}cnrs_data_manager_project_entity_relation ON {$wpdb->prefix}cnrs_data_manager_project_entities.id = {$wpdb->prefix}cnrs_data_manager_project_entity_relation.entity_id WHERE {$wpdb->prefix}cnrs_data_manager_project_entity_relation.project_id = {$projectId}", ARRAY_A);
        $split = ['funders' => [], 'partners' => []];
        foreach ($results as $result) {
            if ($result['entity_type'] === 'FUNDER') {
                $split['funders'][] = $result;
            } else if ($result['entity_type'] === 'PARTNER') {
                $split['partners'][] = $result;
            }
        }
        return $split;
    }

    /**
     * Retrieves the list of collaborators, split into funders and partners.
     *
     * @return array An associative array containing two sub-arrays: 'funders' and 'partners'.
     *     Each sub-array contains the details of the respective type of collaborators.
     *     The 'entity_logo' key in each collaborator's details contains a URL to the corresponding logo.
     */
    public static function getCollaborators(): array
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_project_entities", ARRAY_A);
        $split = ['funders' => [], 'partners' => []];
        foreach ($results as $result) {
            if ($result['entity_logo'] !== null) {
                $result['entity_logo'] = wp_get_attachment_url((int) $result['entity_logo']);
            }
            if ($result['entity_type'] === 'FUNDER') {
                $split['funders'][] = $result;
            } else if ($result['entity_type'] === 'PARTNER') {
                $split['partners'][] = $result;
            }
        }
        return $split;
    }

    /**
     * Save the project-entity relations based on the provided form data.
     *
     * @return void
     */
    public static function saveRelations(): void
    {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_entity_relation");
        foreach ($_POST['cnrs-dm-collaborators'] as $projectId => $collabs) {
            if (isset($collabs['funders'])) {
                foreach ($collabs['funders'] as $funderID) {
                    $wpdb->insert(
                        "{$wpdb->prefix}cnrs_data_manager_project_entity_relation",
                        array(
                            'project_id' => (int) $projectId,
                            'entity_id' => (int) $funderID
                        )
                    );
                }
            }
            if (isset($collabs['partners'])) {
                foreach ($collabs['partners'] as $partnerID) {
                    $wpdb->insert(
                        "{$wpdb->prefix}cnrs_data_manager_project_entity_relation",
                        array(
                            'project_id' => (int) $projectId,
                            'entity_id' => (int) $partnerID
                        )
                    );
                }
            }
        }
    }

    public static function importCollabs(): void
    {
        $logos = CNRS_DATA_MANAGER_PATH . '/financeurs/logos/';
        $list = CNRS_DATA_MANAGER_PATH . '/financeurs/list.php';

        if (file_exists($list) && file_exists($logos)) {
            $list = include_once($list);
            foreach ($list as $index => $collab) {
                $row = [
                    'entity_type' => 'FUNDER',
                    'entity_name' => $collab['name'],
                    'entity_logo' => $collab['logo'] !== null && file_exists($logos . $collab['logo']) ? $logos . $collab['logo'] : null
                ];
                if ($row['entity_logo'] !== null) {
                    $url = wp_upload_dir()['url'] . DIRECTORY_SEPARATOR . $collab['logo'];
                    $path = wp_upload_dir()['path'] . DIRECTORY_SEPARATOR . $collab['logo'];
                    copy($row['entity_logo'], $path);
                    $fileName = str_replace(['.jpg', '.jpeg', '.png', '.gif'], '', $collab['logo']);
                    $wpImageToDB = [
                        'guid' => $url,
                        'post_mime_type' => mime_content_type($row['entity_logo']),
                        'post_title' => $fileName,
                        'post_name' => $fileName,
                        'post_status' => 'inherit',
                    ];
                    $id = wp_insert_attachment($wpImageToDB, $path, 0);
                    $attach_data = wp_generate_attachment_metadata($id, $path);
                    wp_update_attachment_metadata($id, $attach_data);
                    $row['entity_logo'] = $id;
                }
                global $wpdb;
                $wpdb->insert(
                    "{$wpdb->prefix}cnrs_data_manager_project_entities",
                    $row,
                    array('%s', '%s', '%d')
                );
            }
        }
    }

    public static function setFundersProjectRelation(int $postId, array $funders): void
    {
        global $wpdb;
        foreach ($funders as $funder) {
            $wpdb->insert(
                "{$wpdb->prefix}cnrs_data_manager_project_entity_relation",
                array(
                    'project_id' => $postId,
                    'entity_id' => (int) $funder
                ),
                array('%d', '%d')
            );
        }
    }
}
