<?php
require_once __DIR__ . '/../../php/error-handling-with-ai.php';
session_start();
require_once __DIR__ . "/../config/DataBase.php";
require_once __DIR__ . "/../modals/addForms.php";

$allowedTables = ["patients", "doctors", "departments"];

$table = htmlspecialchars($_GET["tableName"] ?? "patients", ENT_QUOTES, 'UTF-8');
if (!in_array($table, $allowedTables)) {
    http_response_code(400);
    $content = <<<HTML
    <p class="p-4 text-center text-lg text-red-600 dark:text-red-400">Invalid table<br>{$table}</p>
    HTML;
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "content" => $content,
        'modal'   => null,
    ]);
    exit;
}
$dbLink = DATABASE::openDB()->getPdo();
// pagination
$pageInd = max(1, (int)($_GET['pageInd'] ?? 1));
// $rowPerPage = 20;
// i'm going to hard code 50 rowPerPage until adding paginiation scripts later
$rowPerPage = 50;
$offset = ($pageInd - 1) * $rowPerPage;

// search
$search = trim(htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, "UTF-8"));
$where = '';
$valuesBinder = [];
$searchableCols = [];

$query = <<<SQL
SHOW COLUMNS
FROM {$table}
SQL;
$colsRes = $dbLink->query($query);

if ($colsRes) {
    $cols = $colsRes->fetchAll();
    $searchableCols = array_column($cols, 'Field');
}

$_SESSION[$table] ??= [];
$_SESSION[$table]['searchableCols'] = $searchableCols;

if (!empty($search)) {
    if ($searchableCols) {
        $likePartPrep = [];
        foreach ($searchableCols as $col) {
            $likePartPrep[] = <<<SQL
            {$col} LIKE ?
            SQL;
            $valuesBinder[] = '%' . $search . '%';
        }
        $where = 'WHERE ' . implode(' OR ', $likePartPrep);
    }
}

// rows count
$rowsCountPrep = <<<SQL
SELECT COUNT(*) AS rowsCount
FROM {$table}
{$where}
SQL;

$rowsCountStmt = $dbLink->prepare($rowsCountPrep);
if ($rowsCountStmt && $valuesBinder) {
    // $rowsCountTypes = str_repeat("s", count($valuesBinder));
    // $rowsCountStmt->bind_param($rowsCountTypes, ...$valuesBinder);
}
$rowsCount = 0;
if ($rowsCountStmt->execute($valuesBinder)) {
    $row = $rowsCountStmt ? $rowsCountStmt->fetch() : ["rowsCount" => 0];
    $rowsCount = (int)$row["rowsCount"];
}

$pageCount = (int)ceil(max(1, $rowsCount) / $rowPerPage);

$limitedContentPrep = <<<SQL
SELECT *
FROM {$table}
{$where}
LIMIT ?
OFFSET ?
SQL;

$limitedContentStmt = $dbLink->prepare($limitedContentPrep);
// $limitedContentTypes = $valuesBinder ? str_repeat("s", count($valuesBinder)) : "";
// $limitedContentTypes .= "ii"; // limit and offset
$valuesBinder[] = $rowPerPage;
$valuesBinder[] = $offset;

// if ($limitedContentStmt) $limitedContentStmt->bind_param($limitedContentTypes, ...$valuesBinder);

$rows = [];
// $colsNames = [];

if ($limitedContentStmt && $limitedContentStmt->execute($valuesBinder)) {
    if ($limitedContentStmt) {
        // $fields = $limitedContentStmt->fetch_fields();
        // foreach ($fields as $field) {
        //     $colsNames[] = $field->name;
        // }
        $rows = $limitedContentStmt->fetchAll();
    }
}


$title = ucfirst($table);

