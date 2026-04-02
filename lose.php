<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Over - Tijd verlopen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            animation: fadeIn 1s ease-in;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .container {
            text-align: center;
            background: rgba(0, 0, 0, 0.8);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 0 50px rgba(255, 0, 0, 0.3);
            max-width: 600px;
            margin: 20px;
            border: 2px solid #ff0000;
            animation: shake 0.5s ease-in-out;
        }
        
        h1 {
            font-size: 64px;
            margin-bottom: 20px;
            color: #ff0000;
            text-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
            animation: pulse 2s infinite;
        }
        
        .clock-icon {
            font-size: 80px;
            margin-bottom: 20px;
            display: inline-block;
            animation: pulse 1s infinite;
        }
        
        .message {
            font-size: 24px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .time-over {
            font-size: 32px;
            font-weight: bold;
            color: #ff6666;
            margin: 20px 0;
            padding: 15px;
            background: rgba(255, 0, 0, 0.2);
            border-radius: 10px;
            border-left: 5px solid #ff0000;
        }
        
        .button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            margin: 10px;
            border-radius: 50px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
        
        .button:active {
            transform: translateY(0);
        }
        
        .restart-btn {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .exit-btn {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stats {
            margin-top: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 14px;
        }
        
        .sad-face {
            font-size: 100px;
            margin: 20px 0;
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        .warning {
            color: #ff6666;
            animation: blink 1s infinite;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="clock-icon">⏰</div>
    <h1>GAME OVER</h1>
    <div class="sad-face">😵</div>
    
    <div class="message">
        <strong>Je bent te laat!</strong><br>
        De tijd is verlopen...
    </div>
    
    <div class="time-over">
        ⏱️ TIJD IS OPGEGETEN ⏱️
    </div>
    
    <div class="message">
        Je hebt niet alle puzzels op tijd kunnen oplossen.<br>
        De escape room is mislukt!
    </div>
    
    <div class="warning">
        ⚠️ Volgende keer sneller zijn! ⚠️
    </div>
    
    <div>
        <button class="button restart-btn" onclick="restartGame()">🔄 Opnieuw beginnen</button>
        <button class="button exit-btn" onclick="exitGame()">🚪 Afsluiten</button>
    </div>
    
    <div class="stats">
        <strong>📊 Escape Room Statistieken:</strong><br>
        <span id="timeSpent">Tijd besteed: </span><br>
        Helaas heb je de uitdaging niet gehaald.<br>
        Volgende keer beter!
    </div>
</div>

<script>
    // Toon hoe lang de speler bezig was (als die info beschikbaar is)
    function getTimeSpent() {
        <?php
        if (isset($_SESSION['total_time_started'])) {
            $time_spent = time() - $_SESSION['total_time_started'];
            $minutes = floor($time_spent / 60);
            $seconds = $time_spent % 60;
            echo "return '{$minutes} minuten en {$seconds} seconden';";
        } else {
            echo "return 'Onbekend';";
        }
        ?>
    }
    
    // Update de statistieken
    const timeSpentElement = document.getElementById('timeSpent');
    if (timeSpentElement) {
        timeSpentElement.innerHTML = `Tijd besteed: ${getTimeSpent()}`;
    }
    
    // Herstart de game (ga terug naar kamer 1)
    function restartGame() {
        // Stuur een verzoek om de sessie te resetten
        fetch('reset_game.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'room_1.php';
            } else {
                // Fallback: direct naar room 1 gaan
                window.location.href = 'room_1.php';
            }
        })
        .catch(() => {
            // Fallback bij error
            window.location.href = 'room_1.php';
        });
    }
    
    // Sluit de game (of ga naar startpagina)
    function exitGame() {
        const confirmExit = confirm("Weet je zeker dat je wilt stoppen?");
        if (confirmExit) {
            window.location.href = 'index.php';
        }
    }
    
    // Toon extra dramatisch effect bij laden
    window.addEventListener('load', function() {
        // Speel een "buzz" geluid als de browser het toestaat
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.value = 200;
            gainNode.gain.value = 0.1;
            
            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, audioContext.currentTime + 1);
            oscillator.stop(audioContext.currentTime + 1);
        } catch(e) {
            // Geluid niet ondersteund, negeer error
        }
    });
</script>

<?php
// Optioneel: log de loss in de sessie voor statistieken
session_start();
if (!isset($_SESSION['loss_count'])) {
    $_SESSION['loss_count'] = 0;
}
$_SESSION['loss_count']++;
$_SESSION['last_loss_time'] = time();
?>

</body>
</html>