<?php
// api_handler.php
$apiKey = "";
$groqApiUrl = "https://api.groq.com/openai/v1/chat/completions";

// Get the question from the POST data
$question = $_POST['question'] ?? null;

if (empty($question)) {
    $error = "No question provided";
    header("Location: index.php?error=" . urlencode($error));
    exit;
}

// Data to send to Groq API
$data = [
    "model" => "llama3-70b-8192", // Updated model name
    "messages" => [
        ["role" => "user", "content" => $question]
    ]
];

// Initialize cURL
$ch = curl_init($groqApiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type": application/json"
]);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    $error = "Error: " . curl_error($ch);
    header("Location: index.php?error=" . urlencode($error));
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        $error = "Groq API request failed with HTTP code $httpCode. Response: $response";
        header("Location: index.php?error=" . urlencode($error));
    } else {
        $responseData = json_encode(json_decode($response), JSON_PRETTY_PRINT);
        header("Location: index.php?response=" . urlencode($responseData));
    }
}

// Close cURL
curl_close($ch);
?>
