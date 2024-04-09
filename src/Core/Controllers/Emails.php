<?php

namespace CnrsDataManager\Core\Controllers;

use CnrsDataManager\Core\Models\Forms;

class Emails
{
    /**
     * Sends a reset email.
     *
     * @param string $to The email address to send the reset email to.
     * @return bool Returns true if the reset email was sent successfully, otherwise false.
     */
    public static function sendResetEmail(string $to): bool
    {
        try {
            $pwd = wp_generate_password();

            $options = ['cost' => 12];
            $encodedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);
            Forms::updatePassword($to, $encodedPwd);
            $subject = __('Your new password', 'cnrs-data-manager');
            $template = 'reset-password';

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            return sendCNRSEmail($to, $subject, $body);

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Checks the validity of an email address against a given list of email addresses.
     *
     * @param string $email The email address to be checked.
     * @param array $agents An array of email addresses to compare against.
     *
     * @return bool Returns true if the email address is valid, false otherwise.
     */
    public static function checkEmailValidity(string $email, array $agents): bool
    {
        foreach ($agents as $agent) {
            $agentEmail = $agent['email_pro'];
            if (is_string($agentEmail) && $agentEmail === $email) {
                return true;
            }
        }
        return false;
    }

    public static function sendConfirmationEmail(string $uuid): bool
    {
        try {
            $user = Forms::getUserByUuid($uuid);
            if ($user === null) {
                return false;
            }
            $subject = __('Confirmation', 'cnrs-data-manager');
            $template = 'confirmation';

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            return sendCNRSEmail($user->email, $subject, $body);

        } catch (\ErrorException $e) {
            return false;
        }
    }
}