<?php
// api_handler.php
$groqApiKey = getenv('GROQ_API_KEY') ?: ($_ENV['GROQ_API_KEY'] ?? $_SERVER['GROQ_API_KEY'] ?? '');

if (empty($groqApiKey)) {
    $error = "GROQ_API_KEY is not configured. Please add it to your environment secrets.";
    header("Location: index.php?error=" . urlencode($error));
    exit;
}

$groqApiUrl = "https://api.groq.com/openai/v1/chat/completions";

// Get the question, grade level, and subject from the POST data
$question = $_POST['question'] ?? null;
$gradeLevel = $_POST['grade-level'] ?? null;
$subject = $_POST['subject'] ?? null;

if (empty($question) || empty($gradeLevel) || empty($subject)) {
    $error = "Please provide a question, grade level, and subject.";
    header("Location: index.php?error=" . urlencode($error));
    exit;
}

// Customize the prompt based on grade level and subject
$gradeInstruction = "";
switch ($gradeLevel) {
    case "elementary":
        $gradeInstruction = "Explain this in a simple, fun, and easy-to-understand way for an elementary school student. Use analogies and avoid complex terms.";
        break;
    case "middle":
        $gradeInstruction = "Explain this clearly for a middle school student. Use some examples and avoid overly complex language.";
        break;
    case "high":
        $gradeInstruction = "Provide a detailed and accurate explanation suitable for a high school student. Use appropriate terminology and examples.";
        break;
    case "college":
        $gradeInstruction = "Explain this at a college level, with depth, precision, and advanced terminology. Include relevant theories or concepts.";
        break;
    default:
        $gradeInstruction = "Provide a clear and detailed explanation.";
}

$customPrompt = "You are an AI tutor. The student is in **$gradeLevel** and is asking about **$subject**. ";
$customPrompt .= "$gradeInstruction ";
$customPrompt .= "The student's question is: **$question**. Respond in a way that matches their educational level.";

// Data to send to Groq API
$data = [
    "model" => "llama-3.3-70b-versatile",
    "messages" => [
        ["role" => "system", "content" => $customPrompt],
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
$tokenUsage = $responseData['usage']['total_tokens'] ?? 'Unknown';

// Redirect back to index.php with response, grade, subject, and token usage
header("Location: index.php?"
    . "response=" . urlencode($answer)
    . "&grade=" . urlencode($gradeLevel)
    . "&subject=" . urlencode($subject)
    . "&tokens=" . urlencode($tokenUsage));
exit;
?>
