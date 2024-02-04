<?php

use CnrsDataManager\Core\Models\Settings;
use CnrsDataManager\Core\Models\Map;

Settings::updateMarkers();
$json = Map::getData();
$mapData = json_encode($json, JSON_THROW_ON_ERROR);
$markers = $json['markers'];
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
    <div id="cnrs-dm-title-with-button">
        <h3 class="cnrs-dm-tools-h2"><?= __('Map Options', 'cnrs-data-manager') ?></h3>
        <button type="button" class="button button-primary" id="cnrs-dm-open-map-preview"><?= __('Preview', 'cnrs-data-manager') ?></button>
    </div>
    <form method="post">
        <input type="hidden" name="action" value="update-default-marker">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=3D-map">
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-main-lat"><?= __('Default latitude', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <p>
                        <input required name="cnrs-dm-main-lat" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-main-lat" value="<?= $json['main']['lat'] ?>" class="regular-text">
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-main-lng"><?= __('Default longitude', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <p>
                        <input required name="cnrs-dm-main-lng" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-main-lng" value="<?= $json['main']['lng'] ?>" class="regular-text">
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-map-settings-view"><?= __('Map view', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <select id="cnrs-dm-map-settings-view" required name="cnrs-dm-map-settings-view">
                        <option <?= $json['view'] === 'space' ? 'selected' : '' ?> value="space"><?= __('Space', 'cnrs-data-manager') ?></option>
                        <option <?= $json['view'] === 'hologram' ? 'selected' : '' ?> value="hologram"><?= __('Hologram', 'cnrs-data-manager') ?></option>
                        <option <?= $json['view'] === 'news' ? 'selected' : '' ?> value="news"><?= __('News', 'cnrs-data-manager') ?></option>
                        <option <?= $json['view'] === 'classic' ? 'selected' : '' ?> value="classic"><?= __('Classic', 'cnrs-data-manager') ?></option>
                        <option <?= $json['view'] === 'cork' ? 'selected' : '' ?> value="cork"><?= __('Cork', 'cnrs-data-manager') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-map-settings-sunlight"><?= __('Solar illumination effect', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <div class="cnrs-dm-radio-container">
                        <label class="cnrs-dm-label-radio">
                            <span><?= __('Yes', 'cnrs-data-manager') ?></span>
                            <input <?= (int) $json['sunlight'] === 1 ? 'checked' : '' ?> name="cnrs-dm-map-settings-sunlight" type="radio" class="cnrs-dm-radio-button" value="1">
                        </label>
                        <label class="cnrs-dm-label-radio">
                            <input <?= (int) $json['sunlight'] === 0 ? 'checked' : '' ?> name="cnrs-dm-map-settings-sunlight" type="radio" class="cnrs-dm-radio-button" value="0">
                            <span><?= __('No', 'cnrs-data-manager') ?></span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-map-settings-stars"><?= __('Star Generation', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <div class="cnrs-dm-radio-container">
                        <label class="cnrs-dm-label-radio">
                            <span><?= __('Yes', 'cnrs-data-manager') ?></span>
                            <input <?= (int) $json['stars'] === 1 ? 'checked' : '' ?> name="cnrs-dm-map-settings-stars" type="radio" class="cnrs-dm-radio-button" value="1">
                        </label>
                        <label class="cnrs-dm-label-radio">
                            <input <?= (int) $json['stars'] === 0 ? 'checked' : '' ?> name="cnrs-dm-map-settings-stars" type="radio" class="cnrs-dm-radio-button" value="0">
                            <span><?= __('No', 'cnrs-data-manager') ?></span>
                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-map-settings-black_bg"><?= __('Black background', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <div class="cnrs-dm-radio-container">
                        <label class="cnrs-dm-label-radio">
                            <span><?= __('Yes', 'cnrs-data-manager') ?></span>
                            <input <?= (int) $json['black_bg'] === 1 ? 'checked' : '' ?> name="cnrs-dm-map-settings-black_bg" type="radio" class="cnrs-dm-radio-button" value="1">
                        </label>
                        <label class="cnrs-dm-label-radio">
                            <input <?= (int) $json['black_bg'] === 0 ? 'checked' : '' ?> name="cnrs-dm-map-settings-black_bg" type="radio" class="cnrs-dm-radio-button" value="0">
                            <span><?= __('No', 'cnrs-data-manager') ?></span>

                        </label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cnrs-dm-map-settings-atmospĥere"><?= __('Atmosphere', 'cnrs-data-manager') ?></label>
                </th>
                <td>
                    <div class="cnrs-dm-radio-container">
                        <label class="cnrs-dm-label-radio">
                            <span><?= __('Yes', 'cnrs-data-manager') ?></span>
                            <input <?= (int) $json['atmosphere'] === 1 ? 'checked' : '' ?> name="cnrs-dm-map-settings-atmosphere" type="radio" class="cnrs-dm-radio-button" value="1">
                        </label>
                        <label class="cnrs-dm-label-radio">
                            <input <?= (int) $json['atmosphere'] === 0 ? 'checked' : '' ?> name="cnrs-dm-map-settings-atmosphere" type="radio" class="cnrs-dm-radio-button" value="0">
                            <span><?= __('No', 'cnrs-data-manager') ?></span>
                        </label>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <h3 class="cnrs-dm-tools-h2 cnrs-dm-only-mobile cnrs-dm-tools-h2-mobile"><?= __('Map preview', 'cnrs-data-manager') ?></h3>
        <div id="cnrs-dm-map-preview">
            <button type="button" class="button button-primary" id="cnrs-dm-refresh-map-preview">
                <span class="cnrs-dm-only-desktop"><?= __('Refresh', 'cnrs-data-manager') ?></span>
                <svg class="cnrs-dm-only-mobile" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
                    <path fill="#fff" d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160H352c-17.7 0-32 14.3-32 32s14.3 32 32 32H463.5c0 0 0 0 0 0h.4c17.7 0 32-14.3 32-32V80c0-17.7-14.3-32-32-32s-32 14.3-32 32v35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1V432c0 17.7 14.3 32 32 32s32-14.3 32-32V396.9l17.6 17.5 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352H160c17.7 0 32-14.3 32-32s-14.3-32-32-32H48.4c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z"/>
                </svg>
            </button>
            <button type="button" class="button" id="cnrs-dm-close-map-preview"><?= __('Close', 'cnrs-data-manager') ?></button>
            <div id="cnrs-dm-map-preview-header">
                <span class="cnrs-dm-grab-dots">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20">
                        <path fill="#fff" d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"/>
                    </svg>
                </span>
                <span class="cnrs-dm-grab-dots">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20">
                        <path fill="#fff" d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z"/>
                    </svg>
                </span>
            </div>
            <div class="cnrs-dm-map">
                <pre style="display: none;" class="cnrs-dm-map-data"><?= $mapData ?></pre>
                <?php if ($json['atmosphere'] === true): ?>
                    <div id="cnrs-dm-map-atmosphere"></div>
                <?php endif; ?>
            </div>
            <?php if ($json['sunlight'] === true): ?>
                <div id="cnrs-dm-map-controls">
                    <div id="cnrs-dm-map-sun-slider-wrap">
                        <input type="range" min="0" max="360" value="90" id="cnrs-dm-map-sun-slider">
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($json['view'] === 'space'): ?>
                <div id="cnrs-dm-map-res" style="display: none;">
                    <img alt="day-view" id="cnrs-dm-map-day" src="/wp-content/plugins/cnrs-data-manager/assets/media/maps/space-view/day-by-nasa.jpg">
                    <img alt="night-view" id="cnrs-dm-map-night" src="/wp-content/plugins/cnrs-data-manager/assets/media/maps/space-view/night-by-nasa.jpg">
                </div>
            <?php endif; ?>
            <?php if ($json['view'] === 'cork'): ?>
                <div id="cnrs-dm-map-res" style="display: none;">
                    <img alt="cork-texture" id="cnrs-dm-map-cork" src="/wp-content/plugins/cnrs-data-manager/assets/media/maps/cork/cork.jpg">
                </div>
            <?php endif; ?>
        </div>

        <p class="submit">
            <input type="submit" name="submit" id="submit-settings" class="button button-primary" value="<?= __('Update') ?>">
        </p>
    </form>

    <hr/>
    <h3 class="cnrs-dm-tools-h2"><?= __('Markers', 'cnrs-data-manager') ?></h3>
    <span id="cnrs-dm-map-reference-labels" data-lat="<?= __('Latitude', 'cnrs-data-manager') ?>" data-lng="<?= __('Longitude', 'cnrs-data-manager') ?>" data-title="<?= __('Title', 'cnrs-data-manager') ?>" data-delete="<?= __('Delete', 'cnrs-data-manager') ?>"></span>
    <form method="post">
        <input type="hidden" name="action" value="update-markers">
        <input type="hidden" name="_wp_http_referer" value="/wp-admin/admin.php?page=3D-map">
        <div id="cnrs-dm-add-marker">
            <button type="button" id="cnrs-dm-marker-adder" class="button button-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" width="16" height="16">
                    <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                </svg>
                <?= __('Add a new marker', 'cnrs-data-manager') ?>
            </button>
        </div>
        <table class="form-table" role="presentation">
            <tbody id="cnrs-dm-markers-list">
                <?php foreach ($markers as $key => $marker): ?>
                    <tr class="cnrs-dm-marker-container" data-index="<?= $key ?>">
                        <td>
                            <table class="form-table" role="presentation">
                                <tbody>
                                <tr class="cnrs-dm-marker-first-row">
                                    <th scope="row">
                                        <label for="cnrs-dm-marker-title-<?= $key ?>"><?= __('Title', 'cnrs-data-manager') ?></label>
                                        <input type="hidden" name="cnrs-dm-marker-id-<?= $key ?>" value="<?= $marker['id'] ?>">
                                        <span class="cnrs-dm-markers-toggle">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
                                                <path fill="#2271b1" d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM135 241c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l87 87 87-87c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9L273 345c-9.4 9.4-24.6 9.4-33.9 0L135 241z"/>
                                            </svg>
                                        </span>
                                    </th>
                                    <td>
                                        <p>
                                            <input required name="cnrs-dm-marker-title-<?= $key ?>" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-title-<?= $key ?>" value="<?= $marker['title'] ?>" class="regular-text">
                                        </p>
                                    </td>
                                </tr>
                                <tr class="cnrs-dm-marker-row cnrs-dm-marker-row-hide">
                                    <th scope="row">
                                        <label for="cnrs-dm-marker-lat-<?= $key ?>"><?= __('Latitude', 'cnrs-data-manager') ?></label>
                                    </th>
                                    <td>
                                        <p>
                                            <input required name="cnrs-dm-marker-lat-<?= $key ?>" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-lat-<?= $key ?>" value="<?= $marker['lat'] ?>" class="regular-text">
                                        </p>
                                    </td>
                                </tr>
                                <tr class="cnrs-dm-marker-row cnrs-dm-marker-row-hide">
                                    <th scope="row">
                                        <label for="cnrs-dm-marker-lng-<?= $key ?>"><?= __('Longitude', 'cnrs-data-manager') ?></label>
                                    </th>
                                    <td>
                                        <p>
                                            <input required name="cnrs-dm-marker-lng-<?= $key ?>" autocomplete="off" spellcheck="false" type="text" id="cnrs-dm-marker-lng-<?= $key ?>" value="<?= $marker['lng'] ?>" class="regular-text">
                                        </p>
                                    </td>
                                </tr>
                                <tr class="cnrs-dm-marker-row cnrs-dm-marker-row-hide">
                                    <td colspan="2" class="cnrs-dm-td-no-padding">
                                        <input type="button" id="cnrs-dm-marker-delete-<?= $key ?>" class="button button-danger" value="<?= __('Delete', 'cnrs-data-manager') ?>">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p id="cnrs-dm-no-marker" class="<?= !empty($markers) ? 'hide' : '' ?>"><?= __('Please add your first marker by clicking on the button above.', 'cnrs-data-manager') ?></p>
        <p class="submit">
            <input disabled type="submit" name="submit" id="submit-markers" class="button button-primary" value="<?= __('Save') ?>">
        </p>
    </form>
</div>