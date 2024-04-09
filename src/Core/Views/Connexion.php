<div class="cnrs-dm-mission-form" id="identification-form">
    <script>
        const xmlAgents = JSON.parse(`<?php echo stripslashes($agents) ?>`);
    </script>
    <div id="cnrs-dm-front-mission-form-login-wrapper">
        <form method="post" class="cnrs-dm-front-mission-form-login" data-action="login" action="<?php echo add_query_arg( NULL, NULL ) ?>">
            <h2><?php echo __('Connexion', 'cnrs-data-manager') ?></h2>
            <?php if (isset($_POST['cnrs-dm-front-mission-form-login-action']) && $_POST['cnrs-dm-front-mission-form-login-action'] === 'reset' && $error === null): ?>
                <p class="cnrs-dm-front-mission-form-confirm-reset"><?php echo __('An email has been sent to your mail box.', 'cnrs-data-manager') ?></p>
            <?php endif; ?>
            <?php if($error !== null): ?>
                <p class="cnrs-dm-front-mission-form-error-reset"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="hidden" name="cnrs-dm-front-mission-form-login-action" value="login">
            <div class="cnrs-dm-front-mission-form-login-container">
                <label class="cnrs-dm-front-mission-form-login-label">
                    <span><?php echo __('Your email', 'cnrs-data-manager') ?></span>
                    <input spellcheck="false" autocomplete="off" type="email" name="cnrs-dm-front-mission-form-login-email" required>
                </label>
                <label class="cnrs-dm-front-mission-form-login-label">
                    <span><?php echo __('Your password', 'cnrs-data-manager') ?></span>
                    <input spellcheck="false" autocomplete="off" type="password" name="cnrs-dm-front-mission-form-login-pwd" required>
                </label>
                <div class="cnrs-dm-front-mission-form-login-email-reset-container">
                    <p id="cnrs-dm-front-mission-form-reset-link"><span><?php echo __('Reset password', 'cnrs-data-manager') ?></span> <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg></p>
                </div>
                <div class="cnrs-dm-front-mission-form-submit-login-container">
                    <button id="cnrs-dm-front-mission-form-submit-login-btn" disabled type="submit" class="cnrs-dm-front-btn"><?php echo __('Login', 'cnrs-data-manager') ?></button>
                </div>
            </div>
        </form>
        <form method="post" class="cnrs-dm-front-mission-form-login hide" data-action="reset" action="<?php echo add_query_arg( NULL, NULL ) ?>">
            <h2><?php echo __('Reset password', 'cnrs-data-manager') ?></h2>
            <input type="hidden" name="cnrs-dm-front-mission-form-login-action" value="reset">
            <div class="cnrs-dm-front-mission-form-login-container">
                <label class="cnrs-dm-front-mission-form-login-label">
                    <span><?php echo __('Your email', 'cnrs-data-manager') ?></span>
                    <input spellcheck="false" autocomplete="off" type="email" name="cnrs-dm-front-mission-form-reset-email" required>
                </label>
                <div class="cnrs-dm-front-mission-form-login-email-reset-container">
                    <p id="cnrs-dm-front-mission-form-login-link"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/></svg> <span><?php echo __('Back to  login', 'cnrs-data-manager') ?></span></p>
                </div>
                <div class="cnrs-dm-front-mission-form-submit-login-container">
                    <button id="cnrs-dm-front-mission-form-submit-reset-btn" disabled type="submit" class="cnrs-dm-front-btn"><?php echo __('Reset password', 'cnrs-data-manager') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>