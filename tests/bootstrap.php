<?php

require dirname(__DIR__).'/vendor/autoload.php';

define('SAAZE_BASE_DIR', __DIR__ . DIRECTORY_SEPARATOR . '_data');
define('SAAZE_CONTENT_PATH', SAAZE_BASE_DIR . DIRECTORY_SEPARATOR . 'content');
define('SAAZE_PUBLIC_PATH', SAAZE_BASE_DIR . DIRECTORY_SEPARATOR . 'public');
define('SAAZE_TEMPLATES_PATH', SAAZE_BASE_DIR . DIRECTORY_SEPARATOR . 'templates');
define('SAAZE_CACHE_PATH', SAAZE_BASE_DIR . DIRECTORY_SEPARATOR . 'cache');
define('SAAZE_ENTRIES_PER_PAGE', 10);
