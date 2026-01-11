<?php
putenv("ROOT=" . __DIR__ . '/');

// passthru("php ./src/php/test.php");

session_start();

$urlStart = "src/php/";
// require_once "{$urlStart}dbLink.php";

// might need this url start
// $_SESSION["urlStart"] = $urlStart;
?>
<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="src/css/tw.css" />
    <title>Admin Dashboard - UCC3</title>

    <!-- jquery -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
    <!-- chart.js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script type="importmap">
        {
            "imports": {
                "@kurkle/color": "/node_modules/@kurkle/color/dist/color.esm.js"
            }
        }
    </script>
    <!-- CRUD logic (generic for all tables) -->
    <script src="src/js/crud.js" type="module" defer></script>
    <!-- Sidebar navigation + section loading -->
    <script src="src/js/sidebar.js" type="module" defer></script>
</head>

<body class="bg-[#030712]">
    <div class="flex min-h-screen">
        <?php require "{$urlStart}component/sidebar.php"; ?>

        <main class="body__main w-full transition-all duration-300 flex flex-col">
            <div class="p-4">
                <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
                    <!-- dashboard/tables wrapper -->
                    <article id="dynamic-content" class="content rounded-lg overflow-hidden">
                        <!-- updated by AJAX -->
                    </article>
                </div>
            </div>
        </main>

        <!-- modal wrapper -->
        <article
            id="hidden-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center overflow-x-hidden overflow-y-auto bg-black/50">
            <div
                id="hidden-modal-inner"
                class="relative w-full max-w-2xl px-4 mx-auto">
                <!-- updated by AJAX if needed -->
            </div>
        </article>
    </div>
</body>

</html>