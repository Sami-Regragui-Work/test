<?php
// Enable error capture globally
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/error-handling-with-ai.log');
ini_set('display_errors', 0); // Never show to users
if ($_ENV['APP_DEBUG'] ?? false) {
    ini_set('display_errors', 1);
}


// Catch ALL fatal errors before they kill JSON response
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'content' => '<div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">Server Error. Check console.</div>',
            'modal' => null,
            'debug' => $_ENV['APP_DEBUG'] ? $error['message'] : null
        ]);
        exit;
    }
});

// Convert warnings/notices to exceptions for JSON handling
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) return;
    throw new ErrorException($message, 0, $severity, $file, $line);
});
