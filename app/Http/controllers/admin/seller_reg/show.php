<?php
$filename = basename($_GET['file'] ?? '');

if (!$filename) {
    http_response_code(400);
    exit('Missing file.');
}

// Construct full path
$filePath = base_path('storage/seller_docs/' . $filename);

if (!file_exists($filePath)) {
    http_response_code(404);
    exit('File not found.');
}

// Serve file securely
header('Content-Type: ' . mime_content_type($filePath));
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
