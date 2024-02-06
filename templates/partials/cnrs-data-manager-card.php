<?php
/**
 * Card Agent Template
 * Available variables:
 *
 *  - Front WP methods, constants and functions.
 *  - $type (string): The request type (teams, services or platforms).
 *  - $isSelectorAvailable (boolean): If the view type selector is activated.
 *  - $renderMode (string): The render mode value ('sorted', 'simple').
 *  - $entities (array): All the agents (plus filter data if sorted render).
 *  - $agent (array): The agent data.
 */

?>

<div class="cnrs-dm-front-agent-container cnrs-dm-front-inline">
    <img src="<?= $agent['photo'] ?>" alt="<?= $agent['nom'] ?> <?= $agent['prenom'] ?> avatar">
    <p><?= $agent['nom'] ?> <?= $agent['prenom'] ?></p>
    <small><?= $agent['statut'] ?> <?= $agent['tutelle'] ?></small>
    <br/>
    <a href="mailto:<?= $agent['email_pro'] ?>"><?= $agent['email_pro'] ?></a>
</div>