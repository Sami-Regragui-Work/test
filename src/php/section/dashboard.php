<?php
require_once __DIR__ . '/../../php/error-handling-with-ai.php';
require_once __DIR__ . '/../pages/Dashboard.php';
session_start();

$dashboard = (new Dashboard())->loadStats();

ob_start();
echo $dashboard->render();
$content = ob_get_clean();

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'content' => $content,
    'modal' => null
]);
