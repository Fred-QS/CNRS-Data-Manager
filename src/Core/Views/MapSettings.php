<?php
$markers = [];
?>

<div class="wrap">
    <h1 class="wp-heading-inline title-and-logo">
        <?= svgFromBase64(CNRS_DATA_MANAGER_MAP_ICON, '#5d5d5d') ?>
        <?= __('3D Map', 'cnrs-data-manager'); ?>
    </h1>
    <p>
        <?= __('In this section, you will be able to configure the display of the <b>3D world map</b> to firstly specify the position coordinates of the default map, then define all the markers you need with the <b>GPS coordinates</b> and the name of each marker.', 'cnrs-data-manager') ?>
        <br/>
        <?= __('You will also be able to <b>configure the type of display</b> as well as all the options allowing you to define the rendering of the 3D world map.', 'cnrs-data-manager') ?>
    </p>
    <h3 class="cnrs-dm-tools-h2"><?= __('Map Options', 'cnrs-data-manager') ?></h3>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=3D-map">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-main-lat"><?= __('Default latitude', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <p>
                        <input name="cnrs-dm-main-lat" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-main-lat" value="" class="regular-text">
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-main-lng"><?= __('Default longitude', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <p>
                        <input name="cnrs-dm-main-lng" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-main-lng" value="" class="regular-text">
                    </p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit-settings" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>

    <hr/>
    <h3 class="cnrs-dm-tools-h2"><?= __('Markers', 'cnrs-data-manager') ?></h3>
    <span id="cnrs-dm-map-reference-labels" data-lat="<?= __('Latitude', 'cnrs-data-manager') ?>" data-lng="<?= __('Longitude', 'cnrs-data-manager') ?>" data-title="<?= __('Title', 'cnrs-data-manager') ?>"></span>
    <form method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=3D-map">
        <div id="cnrs-dm-add-marker">
            <button type="button" id="cnrs-dm-marker-adder" class="button button-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16">
                    <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                </svg>
                <?= __('Add a new marker', 'cnrs-data-manager') ?>
            </button>
        </div>
        <?php if (!empty($markers)): ?>
            <table class="form-table" role="presentation">
                <tbody id="cnrs-dm-markers-list">

                </tbody>
            </table>
        <?php else: ?>
            <p class="cnrs-dm-no-marker"><?= __('Please add your first marker by clicking on the button above.', 'cnrs-data-manager') ?></p>
        <?php endif; ?>
        <p class="submit">
            <input disabled type="submit" name="submit" id="submit-markers" class="button button-primary" value="<?= __('Save') ?>">
        </p>
    </form>
