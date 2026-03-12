<?php
require_once 'api_handler.php';

$answer = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['question'])) {
    $answer = get_ai_response($_POST['question']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tutoring AI</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; line-height: 1.6; }
        .box { border: 1px solid #ccc; padding: 15px; border-radius: 8px; background: #f9f9f9; }
        textarea { width: 100%; height: 100px; margin-bottom: 10px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .response { margin-top: 20px; padding: 15px; background: #e9ecef; border-left: 5px solid #007bff; }
    </style>
</head>
<body>

    <h2>🎓 Tutoring Assistant</h2>
    <div class="box">
        <form method="POST">
            <label>Ask a question:</label>
            <textarea name="question" placeholder="How do I solve quadratic equations?" required></textarea>
            <button type="submit">Get Answer</button>
        </form>
    </div>

    <?php if ($answer): ?>
        <div class="response">
            <strong>Response:</strong><br>
            <?php echo nl2br(htmlspecialchars($answer)); ?>
        </div>
    <?php endif; ?>

</body>
</html>