<?php

namespace CnrsDataManager\Core\Models;

class Projects
{
    /**
     * Sets the relation between a team and a project.
     *
     * @param int $postID The ID of the project.
     * @param int $teamID The ID of the team.
     * @param string $lang
     * @return void
     */
    public static function setTeamProjectRelation(int $postID, int $teamID, string $lang): void
    {
        global $wpdb;
        $insert = [
            'team_id' => $teamID,
            'project_id' => $postID,
            'lang' => $lang,
        ];

        $wpdb->insert($wpdb->prefix . 'cnrs_data_manager_team_project', $insert, ['%d', '%d', '%s']);
    }

    /**
     * Cleans up ghost projects from the team project table.
     *
     * @return void
     */
    public static function cleanGhostProjects(): void
    {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_team_project WHERE project_id NOT IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'project')");
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_entity_relation WHERE project_id NOT IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'project')");
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_attachment_relation WHERE project_id NOT IN (SELECT ID FROM {$wpdb->prefix}posts WHERE post_type = 'project')");
        $projects = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_project_attachment_relation", ARRAY_A);
        foreach ($projects as $project) {
            $attachment = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE ID = {$project['attachment_id']} AND post_type = 'attachment'");
            if ($attachment === null) {
                $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_attachment_relation WHERE attachment_id = {$project['attachment_id']}");
            }
        }
        $collaborators = $wpdb->get_results("SELECT id, entity_logo FROM {$wpdb->prefix}cnrs_data_manager_project_entities", ARRAY_A);
        foreach ($collaborators as $collaborator) {
            if ($collaborator['entity_logo'] !== null) {
                $attachment = $wpdb->get_row("SELECT ID FROM {$wpdb->prefix}posts WHERE ID = {$collaborator['entity_logo']} AND post_type = 'attachment'");
                if ($attachment === null) {
                    $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_project_entities SET entity_logo = null WHERE id = {$collaborator['id']}");
                }
            }
        }
    }

    /**
     * Retrieves all projects from the database.
     *
     * @return array The array containing all projects.
     */
    public static function getProjects(): array
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_team_project", ARRAY_A);
    }

    /**
     * Updates the relationships between teams and projects.
     *
     * @param array $inserts The array containing the data for inserting the relationships.
     *                      Each element should be an associative array with the keys 'team_id',
     *                      'project_id', and optionally 'display_order'.
     * @return void
     */
    public static function updateProjectsRelations(array $inserts): void
    {
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_team_project");
        foreach ($inserts as $insert) {
            if ($insert['display_order'] === null) {
                $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_team_project (team_id, project_id, lang) VALUES ({$insert['team_id']}, {$insert['project_id']}, '{$insert['lang']}')");
            } else {
                $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_team_project (team_id, project_id, display_order, lang) VALUES ({$insert['team_id']}, {$insert['project_id']}, {$insert['display_order']}, '{$insert['lang']}')");
            }
        }
    }

    /**
     * Retrieves the projects associated with a team.
     *
     * @param int $teamID The ID of the team.
     * @return array An array of project IDs associated with the team.
     */
    public static function getProjectsForTeam(int $teamID): array
    {
        global $wpdb;
        return $wpdb->get_results("SELECT ID as id, post_title as title, guid as url FROM {$wpdb->prefix}cnrs_data_manager_team_project INNER JOIN {$wpdb->prefix}cnrs_data_manager_relations ON team_id = xml_entity_id INNER JOIN {$wpdb->prefix}posts ON ID = project_id WHERE type = 'teams' AND term_id = {$teamID} ORDER BY display_order ASC", ARRAY_A);
    }

    /**
     * Retrieves all the IDs of the projects that are forbidden for a specific team.
     *
     * @param int $teamID The ID of the team.
     * @return array The array of forbidden project IDs.
     */
    public static function getAllForbiddenProjectsIDs(int $teamID): array
    {
        global $wpdb;
        $ids = $wpdb->get_col("SELECT project_id FROM {$wpdb->prefix}cnrs_data_manager_team_project WHERE team_id = {$teamID}");
        return array_map(function ($id) {return (int) $id;}, $ids);
    }

    /**
     * Retrieves the images related to a specific project.
     *
     * @param int $projectId The ID of the project.
     * @return array The array containing the attachment IDs of the images.
     */
    public static function getImagesFromProject(int $projectId): array
    {
        global $wpdb;
        $ids = $wpdb->get_col("SELECT attachment_id FROM {$wpdb->prefix}cnrs_data_manager_project_attachment_relation WHERE project_id = {$projectId}");
        return array_map(function ($id) {return (int) $id;}, $ids);
    }

    /**
     * Saves project images to the database.
     *
     * This method checks if the 'cnrs-data-manager-project-images' parameter is set in the $_POST array.
     * If it is set, it deletes all existing project attachment relations from the database table `{$wpdb->prefix}cnrs_data_manager_project_attachment_relation`.
     * Then, it loops through the $_POST['cnrs-data-manager-project-images'] array to extract the project ID and the image IDs associated with each project.
     * For each image ID, it inserts a new row into the `{$wpdb->prefix}cnrs_data_manager_project_attachment_relation` table,
     * linking the project ID and the image ID.
     *
     * @return void
     */
    public static function saveProjectsImages(): void
    {
        if (isset($_POST['cnrs-data-manager-project-images'])) {
            global $wpdb;
            $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_project_attachment_relation");
            foreach ($_POST['cnrs-data-manager-project-images'] as $projectId => $images) {
                foreach ($images as $imageID) {
                    $insert = [
                        'project_id' => $projectId,
                        'attachment_id' => $imageID,
                    ];
                    $wpdb->insert($wpdb->prefix . 'cnrs_data_manager_project_attachment_relation', $insert, ['%d', '%d']);
                }
            }
        }
    }
}
