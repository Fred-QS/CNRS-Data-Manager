<?php
/**
 * Info modal Agent Template.
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

<div class="cnrs-dm-front-agent-info-wrapper">
    <div class="cnrs-dm-front-agent-info">
        <div class="cnrs-dm-front-agent-info-left">
            <div class="cnrs-dm-info-avatar" style="background-image: url(<?= $agent['photo'] ?>);"></div>
            <div class="cnrs-dm-front-agent-info-left-divider">
                <svg class="cnrs-dm-divider-desktop" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 83.96 775.01">
                    <polygon fill="#b0d3ec" points="0 0 83.96 120.18 45.67 212.98 31.9 288.06 63.79 376.1 71.23 496.14 47.01 614.71 55.36 708.71 0 775.01 0 0"/>
                    <polygon fill="#6ab7e6" points="0 0 58.9 140.85 31.9 288.06 63.79 376.1 8.87 586.55 36.22 731.82 0 775.01 0 0"/>
                </svg>
                <svg class="cnrs-dm-divider-mobile" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 775.01 83.96">
                    <polygon fill="#b0d3ec" points="775.01 0 654.83 83.96 562.02 45.67 486.95 31.9 398.91 63.79 278.87 71.23 160.3 47.01 66.3 55.36 0 0 775.01 0"/>
                    <polygon fill="#6ab7e6" points="775.01 0 634.16 58.9 486.95 31.9 398.91 63.79 188.45 8.87 43.18 36.22 0 0 775.01 0"/>
                </svg>
            </div>
        </div>
        <div class="cnrs-dm-front-agent-info-left-shadow"></div>
        <div class="cnrs-dm-front-agent-info-right">
            <div class="cnrs-dm-close-info-container">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                </svg>
            </div>
            <div class="cnrs-dm-front-agent-info-data-container">
                <h3><?= ucfirst($agent['prenom']) ?> <?= strtoupper($agent['nom']) ?></h3>
                <a href="mailto:<?= $agent['email_pro'] ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path d="M64 112c-8.8 0-16 7.2-16 16v22.1L220.5 291.7c20.7 17 50.4 17 71.1 0L464 150.1V128c0-8.8-7.2-16-16-16H64zM48 212.2V384c0 8.8 7.2 16 16 16H448c8.8 0 16-7.2 16-16V212.2L322 328.8c-38.4 31.5-93.7 31.5-132 0L48 212.2zM0 128C0 92.7 28.7 64 64 64H448c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128z"/>
                    </svg>
                    <?= $agent['email_pro'] ?>
                </a>
                <div class="cnrs-dm-front-agent-info-data">
                    <h5><?= __('Status', 'cnrs-data-manager') ?></h5>
                    <span><?= $agent['statut'] ?><?= $agent['tutelle'] !== null ? ', ' . $agent['tutelle'] : '' ?></span>
                    <span><?= $agent['specialite'] ?></span>
                    <span><?= $agent['activite'] ?></span>
                    <h5><?= __('Membership', 'cnrs-data-manager') ?></h5>
                    <?php if (count($agent['equipes']) > 0): ?>
                        <p><?= count($agent['equipes']) > 1 ? __('Teams', 'cnrs-data-manager') : __('Team', 'cnrs-data-manager') ?></p>
                        <ul>
                            <?php foreach ($agent['equipes'] as $equipe): ?>
                                <li><a href="<?= $equipe['extra']['url'] ?>">&bull; <?= $equipe['extra']['title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;  ?>
                    <?php if (count($agent['services']) > 0): ?>
                        <p><?= count($agent['services']) > 1 ? __('Services', 'cnrs-data-manager') : __('Service', 'cnrs-data-manager') ?></p>
                        <ul>
                            <?php foreach ($agent['services'] as $service): ?>
                                <li><a href="<?= $service['extra']['url'] ?>">&bull; <?= $service['extra']['title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;  ?>
                    <?php if (count($agent['plateformes']) > 0): ?>
                        <p><?= count($agent['plateformes']) > 1 ? __('Platforms', 'cnrs-data-manager') : __('Platform', 'cnrs-data-manager') ?></p>
                        <ul>
                            <?php foreach ($agent['plateformes'] as $plateforme): ?>
                                <li><a href="<?= $plateforme['extra']['url'] ?>">&bull; <?= $plateforme['extra']['title'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;  ?>
                    <?php if ($agent['responsabilite'] !== null): ?>
                        <span><?= $agent['responsabilite'] ?></span>
                    <?php endif; ?>
                    <?php if ($agent['expertise'] !== null): ?>
                        <h5><?= __('Expertise', 'cnrs-data-manager') ?></h5>
                        <span><?= $agent['expertise'] ?></span>
                    <?php endif; ?>
                    <?php if (count($agent['liens_externes']) > 0): ?>
                        <p><?= count($agent['liens_externes']) > 1 ? __('Links', 'cnrs-data-manager') : __('Link', 'cnrs-data-manager') ?></p>
                        <ul>
                            <?php foreach ($agent['liens_externes'] as $link): ?>
                                <li><a href="<?= $link['url'] ?>" target="_blank">&bull; <?= $link['nom'] ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;  ?>
                </div>
            </div>
        </div>
    </div>
</div>
