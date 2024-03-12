<?php
/**
 * Card Agent Template.
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
?>

<div class="cnrs-dm-front-agent-container cnrs-dm-front-card<?= $defaultView === 'grid' ? ' selected' : '' ?>">
    <div style="background-image: url(<?= $agent['photo'] ?>)"></div>
    <span>
        <p><?= $agent['nom'] ?> <?= $agent['prenom'] ?></p>
        <?php if ($agent['tutelle'] !== null): ?>
            <small><?= $agent['tutelle'] ?></small>
        <?php endif; ?>
    </span>
    <?php if ($agent['responsabilite'] !== null): ?>
        <small><?= $agent['responsabilite'] ?></small>
    <?php endif; ?>
</div>