<?php

require_once ROOT_PATH . '/modules/All in One Accessibility/module.php';

$aioa_language = new Language(ROOT_PATH . '/modules/All in One Accessibility/language');

$module = new Aioa_Module($language, $aioa_language, $pages);
