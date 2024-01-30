<?php

?>

<div class="wrap">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_SETTINGS_ICON, '#5d5d5d') ?>
        <?= __('Settings', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?= __('Here you can update the settings of the CNRS Data Manager extension.', 'cnrs-data-manager') ?>
        <br/>
        <?= __('In order to save your changes, please click on the <b>Update</b> button.', 'cnrs-data-manager') ?>
    </p>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=settings">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="cnrs-dm-filename"><?= __('XML file name', 'cnrs-data-manager') ?></label>
                    </th>
                    <td>
                        <p><input name="cnrs-dm-filename" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-filename" value="umr_5805" class="regular-text">&nbsp;<i>.xml</i></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>
</div>