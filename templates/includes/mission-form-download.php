<?php

$mode = explode(
    '?',
    str_replace(
        ['/cnrs-umr/mission-form-', '/'],
        '',
        $_SERVER['REQUEST_URI']
    )
)[0];

if (!in_array($mode, ['print', 'download'], true)) {
    header('Location: /404');
}