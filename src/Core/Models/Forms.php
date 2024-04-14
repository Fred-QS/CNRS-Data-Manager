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

    public static function recordNewForm(string $newForm, string $originalForm, string $userEmail, string $uuid): void
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
                    'created_at' => date("Y-m-d H:i:s")
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );
        }
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
        return $wpdb->get_row("SELECT debug_mode, debug_email FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
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
        $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_mission_form_settings SET debug_mode = {$mode}, debug_email = '{$email}'");
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
        return $wpdb->get_results("SELECT email, created_at, uuid FROM {$wpdb->prefix}cnrs_data_manager_mission_forms {$where} ORDER BY created_at DESC LIMIT {$offset}, {$limit}", ARRAY_A);
    }

    /**
     * Retrieves the form from the cnrs_data_manager_mission_forms table based on the provided UUID.
     *
     * @param string $uuid The UUID of the form.
     * @return ?string The form if found, null otherwise.
     */
    public static function getFormsByUuid(string $uuid): ?object
    {
        global $wpdb;
        return $wpdb->get_row("SELECT form, created_at FROM {$wpdb->prefix}cnrs_data_manager_mission_forms WHERE uuid = '{$uuid}'");
    }
}