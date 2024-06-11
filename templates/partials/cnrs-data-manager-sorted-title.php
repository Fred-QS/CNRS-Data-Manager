<?php
/**
 * Entity title Template for sorted view render type.
 *
 * Useful variables:
 *
 *  - Front WP methods, constants and functions.
 *  - $type (string): The request type (teams, services or platforms).
 *  - $isSelectorAvailable (boolean): If the view type selector is activated.
 *  - $renderMode (string): The render mode value ('sorted', 'simple').
 *  - $entity (array): the entity data (xml name, wp name)
 *  - $shortCodesCounter (int): Shortcode iteration in the page.
 *  - $defaultView (string): Default view (null, 'list', 'grid').
 */

?>

<p class="cnrs-dm-front-entity-title">
    <?php echo $entity['wp_name'] !== null ? $entity['wp_name'] : __('Without belonging', 'cnrs-data-manager') ?>
    <?php echo $entity['xml_name'] !== null && $entity['xml_name'] !== $entity['wp_name'] ? ' <i>' . $entity['xml_name'] . '</i>' : '' ?>
</p>
