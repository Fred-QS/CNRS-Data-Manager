<?php
/**
 * Inline Agent Template.
 *
 * Useful variables:
 *
 *  - Front WP methods, constants and functions.
 *  - $type (string): The request type (teams, services or platforms).
 *  - $isSelectorAvailable (boolean): If the view type selector is activated.
 *  - $renderMode (string): The render mode value ('sorted', 'simple').
 *  - $agent (array): The agent data.
 *  - $shortCodesCounter (int): Shortcode iteration in the page.
 *  - $defaultView (string): Default view (null, 'list', 'grid').
 */
$line = inlineInfo($agent);
?>

<div class="cnrs-dm-front-agent-container cnrs-dm-front-inline<?php echo in_array($defaultView, [null, 'list']) ? ' selected' : '' ?>">
    <div style="background-image: url(<?php echo $agent['photo'] ?>)"></div>
    <p><?php echo strtoupper($agent['nom']) ?> <?php echo $agent['prenom'] ?></p>
    <i><?php echo $line ?></i>
</div>