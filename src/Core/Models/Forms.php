<?php

namespace CnrsDataManager\Core\Models;

class Forms
{
    /**
     * Retrieves the current form from the database.
     *
     * @return string The current form.
     *
     */
    public static function getCurrentForm(): string
    {
        global $wpdb;
        $row = $wpdb->get_row("SELECT form FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
        return $row->form;
    }

    /**
     * Sets the current form in the cnrs_data_manager_mission_form_settings table.
     *
     * @param string $form The form to set.
     * @return void
     */
    public static function setCurrentForm(string $form): void
    {
        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_mission_form_settings SET form='{$form}'");
    }

    public static function recordNewForm(
        string $newForm,
        string $originalForm,
        string $userEmail,
        string $uuid,
        bool $isValidated
    ): ?string
    {
        global $wpdb;
        $exist = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}cnrs_data_manager_mission_forms WHERE uuid = '{$uuid}'");
        if ($exist === null) {
            $wpdb->insert(
                "{$wpdb->prefix}cnrs_data_manager_mission_forms",
                array(
                    'uuid' => $uuid,
                    'email' => $userEmail,
                    'original' => $originalForm,
                    'form' => $newForm,
                    'status' => $isValidated === true ? 'PENDING' : 'EXCEPTION',
                    'created_at' => date("Y-m-d H:i:s")
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );
            if ($isValidated === true) {
                $form_id = $wpdb->insert_id;
                $revision_uuid = wp_generate_uuid4();
                $wpdb->insert(
                    "{$wpdb->prefix}cnrs_data_manager_revisions",
                    array(
                        'active' => 1,
                        'uuid' => $revision_uuid,
                        'form_id' => $form_id,
                        'sender' => 'AGENT',
                        'created_at' => date("Y-m-d H:i:s")
                    ),
                    array('%d', '%s', '%d', '%s', '%s')
                );
            }
            return $revision_uuid;
        }

        return null;
    }

    public static function updatePassword(string $email, string $pwd): void
    {
        global $wpdb;
        $exist = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}cnrs_data_manager_agents_accounts WHERE email = '{$email}'");
        if (get_locale() === 'fr_FR') {
            date_default_timezone_set("Europe/Paris");
        }
        if ($exist === null) {
            $uuid = str_replace('-', '', wp_generate_uuid4());
            $wpdb->insert(
                "{$wpdb->prefix}cnrs_data_manager_agents_accounts",
                array(
                    'email' => $email,
                    'password' => $pwd,
                    'uuid' => $uuid,
                    'created_at' => date("Y-m-d H:i:s")
                ),
                array('%s', '%s', '%s', '%s')
            );
        } else {
            $wpdb->update(
                "{$wpdb->prefix}cnrs_data_manager_agents_accounts",
                array('password' => $pwd, 'created_at' => date("Y-m-d H:i:s")),
                array('email' => $email),
                array('%s', '%s'),
                array('%s'),
            );
        }
    }

    /**
     * Validates a user based on their email and password.
     *
     * @param string $email The user's email.
     * @param string $pwd The user's password.
     * @return string|null Returns true if the user is valid, false otherwise.
     */
    public static function validatedUser(string $email, string $pwd): string|null
    {
        global $wpdb;
        $user = $wpdb->get_row("SELECT password, uuid FROM {$wpdb->prefix}cnrs_data_manager_agents_accounts WHERE email = '{$email}'");
        if ($user !== null && password_verify($pwd, $user->password)) {
            return $user->uuid;
        }
        return null;
    }

    /**
     * Retrieves a user from the cnrs_data_manager_agents_accounts table based on the given UUID.
     *
     * @param string|null $uuid The UUID of the user.
     * @return object|null The user object if found, null otherwise.
     */
    public static function getUserByUuid(string|null $uuid): object|null
    {
        if ($uuid !== null) {
            global $wpdb;
            return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_agents_accounts WHERE uuid = '{$uuid}'");
        }
        return null;
    }