ob_start();
?>
<section
    id="dynamic-table"
    class="p-4 space-y-4"
    data-table="<?= $table ?>"
    data-page="<?= (int)$pageInd ?>"
    data-per-page="<?= (int)$rowPerPage ?>"
    data-total-rows="<?= (int)$rowsCount ?>">
    <header class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                <?= $title ?>
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Listing of <?= $title ?>.
            </p>
        </div>

        <div class="flex gap-2">
            <input
                id="search"
                type="search"
                name="search"
                value="<?= $search ?>"
                placeholder="Search..."
                class="w-full sm:w-64 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                data-role="table-search">

            <button
                type="button"
                class="inline-flex items-center rounded-md border border-blue-300 bg-white px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-50 dark:border-blue-700 dark:bg-gray-900 dark:text-blue-300 dark:hover:bg-blue-950"
                data-role="table-add"
                data-table="<?= $table ?>">
                + Add
            </button>


        </div>
    </header>


    <main class="main__main overflow-x-auto bg-white shadow rounded-lg dark:bg-gray-800">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900 dark:text-gray-400">
                <tr>
                    <?php foreach ($searchableCols as $col): ?>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            <?= htmlspecialchars($col, ENT_QUOTES, 'UTF-8') ?>
                        </th>
                    <?php endforeach; ?>
                    <th scope="col" class="px-4 py-3 text-right whitespace-nowrap">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="<?= max(1, count($searchableCols) + 1) ?>" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Table is still empty
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $row): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <?php foreach ($searchableCols as $col): ?>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <?php
                                    $value = $row[$col] ?? '';
                                    if (is_numeric($value)) {
                                        echo (string)$value;
                                    } else {
                                        echo htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
                                    }
                                    ?>
                                </td>
                            <?php endforeach; ?>

                            <?php $idName = $searchableCols[0];
                            $id = (int)($row[$idName] ?? 0); ?>
                            <td class="actions px-4 py-3 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-yellow-300 bg-white px-2 py-1 text-xs font-medium text-yellow-700 hover:bg-yellow-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-yellow-600 dark:bg-gray-900 dark:text-yellow-300 dark:hover:bg-yellow-950"
                                    data-role="table-edit"
                                    data-table="<?= $table ?>"
                                    data-id="<?= $id ?>">
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex items-center rounded-md border border-red-300 bg-white px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-50 dark:border-red-700 dark:bg-gray-900 dark:text-red-300 dark:hover:bg-red-950"
                                    data-role="table-delete"
                                    data-table="<?= $table ?>"
                                    data-id="<?= $id ?>">
                                    Delete
                                </button>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>

        </table>
    </main>

    <footer class="flex flex-col items-center justify-between gap-3 text-xs text-gray-500 sm:flex-row dark:text-gray-400">
        <div>
            Page <?= (int)$pageInd ?> of <?= max(1, (int)$pageCount) ?>,
            <?= (int)$rowsCount ?> total rows
        </div>
        <?php if ($pageCount > 1): ?>
            <nav class="inline-flex items-center gap-1" aria-label="Pagination" data-role="table-pagination">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-950"
                    data-page="<?= max(1, $pageInd - 1) ?>"
                    <?= $pageInd <= 1 ? 'disabled' : '' ?>>
                    Previous
                </button>
                <?php for ($p = 1; $p <= $pageCount; $p++): ?>
                    <button
                        type="button"
                        class="inline-flex items-center rounded-md border px-2 py-1 text-xs font-medium
                        <?= $p == $pageInd
                            ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-950 dark:text-indigo-200'
                            : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-950' ?>"
                        data-page="<?= (int)$p ?>">
                        <?= (int)$p ?>
                    </button>
                <?php endfor; ?>
                <button
                    type="button"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-2 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-950"
                    data-page="<?= min($pageCount, $pageInd + 1) ?>"
                    <?= $pageInd >= $pageCount ? 'disabled' : '' ?>>
                    Next
                </button>
            </nav>
        <?php endif; ?>
    </footer>
</section>
<?php
$content = ob_get_clean();

$modal = $addForms[$table] ?? "";


header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'content' => $content,
    'modal'   => $modal,
]);
