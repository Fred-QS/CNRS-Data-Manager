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
 *  - $pagination (array): Pagination data to build le UI logic.
 */
?>

<div class="cnrs-dm-front-list-item-container">
    <div class="cnrs-dm-front-list-item-avatar cnrs-dm-front-list-item-desktop">
        <div style="background-image: url(<?php echo $agent['photo'] ?>)"></div>
    </div>
    <div class="cnrs-dm-front-list-item-info">
        <div class="cnrs-dm-front-list-item cnrs-dm-front-list-item-desktop">
            <p><?php echo $agent['nom'] ?></p>
        </div>
        <div class="cnrs-dm-front-list-item cnrs-dm-front-list-item-desktop">
            <p><?php echo $agent['prenom'] ?></p>
        </div>
        <div class="cnrs-dm-front-list-item cnrs-dm-front-list-item-desktop">
            <p><?php echo $agent['statut'] ?></p>
        </div>
        <div class="cnrs-dm-front-list-item-mobile">
            <div style="background-image: url(<?php echo $agent['photo'] ?>)"></div>
            <p><?php echo $agent['nom'] ?> <?php echo $agent['prenom'] ?></p>
            <i><?php echo $agent['statut'] ?></i>
        </div>
        <div class="cnrs-dm-front-list-item cnrs-dm-front-list-item-membership">
            <?php foreach ($agent['equipes'] as $team): ?>
                <a class="cnrs-dm-front-membership-item" href="<?php echo $team['extra']['url'] ?>"><?php echo $team['extra']['title'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
