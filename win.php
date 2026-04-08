<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gewonnen!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #0f9b8e 0%, #34d399 50%, #10b981 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .container {
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 50px;
            border-radius: 24px;
            box-shadow: 0 0 50px rgba(16, 185, 129, 0.4);
            max-width: 640px;
            margin: 20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        h1 {
            font-size: 64px;
            margin-bottom: 16px;
            color: #fff;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.5);
        }
        .hero {
            font-size: 96px;
            margin: 20px 0;
        }
        p {
            font-size: 20px;
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .button {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            font-size: 18px;
            margin: 10px;
            border-radius: 999px;
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .button:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); }
        .button:active { transform: translateY(0); }
        .button.exit { background: linear-gradient(135deg, #fbbf24 0%, #f97316 100%); }
    </style>
</head>
<body>
    <div class="container">
        <div class="hero">🏆</div>
        <h1>Gefeliciteerd!</h1>
        <p>Je hebt alle drie de vragen goed beantwoord en de escape room gewonnen.</p>
        <p>Goed gedaan, het team mag door naar de volgende uitdaging.</p>
        <button class="button" onclick="restartGame()">🔄 Opnieuw spelen</button>
        <button class="button exit" onclick="exitGame()">🚪 Naar startpagina</button>
    </div>
    <script>
        function restartGame() {
            fetch('reset_game.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => { window.location.href = 'rooms/room_1.php'; })
            .catch(() => { window.location.href = 'rooms/room_1.php'; });
        }
        function exitGame() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>
