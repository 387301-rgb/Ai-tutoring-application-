<?php
// api_handler.php
$groqApiKey = getenv('GROQ_API_KEY');
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
    "model" => "llama3-70b-8192",
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
    "Authorization: Bearer $groqApiKey",
    "Content-Type: application/json"
]);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    $error = "Error: " . curl_error($ch);
    curl_close($ch);
    header("Location: index.php?error=" . urlencode($error));
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    $error = "Groq API request failed with HTTP code $httpCode. Response: $response";
    header("Location: index.php?error=" . urlencode($error));
    exit;
}

$responseData = json_decode($response, true);
$answer = $responseData['choices'][0]['message']['content'] ?? 'No response received.';
header("Location: index.php?response=" . urlencode($answer));
exit;
?>
