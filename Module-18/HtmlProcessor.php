<?php

$response = [];

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);

    if (! empty($body)) {
        $response['formatted_text'] = preg_replace('/<a[^>]*>(.*?)<\/a>/s', '$1', array_shift($body));
    } else {
        http_response_code(500);
        $response['error'] = 'Internal Server Error: Body is empty';
    }
} else {
    http_response_code(400);
    $response['error'] = 'Bad request: Needs method POST';
}

print_r(json_encode($response, JSON_FORCE_OBJECT));
