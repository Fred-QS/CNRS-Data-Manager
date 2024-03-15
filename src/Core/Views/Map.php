<?php
$data = json_encode($json, JSON_THROW_ON_ERROR);
?>

<div class="cnrs-dm-map" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <pre style="display: none;" class="cnrs-dm-map-data"><?php echo $data ?></pre>
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

