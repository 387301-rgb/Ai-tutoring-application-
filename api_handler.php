<?php
function get_ai_response($user_prompt) {
    // This pulls 'gsk_...' from your Secrets
    $api_key = $_ENV['Tutoring']; 

    // Use the Groq API endpoint
    $url = "https://api.groq.com/openai/v1/chat/completions"; 

    $data = [
        "model" => "grok-4.1-fast-reasoning", // Latest high-speed reasoning model
        "messages" => [
            ["role" => "system", "content" => "You are an expert tutor. Break down complex topics simply."],
            ["role" => "user", "content" => $user_prompt]
        ],
        "temperature" => 0.6
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $api_key
    ]);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    // Return the content or a helpful error message
    if (isset($result['choices'][0]['message']['content'])) {
        return $result['choices'][0]['message']['content'];
    } else {
        return "API Error: " . ($result['error']['message'] ?? "Unknown issue. Check your API key.");
    }
}
?>