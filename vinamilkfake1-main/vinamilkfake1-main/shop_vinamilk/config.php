<?php
// config.php
// Lấy đường dẫn base tự động
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_PATH', $scriptName . '/');

function url($path = '')
{
    return BASE_PATH . ltrim($path, '/');
}
