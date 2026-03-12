<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Me!</title>
    <style>
        :root {
            --primary-color: #4a90e2;
            --accent-color: #f39c12;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            background: var(--bg-gradient);
            color: #333;
            min-height: 100vh;
        }

        /* --- Header & Navigation --- */
        header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .logo { font-size: 1.5rem; font-weight: bold; color: white; }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        nav a:hover { color: var(--accent-color); }

        /* --- Main Container --- */
        .container { 
            max-width: 600px; 
            margin: 50px auto; 
            padding: 40px; 
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
        }

        /* --- Fun Aspect: Floating Animation --- */
        .floating-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            animation: float 3s ease-in-out infinite;
            display: inline-block;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        h1 { color: var(--primary-color); margin-bottom: 5px; }
        .sub-header { color: #666; margin-bottom: 30px; font-weight: bold; }

        /* --- Form Styling --- */
        form { display: flex; flex-direction: column; gap: 15px; }
        
        label { text-align: left; font-weight: 600; font-size: 0.9rem; }

        input[type="text"] { 
            padding: 12px; 
            border: 2px solid #ddd; 
            border-radius: 8px; 
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        button { 
            padding: 12px; 
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover { 
            background: #357abd; 
            transform: scale(1.02);
        }

        /* --- Response Box --- */
        #response { 
            margin-top: 30px; 
            padding: 15px; 
            background: #f9f9f9;
            border-left: 5px solid var(--primary-color);
            border-radius: 4px; 
            white-space: pre-wrap; 
            text-align: left;
        }

        .error { color: #e74c3c; margin-top: 10px; font-weight: bold; }
    </style>
</head>
<body>

    <header>
        <div class="logo">Tutor Me!</div>
        <nav>
            <a href="#">Home</a>
            <a href="#">Login</a>
            <a href="#">Settings</a>
            <a href="#">Logout</a>
        </nav>
    </header>

    <div class="container">
        <div class="floating-icon">✏️</div>
        
        <h1>Tutor Me!</h1>
        <p class="sub-header">Enter here</p>

        <form id="groqForm" action="api_handler.php" method="post">
            <label for="question">What are we learning today?</label>
            <input type="text" id="question" name="question" placeholder="e.g. Explain photosynthesis like I'm five" required>
            <button type="submit">Get Answer</button>
        </form>

        <?php if (isset($_GET['response'])): ?>
            <h2>Response:</h2>
            <div id="response">
                <?php echo htmlspecialchars($_GET['response']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
    </div>

</body>
</html>