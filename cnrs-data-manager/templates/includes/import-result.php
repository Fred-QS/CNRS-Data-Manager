<ul>
    <li class="cnrs-dm-import-state-pending">
        <i class="cnrs-dm-import-state-response cnrs-dm-import-state">1.&nbsp;<?= __('File uploaded', 'cnrs-data-manager') ?></i>
        <span class="cnrs-dm-import-state-logo">
            <svg id="cnrs-dm-import-good" viewBox="0 0 448 512">
                <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
            </svg>
        </span>
    </li>
    <li class="cnrs-dm-import-state-pending">
        <i class="cnrs-dm-import-state-response cnrs-dm-import-state">2.&nbsp;<?= __('Checking file', 'cnrs-data-manager') ?></i>
        <span class="cnrs-dm-import-state-logo">
            <?php if ($error === null): ?>
                <svg id="cnrs-dm-import-good" viewBox="0 0 448 512">
                    <path d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/>
                </svg>
            <?php else: ?>
                <svg id="cnrs-dm-import-bad" viewBox="0 0 384 512">
                    <path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/>
                </svg>
            <?php endif; ?>
        </span>
    </li>
</ul>
<?php if ($error === null): ?>
    <form method="post" id="cnrs-dm-import-confirm-form">
        <p>
            <input class="button button-primary" type="submit" value="<?= __('Confirm import', 'cnrs-data-manager') ?>">
        </p>
    </form>
<?php else: ?>
    <p class="cnrs-dm-import-error-message"><?= $error ?></p>
<?php endif; ?>