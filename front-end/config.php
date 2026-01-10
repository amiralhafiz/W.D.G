<?php
// /front-end/config.php

// Set the default timezone if necessary, e.g., for Kuala Lumpur, Malaysia
date_default_timezone_set('Asia/Kuala_Lumpur'); 

// PATH LOGIC: Check if back-end exists locally
$backendPath = __DIR__ . '/../back-end/bootstrap.php';

if (file_exists($backendPath)) {
    // SAME SERVER MODE
    require_once $backendPath;
} else {
    // DIFFERENT SERVER MODE
    // Here you would define constants to point to your Remote API URL
    define('API_URL', 'https://api.yourdomain.com');
}
