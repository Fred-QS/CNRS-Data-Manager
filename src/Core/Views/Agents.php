<?php

?>

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
