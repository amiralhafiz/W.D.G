<?php
// back-end/api/version.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../bootstrap.php';

try {
    $db = \App\Database::getInstance();
    
    // Auto-calculating version based on user count or activity
    // Base version 10.01 + increments for every user
    $userCount = (int)($db->query("SELECT COUNT(*) FROM wdg_users")->fetchColumn());
    
    $major = 10 + floor($userCount / 100);
    $minor = $userCount % 100;
    
    $version = sprintf("v%d.%02d", $major, $minor);
    
    echo json_encode(['status' => 'success', 'version' => $version]);
} catch (\Exception $e) {
    echo json_encode(['status' => 'success', 'version' => 'v10.00']);
}
