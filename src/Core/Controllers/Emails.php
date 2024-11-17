<?php

namespace CnrsDataManager\Core\Controllers;

use CnrsDataManager\Core\Models\Forms;
use CnrsDataManager\Core\Models\Emails as EmailsModel;

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
            $template = 'reset-password';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            return sendCNRSEmail($to, $data->subject, $body);

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
            $template = 'confirmation';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends a mission form revision email to the specified email address.
     *
     * @param string $email The email address to send the mission form revision email to.
     * @param string $revisionUuid The unique identifier of the mission form.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendToManager(string $email, string $revisionUuid): bool
    {
        try {
            $template = 'revision';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends an abandoned form notification email.
     *
     * @param string $email The email address to send the notification to.
     * @param bool $byFunder If abandoned by credit manager.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendAbandonForm(string $email, bool $byFunder = false): bool
    {
        try {
            $template = $byFunder === false
                ? 'canceled'
                : 'canceled-by-funder';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends a funder approval notification email.
     *
     * @param string $email The email address to send the notification to.
     * @param string $funderUuid A unique identifier for the funder.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendToFunder(string $email, string $funderUuid): bool
    {
        try {
            $template = 'funder';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends a notification email to the admin indicating that the deadline for a form has been exceeded.
     *
     * @param array $emails
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendToAdmins(array $emails): bool
    {
        try {
            $template = 'exceed';
            $email = $emails[0];
            array_shift($emails);
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body, $emails);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends a mission form revision notification email to the agent.
     *
     * @param string $email The email address to send the notification to.
     * @param string $agentUuid The UUID of the mission form revision.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendRevisionToAgent(string $email, string $agentUuid): bool
    {
        try {
            $template = 'edit';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    /**
     * Sends a validated mission form notification email.
     *
     * @param string $email The email address to send the notification to.
     *
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    public static function sendValidatedForm(string $email, string $validateUuid, bool $forAll = false): bool
    {
        try {
            $template = $forAll === false ? 'validate' : 'validate-for-all';
            $data = EmailsModel::getEmailFromFileAndLang($template, substr(get_locale(), 0, 2));

            if ($data === null) {
                return false;
            }

            ob_start();
            include(CNRS_DATA_MANAGER_PATH . '/templates/includes/emails/template.php');
            $body = ob_get_clean();

            sendCNRSEmail($email, $data->subject, $body);

            return true;

        } catch (\ErrorException $e) {
            return false;
        }
    }

    public static function initEmailsTemplates(): void
    {
        $languages = function_exists('pll_the_languages') ? pll_languages_list() : ['fr'];
        $emails = getAllTemplateModel();

        $allEmails = EmailsModel::getAllEmails();
        $emailsToDelete = array_filter($allEmails, function ($email) use($languages) {
            return !in_array($email->lang, $languages, true);
        });

        EmailsModel::deleteEmails($emailsToDelete);

        foreach ($languages as $language) {
            foreach ($emails as $emailType => $shortcodes) {
                $exist = EmailsModel::getEmailFromFileAndLang($emailType, $language);
                if (null === $exist) {
                    EmailsModel::createEmailFromFileAndLang($emailType, $language, $shortcodes);
                }
            }
        }
    }

    public static function getEmailsList(): array
    {
        $emails = EmailsModel::getAllEmails();
        $filtered = [];
        foreach ($emails as $email) {
            if (!isset($filtered[$email->file])) {
                $filtered[$email->file] = [];
            }
            $email->url = '/cnrs-umr/email-preview?template=' . $email->file . '&lang=' . $email->lang;
            $email->flag = cnrs_get_languages_from_pll([], false)[$email->lang];
            $filtered[$email->file][$email->lang] = $email;
        }
        return $filtered;
    }

    public static function saveEmailsTemplates(): void
    {
        if (isset(
            $_POST['cnrs_dm_email_id'],
            $_POST['cnrs_dm_email_subject'],
            $_POST['cnrs_dm_email_title'],
            $_POST['cnrs_dm_email_content']
        )) {
            EmailsModel::saveEmail(
                [
                    'subject' => stripslashes($_POST['cnrs_dm_email_subject']),
                    'title' => stripslashes($_POST['cnrs_dm_email_title']),
                    'content' => stripslashes($_POST['cnrs_dm_email_content']),
                    'title_logo' => strlen(trim($_POST['cnrs_dm_email_icon'])) > 0 ? $_POST['cnrs_dm_email_icon'] : null,
                ],
                (int) $_POST['cnrs_dm_email_id']
            );
        }
    }
}
