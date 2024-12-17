<?php

namespace CnrsDataManager\Core\Models;

class Emails
{
    public static function deleteEmails(array $ids): void
    {
        global $wpdb;
        $sql = "DELETE FROM {$wpdb->prefix}cnrs_data_manager_emails WHERE id IN (%s)";
        $wpdb->query($wpdb->prepare($sql, implode(',', $ids)));
    }

    public static function getAllEmails(): array
    {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_emails ORDER BY file ASC";
        return $wpdb->get_results($sql);
    }

    public static function getEmailFromFileAndLang(string $file, string $lang): ?object
    {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_emails WHERE file = '%s' AND lang = '%s'";
        return $wpdb->get_row($wpdb->prepare($sql, $file, $lang));
    }

    public static function createEmailFromFileAndLang(string $file, string $lang, array $shortcodes): void
    {
        global $wpdb;
        $wpdb->insert(
            "{$wpdb->prefix}cnrs_data_manager_emails",
            array(
                'subject' => ucfirst(str_replace('-', ' ', $file)),
                'title' => ucfirst(str_replace('-', ' ', $file)),
                'file' => $file,
                'content' => '<p>TODO</p>',
                'lang' => $lang,
                'shortcodes' => json_encode($shortcodes),
            )
        );
    }

    public static function saveEmail(array $data, int $id): void
    {
        global $wpdb;
        $wpdb->update(
            "{$wpdb->prefix}cnrs_data_manager_emails",
            $data,
            array(
                'id' => $id
            )
        );
    }
}
