<?php

/**
 * List item Agent Template for full content render.
 * Available variables:
 *
 *  - Front WP methods, constants and functions.
 *  - $type (string): The request type (teams, services or platforms).
 *  - $isSelectorAvailable (boolean): If the view type selector is activated.
 *  - $renderMode (string): The render mode value ('sorted', 'simple').
 *  - $entities (array): All the agents (plus filter data if sorted render).
 *  - $agent (array): The agent data.
 *  - $shortCodesCounter (int): Shortcode iteration in the page.
 *  - $defaultView (string): Default view (null, 'list', 'grid').
 */

?>

<div class="et_had_animation cnrs-dm-front-list-item-container">
    <div style="background-image: url(<?= $agent['photo'] ?>)"></div>
    <p><?= $agent['nom'] ?> <?= $agent['prenom'] ?></p>
    <small><?= $agent['statut'] ?> <?= $agent['tutelle'] ?></small>
    <br/>
    <a href="mailto:<?= $agent['email_pro'] ?>"><?= $agent['email_pro'] ?></a>
</div>