    /**
     * Retrieves the settings from the cnrs_data_manager_mission_form_settings table.
     *
     * @return object An object containing the debug_mode and debug_email settings.
     */
    public static function getSettings(): object
    {
        global $wpdb;
        return $wpdb->get_row("SELECT debug_mode, debug_email, admin_email, generic_email, days_limit FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
    }

    /**
     * Sets the settings in the cnrs_data_manager_mission_form_settings table.
     *
     * @param array $data The data containing the settings.
     *                   The $data array should have the following keys:
     *                   - 'cnrs-dm-debug-mode': A string indicating the debug mode state.
     *                   - 'cnrs-dm-debug-email': A string representing the debug email.
     * @return void
     */
    public static function setSettings(array $data): void
    {
        global $wpdb;
        $mode = isset($data['cnrs-dm-debug-mode']) && $data['cnrs-dm-debug-mode'] === 'on' ? 1 : 0;
        $email = $data['cnrs-dm-debug-email'];
        $admin = $data['cnrs-dm-admin-email'];
        $generic = $data['cnrs-dm-generic-email'];
        $days = $data['cnrs-dm-days-limit'];
        $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_mission_form_settings SET debug_mode = {$mode}, debug_email = '{$email}', admin_email = '{$admin}', generic_email = '{$generic}', days_limit = {$days}");
    }

    /**
     * Retrieves the count of forms from the cnrs_data_manager_mission_forms table.
     *
     * @return int The number of forms.
     */
    public static function getFormsCount(): int
    {
        global $wpdb;
        $count = $wpdb->get_row("SELECT COUNT(*) as nb FROM {$wpdb->prefix}cnrs_data_manager_mission_forms");
        return $count->nb;
    }

    /**
     * Retrieves a paginated list of forms from the cnrs_data_manager_mission_forms table.
     *
     * @param string $search The search string to filter the forms by email.
     * @param int $limit The maximum number of forms to retrieve per page.
     * @param int $current The current page number.
     * @return array Returns an array containing the forms matching the search criteria, sorted by created_at date in descending order.
     */
    public static function getPaginatedFormsList(string $search, int $limit, int $current): array
    {
        global $wpdb;
        $where = strlen($search) > 0 ? "WHERE email LIKE '%{$search}%'" : '';
        $offset = ($current*$limit) - $limit;
        return $wpdb->get_results("SELECT id, email, created_at, uuid, status FROM {$wpdb->prefix}cnrs_data_manager_mission_forms {$where} ORDER BY created_at DESC LIMIT {$offset}, {$limit}", ARRAY_A);
    }

    /**
     * Retrieves the form from the cnrs_data_manager_mission_forms table based on the provided UUID.
     *
     * @param string $uuid The UUID of the form.
     * @return object|null The form if found, null otherwise.
     */
    public static function getFormsByUuid(string $uuid): ?object
    {
        global $wpdb;
        return $wpdb->get_row("SELECT form, created_at FROM {$wpdb->prefix}cnrs_data_manager_mission_forms WHERE uuid = '{$uuid}'");
    }

    /**
     * Sets the conventions in the cnrs_data_manager_conventions table based on the provided data.
     *
     * @param array $data The data containing the conventions to set.
     *        Each convention should be an array with the following keys:
     *        - 'id' (string|int): The ID of the convention. If it starts with 'new', a new convention will be inserted.
     *        - 'name' (string): The name of the convention.
     *        - 'primary_email' (string): The primary email of the convention.
     *        - 'secondary_email' (string): The secondary email of the convention.
     *        - 'available' (bool): Whether the convention is available or not. True for available, false for not available.
     * @return void
     */
    public static function setConventions(array $data): void
    {
        global $wpdb;
        $exists = array_map(function ($row) {
            return (int) $row['id'];
        }, $wpdb->get_results("SELECT id FROM {$wpdb->prefix}cnrs_data_manager_conventions", ARRAY_A));
        $updatedRows = [];
        foreach ($data as $convention) {
            if (is_array($convention)) {
                if (str_starts_with($convention['id'], 'new')) {
                    $wpdb->insert(
                        "{$wpdb->prefix}cnrs_data_manager_conventions",
                        array(
                            'name' => $convention['name'],
                            'primary_email' => $convention['primary_email'],
                            'secondary_email' => $convention['secondary_email'],
                            'available' => isset($convention['available']) ? 0 : 1
                        ),
                        array('%s', '%s', '%s', '%d')
                    );
                } else {
                    $updatedRows[] = (int) $convention['id'];
                    $wpdb->update(
                        "{$wpdb->prefix}cnrs_data_manager_conventions",
                        array(
                            'name' => $convention['name'],
                            'primary_email' => $convention['primary_email'],
                            'secondary_email' => $convention['secondary_email'],
                            'available' => isset($convention['available']) ? 0 : 1
                        ),
                        array('id' => $convention['id']),
                        array('%s', '%s', '%s', '%d'),
                        array('%d'),
                    );
                }
            }
        }
        $toDelete = array_diff($exists, $updatedRows);
        foreach ($toDelete as $id) {
            $wpdb->delete(
                "{$wpdb->prefix}cnrs_data_manager_conventions",
                array('id' => $id),
                array('%d')
            );
        }
    }

    /**
     * Retrieves all conventions from the database.
     *
     * @return array An array containing all conventions as associative arrays.
     */
    public static function getConventions(): array
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_conventions ORDER BY id DESC", ARRAY_A);
    }

