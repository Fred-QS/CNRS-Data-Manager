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

    /**
     * Sends a confirmation email to the specified email address.
     *
     * @param string $email The email address to send the confirmation email to.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendConfirmationEmail(string $email): bool
    {
        try {
            $subject = __('Confirmation', 'cnrs-data-manager');
            $template = 'confirmation';

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends a mission form revision email to the specified email address.
     *
     * @param string $email The email address to send the mission form revision email to.
     * @param string $uuid The unique identifier of the mission form.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendToManager(string $email, string $uuid): bool
    {
        try {
            $subject = __('Mission form revision', 'cnrs-data-manager');
            $template = 'revision';

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends an abandoned form notification email.
     *
     * @param string $email The email address to send the notification to.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendAbandonForm(string $email): bool
    {
        try {
            $subject = __('Abandoned form', 'cnrs-data-manager');
            $template = 'canceled';

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    public static function sendToAdmin(string $email): bool
    {
        try {
            $subject = __('Deadline exceeded form', 'cnrs-data-manager');
            $template = 'exceed';

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }
}
