<?php

namespace CnrsDataManager\Core\Models;

class Projects
{
    /**
     * Sets the relation between a team and a project.
     *
     * @param int $postID The ID of the project.
     * @param int $teamID The ID of the team.
     * @return void
     */
    public static function setTeamProjectRelation(int $postID, int $teamID): void
    {
        global $wpdb;
        $insert = [
            'team_id' => $teamID,
            'project_id' => $postID
        ];

        $wpdb->insert($wpdb->prefix . 'cnrs_data_manager_team_project', $insert, ['%d', '%d']);
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
                $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_team_project (team_id, project_id) VALUES ({$insert['team_id']}, {$insert['project_id']})");
            } else {
                $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_team_project (team_id, project_id, display_order) VALUES ({$insert['team_id']}, {$insert['project_id']}, {$insert['display_order']})");
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
}
