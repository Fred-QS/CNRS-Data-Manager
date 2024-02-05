<?php if ($renderMode === 'simple'): ?>

    <div class="cnrs-dm-front-agents-container">
        <ul class="cnrs-dm-front-agents-list">
            <?php foreach ($agents as $agent): ?>
                <li class="cnrs-dm-front-agent-container">
                    <p><?= $agent['nom'] ?> <?= $agent['prenom'] ?></p>
                    <small><?= $agent['statut'] ?> <?= $agent['tutelle'] ?></small>
                    <br/>
                    <a href="mailto:<?= $agent['email_pro'] ?>"><?= $agent['email_pro'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php else: ?>

    <div class="cnrs-dm-front-agents-container">
        <?php foreach ($agents as $agent): ?>
        <div>
            <p class="cnrs-dm-front-entity-title"><?= $agent['wp_name'] !== null ? $agent['wp_name'] : __('Without belonging', 'cnrs-data-manager') ?><?= $agent['xml_name'] !== null ? ' <i>' . $agent['xml_name'] . '</i>' : '' ?></p>
        </div>
        <ul class="cnrs-dm-front-agents-list">
            <?php foreach ($agent['agents'] as $user): ?>
                <li class="cnrs-dm-front-agent-container">
                    <p><?= $user['nom'] ?> <?= $user['prenom'] ?></p>
                    <small><?= $user['statut'] ?> <?= $user['tutelle'] ?></small>
                    <br/>
                    <a href="mailto:<?= $user['email_pro'] ?>"><?= $user['email_pro'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
    </div>

<?php endif; ?>
