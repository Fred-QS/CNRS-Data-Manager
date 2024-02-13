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
        <div class="cnrs-dm-front-close-shadow"></div>
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
        <div class="cnrs-dm-close-info-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 79.51 79.54">
                <polygon fill="#b0d3ec" points="53.54 70.95 33.58 44.55 16.35 31.03 0 0 79.51 .03 79.51 79.54 53.54 70.95"/>
                <polygon fill="#6ab7e6" points="30.21 51.39 27.61 27.44 0 0 79.51 .03 79.51 79.54 56.93 59.55 30.21 51.39"/>
                <path fill="#fff" d="M52.71,26.1c-.12-.12-.18-.19-.25-.26-1.38-1.38-2.76-2.76-4.13-4.14-.08-.08-.16-.16-.21-.25-.14-.22-.15-.45.03-.66.16-.19.43-.24.67-.1.12.07.22.16.32.25,1.37,1.36,2.73,2.73,4.1,4.1.08.08.12.19.18.29.14-.13.2-.18.26-.24.79-.79,1.58-1.57,2.38-2.36.6-.59,1.2-1.18,1.8-1.77.38-.37.7-.44.94-.19.25.26.18.6-.2.98-1.37,1.37-2.73,2.73-4.1,4.1-.07.07-.14.14-.22.23.09.1.16.19.25.27,1.39,1.4,2.78,2.81,4.17,4.21.15.15.28.32.23.55-.1.44-.57.56-.92.23-.41-.39-.81-.79-1.22-1.19-1.04-1.03-2.08-2.07-3.11-3.1-.06-.06-.12-.11-.26-.24-.06.1-.1.21-.18.29-1.37,1.38-2.74,2.75-4.11,4.12-.1.1-.2.19-.32.25-.24.13-.53.06-.67-.13-.16-.23-.16-.45.04-.67.4-.43.81-.85,1.22-1.26,1.1-1.09,2.2-2.17,3.32-3.29Z"/>
            </svg>
        </div>
        <div class="cnrs-dm-front-agent-info-right">
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
