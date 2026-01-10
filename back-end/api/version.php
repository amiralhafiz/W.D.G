<?php
// back-end/api/version.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/../bootstrap.php';

try {
    // Get commit count as version number
    $commitCount = trim(shell_exec('git rev-list --count HEAD') ?: '0');
    $commitHash = trim(shell_exec('git rev-parse --short HEAD') ?: 'unknown');
    
    // Starting at 10.01 (assuming 1st commit is 10.01)
    // Formula: Major = 10 + floor(commits/100), Minor = commits % 100
    // If commit count is 8, it becomes v10.08
    $totalCommits = (int)$commitCount;
    $major = 10 + floor($totalCommits / 100);
    $minor = $totalCommits % 100;
    
    $version = sprintf("v%d.%02d - %s", $major, $minor, $commitHash);
    
    echo json_encode(['status' => 'success', 'version' => $version]);
} catch (\Exception $e) {
    echo json_encode(['status' => 'success', 'version' => 'v10.00 - dev']);
}
