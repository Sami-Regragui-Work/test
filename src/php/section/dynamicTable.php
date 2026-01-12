<?php
require_once __DIR__ . '/../../php/error-handling-with-ai.php';
require_once __DIR__ . '/../pages/DynamicTable.php';

session_start();

$tableName = $_GET['tableName'] ?? 'users';

try {
    $table = (new DynamicTable($tableName))->loadData();

    ob_start();
    echo $table->render();
    $content = ob_get_clean();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'content' => $content,
        'modal' => null
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'content' => "<div class='p-8 text-center text-red-600 bg-red-50 rounded-lg'>Error: " . $e->getMessage() . "</div>",
        'modal' => null
    ]);
}


// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // foreach (glob(__DIR__ . '/../repositories/*.php') as $file) {
// //     require_once $file;
// // }
// // foreach (glob(__DIR__ . '/../model/*.php') as $file) {
// //     require_once $file;
// // }
// require_once __DIR__ . '/../pages/DynamicTable.php';


// // Debug: List loaded classes
// echo "<!-- DEBUG: Repos: " . count(glob(__DIR__ . '/../repositories/*.php')) . " -->";

// $tableName = $_GET['tableName'] ?? 'users';

// try {
//     echo "<!-- DEBUG: Creating DynamicTable($tableName) -->";
//     $table = (new DynamicTable($tableName))->loadData();

//     ob_start();
//     echo $table->render();
//     $content = ob_get_clean();

//     header('Content-Type: application/json; charset=utf-8');
//     echo json_encode([
//         'content' => $content,
//         'debug' => ['table' => $tableName, 'records' => $table->totalRecords ?? 0]
//     ]);
// } catch (Throwable $e) {
//     http_response_code(500);
//     echo json_encode([
//         'error' => $e->getMessage(),
//         'trace' => $e->getTraceAsString(),
//         'table' => $tableName ?? 'unknown'
//     ]);
// }
