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
                    'form' => $newForm
                ),
                array('%s', '%s', '%s', '%s')
            );
        }
    }
}