    /**
     * Retrieves a specific convention from the database based on its ID.
     *
     * @param int $id The ID of the convention to retrieve.
     * @return array An associative array containing the details of the convention.
     */
    public static function getConvention(int $id): array
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_conventions WHERE id = {$id}", ARRAY_A);
    }

    /**
     * Checks if a revision exists in the database.
     *
     * @return bool Returns true if the revision exists; otherwise, returns false.
     */
    public static function revisionExists(): bool
    {
        if (isset($_GET['r'])) {
            global $wpdb;
            $exist = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}cnrs_data_manager_revisions WHERE uuid = '{$_GET['r']}' AND active = 1");
            return $exist !== null;
        }
        return false;
    }

    /**
     * Retrieves the revision details from the database.
     *
     * @return ?object An object containing the revision details, or null if no revision found.
     */
    public static function getRevision(): ?object
    {
        global $wpdb;
        return $wpdb->get_row("SELECT {$wpdb->prefix}cnrs_data_manager_revisions.*, {$wpdb->prefix}cnrs_data_manager_mission_forms.uuid as form_uuid, {$wpdb->prefix}cnrs_data_manager_mission_forms.email as agent_email, {$wpdb->prefix}cnrs_data_manager_mission_forms.form FROM {$wpdb->prefix}cnrs_data_manager_revisions INNER JOIN {$wpdb->prefix}cnrs_data_manager_mission_forms ON {$wpdb->prefix}cnrs_data_manager_mission_forms.id = {$wpdb->prefix}cnrs_data_manager_revisions.form_id WHERE {$wpdb->prefix}cnrs_data_manager_revisions.uuid = '{$_GET['r']}' AND {$wpdb->prefix}cnrs_data_manager_revisions.active = 1");
    }

    /**
     * Retrieves the number of revisions for a given form ID from the database.
     *
     * @param int $formId The ID of the form.
     * @return int The number of revisions for the given form ID.
     */
    public static function getRevisionsCountByFormId(int $formId): int
    {
        global $wpdb;
        $qr = $wpdb->get_row("SELECT COUNT(*) as nb FROM {$wpdb->prefix}cnrs_data_manager_revisions WHERE form_id = {$formId}");
        return (int) $qr->nb;
    }

    /**
     * Sets the admin email for the mission form settings in the database.
     *
     * @param string $email The email address of the admin.
     * @return void
     */
    public static function setAdminEmail(string $email): void
    {
        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_mission_form_settings SET admin_email='{$email}', days_limit=5");
    }

    /**
     * Sets the status of a form to 'CANCELED' in the mission forms table and deactivates all revisions related to the form.
     *
     * @param int $id The ID of the form to be abandoned.
     * @return string The email associated with the abandoned form.
     */
    public static function setAbandonForm(int $id): string
    {
        global $wpdb;
        $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_mission_forms SET status='CANCELED' WHERE id={$id}");
        $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_revisions SET active=0 WHERE form_id={$id}");
        $form = $wpdb->get_row("SELECT email FROM {$wpdb->prefix}cnrs_data_manager_mission_forms WHERE id={$id}");
        return $form->email;
    }

    /**
     * Sets the status of a mission form to 'PENDING' and retrieves the email of the form based on its ID.
     *
     * @param int $id The ID of the mission form.
     *
     * @return array An array containing the email and uuid associated with the mission form.
     */
    public static function setPendingForm(int $id): array
    {
        global $wpdb;
        $wpdb->update(
            "{$wpdb->prefix}cnrs_data_manager_mission_forms",
            array('status' => 'PENDING'),
            array('id' => $id),
            array('%s'),
            array('%d'),
        );
        $uuid = wp_generate_uuid4();
        $wpdb->insert(
            "{$wpdb->prefix}cnrs_data_manager_revisions",
            array(
                'active' => 1,
                'uuid' => $uuid,
                'form_id' => $id,
                'sender' => 'AGENT',
                'created_at' => date("Y-m-d H:i:s")
            ),
            array('%d', '%s', '%d', '%s', '%s')
        );
        $form = $wpdb->get_row("SELECT form FROM {$wpdb->prefix}cnrs_data_manager_mission_forms WHERE id={$id}");
        $email = getManagerEmailFromForm($form->form);
        return ['email' => $email, 'uuid' => $uuid];
    }

    /**
     * Records an observation in the database.
     *
     * @param object $data The data containing the observation details.
     *                     The object should have the following properties:
     *                     - id: The ID of the observation to update.
     *                     - uuid: The UUID corresponding to the observation.
     *                     - form_id: The form ID associated with the observation.
     *                     - sender: The sender of the observation.
     *                     - observations: The observation details.
     *                     - created_at: The creation date of the observation.
     * @param bool $isValid
     * @return void
     */
    public static function recordObservation(object $data, bool $isValid = false): void
    {
        global $wpdb;
        $wpdb->update(
            "{$wpdb->prefix}cnrs_data_manager_revisions",
            array('active' => 0),
            array('id' => $data->id),
            array('%d'),
            array('%d')
        );
        if ($isValid === false) {
            $wpdb->insert(
                "{$wpdb->prefix}cnrs_data_manager_revisions",
                array(
                    'active' => 1,
                    'uuid' => $data->uuid,
                    'manager_name' => $data->manager_name,
                    'manager_email' => $data->manager_email,
                    'form_id' => $data->form_id,
                    'sender' => $data->sender,
                    'observations' => $data->observations,
                    'created_at' => $data->created_at
                ),
                array('%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s')
            );
            $wpdb->update(
                "{$wpdb->prefix}cnrs_data_manager_mission_forms",
                array('form' => $data->form),
                array('id' => $data->form_id),
                array('%s'),
                array('%d')
            );
        } else {
            $wpdb->insert(
                "{$wpdb->prefix}cnrs_data_manager_revisions",
                array(
                    'active' => 0,
                    'uuid' => $data->uuid,
                    'manager_name' => $data->manager_name,
                    'manager_email' => $data->manager_email,
                    'form_id' => $data->form_id,
                    'sender' => $data->sender,
                    'created_at' => $data->created_at
                ),
                array('%d', '%s', '%s', '%s', '%d', '%s', '%s')
            );
            $wpdb->update(
                "{$wpdb->prefix}cnrs_data_manager_mission_forms",
                array('status' => 'VALIDATED', 'form' => $data->form),
                array('id' => $data->form_id),
                array('%s', '%s'),
                array('%d')
            );
        }
    }

    /**
     * Retrieves the revision managers associated with a specific form from the database.
     *
     * @param int $formId The ID of the form to retrieve the revision managers for.
     * @return array An array containing revision managers as associative arrays with 'name' and 'email' keys.
     */
    public static function getRevisionManagers(int $formId): array
    {
        global $wpdb;
        return $wpdb->get_results("SELECT manager_name as name, manager_email as email FROM {$wpdb->prefix}cnrs_data_manager_revisions WHERE form_id={$formId} AND manager_name IS NOT NULL AND manager_email IS NOT NULL", ARRAY_A);
    }
    
    public static function updateForm(object $data): void
    {
        global $wpdb;
        $wpdb->update(
            "{$wpdb->prefix}cnrs_data_manager_revisions",
            array('active' => 0),
            array('id' => $data->id),
            array('%d'),
            array('%d')
        );
        $wpdb->insert(
            "{$wpdb->prefix}cnrs_data_manager_revisions",
            array(
                'active' => 1,
                'uuid' => $data->uuid,
                'form_id' => $data->form_id,
                'sender' => $data->sender,
                'created_at' => $data->created_at
            ),
            array('%d', '%s', '%d', '%s', '%s')
        );
        $wpdb->update(
            "{$wpdb->prefix}cnrs_data_manager_mission_forms",
            array('form' => $data->form),
            array('id' => $data->form_id),
            array('%s'),
            array('%d')
        );
    }
}
