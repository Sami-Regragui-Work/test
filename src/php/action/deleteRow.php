<?php
session_start();
require_once __DIR__ . "/../../php/config/DataBase.php";

$dbLink = DATABASE::openDB()->getPdo();

$allowedTables = ["patients", "doctors", "departments"];

$table = htmlspecialchars($_POST["table"] ?? "", ENT_QUOTES, "UTF-8");
if (!in_array($table, $allowedTables, true)) {
    http_response_code(400);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "success" => false,
        "error"   => "Invalid table",
    ]);
    exit;
}

$searchableCols = $_SESSION[$table]['searchableCols'];

$idName = $searchableCols[0];
$rowId  = (int)($_POST[$idName] ?? 0);

$deleteQuery = <<<SQL
DELETE FROM {$table}
WHERE {$idName} = {$rowId}
SQL;

$affected = 0;
$deleteRes = $dbLink->query($deleteQuery);
if ($deleteRes) {
    // $affected = $dbLink->affected_rows;
    $affected = $deleteRes->rowCount();
}

header("Content-Type: application/json; charset=utf-8");
echo json_encode([
    "success"  => true,
    "affected" => $affected,
    "error"    => "",
    "debug"        => [
        "table"         => $table,
        "post"          => $_POST,
        "searchableCols" => $searchableCols,
        "idName"        => $idName,
        "rowId"         => $rowId,
        "deleteQuery"   => $deleteQuery,
    ],
]);
