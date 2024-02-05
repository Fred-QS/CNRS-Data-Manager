<?php if ($renderMode === 'simple'): ?>

    <div>
        <ul>
            <?php foreach ($agents as $agent): ?>
                <li>
                    <p><?= $agent['nom'] ?> <?= $agent['prenom'] ?></p>
                    <small><?= $agent['statut'] ?> <?= $agent['tutelle'] ?></small>
                    <br/>
                    <a href="mailto:<?= $agent['email_pro'] ?>"><?= $agent['email_pro'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php else: ?>

<div>
    <?php foreach ($agents as $agent): ?>
    <div>
        <h3><?= $agent['wp_name'] !== null ? $agent['wp_name'] : __('Orphans', 'cnrs-data-manager') ?><?= $agent['xml_name'] !== null ? ' <i>' . $agent['xml_name'] . '</i>' : '' ?></h3>
    </div>
    <ul>
        <?php foreach ($agent['agents'] as $user): ?>
            <li>
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
