// Game state
let gameActive = false;
let timeLeft = 300; // 5 minuten in seconden
let timerInterval = null;
let gameWon = false;
let gameLost = false;
let currentPlayer = '';

// Items die gevonden kunnen worden
const items = [
    'Oude sleutel',
    'Verfrommeld briefje',
    'Kapotte lichtslinger',
    'Clown masker',
    'Kaartje uit 1985'
];

// DOM elementen
const timerDisplay = document.getElementById('timer');
const timerBar = document.getElementById('timer-bar');
const codeInput = document.getElementById('code-input');
const checkCodeBtn = document.getElementById('check-code');
const startGameBtn = document.getElementById('start-game');
const playerNameInput = document.getElementById('player-name');
const winScreen = document.getElementById('win-screen');
const loseScreen = document.getElementById('lose-screen');
const playAgainWin = document.getElementById('play-again-win');
const playAgainLose = document.getElementById('play-again-lose');
const winTimeDisplay = document.getElementById('win-time');
const itemsContainer = document.getElementById('items');
const highscoresBtn = document.getElementById('highscores-btn');
const highscoresModal = document.getElementById('highscores-modal');
const highscoresList = document.getElementById('highscores-list');
const closeHighscores = document.getElementById('close-highscores');

// Event listeners
startGameBtn.addEventListener('click', startGame);
checkCodeBtn.addEventListener('click', checkCode);
playAgainWin.addEventListener('click', resetGame);
playAgainLose.addEventListener('click', resetGame);
highscoresBtn.addEventListener('click', showHighscores);
closeHighscores.addEventListener('click', () => {
    highscoresModal.classList.add('hidden');
});

// Enter key voor code invoer
codeInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && gameActive) {
        checkCode();
    }
});

// Timer functie
function startTimer() {
    timerInterval = setInterval(() => {
        if (!gameActive || gameWon || gameLost) return;
        
        timeLeft--;
        updateTimerDisplay();
        
        if (timeLeft <= 0) {
            gameLost = true;
            gameActive = false;
            clearInterval(timerInterval);
            showLoseScreen();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    timerBar.value = timeLeft;
}

// Spel starten
function startGame() {
    currentPlayer = playerNameInput.value.trim() || 'Anonieme speler';
    
    gameActive = true;
    gameWon = false;
    gameLost = false;
    timeLeft = 300;
    
    updateTimerDisplay();
    startTimer();
    
    // Toon gevonden items
    showItems();
    
    // Schakel invoer in
    codeInput.disabled = false;
    codeInput.value = '';
    codeInput.focus();
    
    // Verberg win/verlies schermen
    winScreen.classList.add('hidden');
    loseScreen.classList.add('hidden');
    
    // Kleine animatie
    document.body.style.animation = 'none';
    document.body.offsetHeight;
    document.body.style.animation = 'flicker 3s infinite';
}

// Items tonen
function showItems() {
    itemsContainer.innerHTML = '';
    items.forEach(item => {
        const itemElement = document.createElement('span');
        itemElement.className = 'item';
        itemElement.textContent = item;
        itemsContainer.appendChild(itemElement);
    });
}

// Code controleren (de puzzel oplossing)
function checkCode() {
    if (!gameActive || gameWon || gameLost) return;
    
    const code = codeInput.value.trim();
    
    // Het pretpark sloot in 1985
    if (code === '1985') {
        gameWon = true;
        gameActive = false;
        gameLost = false;
        clearInterval(timerInterval);
        
        const timeTaken = 300 - timeLeft;
        const minutes = Math.floor(timeTaken / 60);
        const seconds = timeTaken % 60;
        winTimeDisplay.textContent = `Ontsnapt in ${minutes}:${seconds.toString().padStart(2, '0')} minuten!`;
        
        showWinScreen();
        saveHighscore(currentPlayer, timeTaken);
    } else {
        // Foute code - trillen animatie
        codeInput.style.animation = 'shake 0.5s';
        setTimeout(() => {
            codeInput.style.animation = '';
        }, 500);
        
        // Hint bij foute code
        if (code.length === 4) {
            const hint = document.createElement('div');
            hint.className = 'hint-message';
            hint.textContent = '❌ Foute code! Probeer opnieuw...';
            hint.style.color = '#ff6b6b';
            hint.style.marginTop = '0.5rem';
            
            const oldHint = document.querySelector('.hint-message');
            if (oldHint) oldHint.remove();
            
            document.querySelector('.puzzle-section').appendChild(hint);
            
            setTimeout(() => {
                if (hint.parentNode) hint.remove();
            }, 2000);
        }
    }
}

// Schud animatie voor foute code
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);

// Scherm tonen functies
function showWinScreen() {
    winScreen.classList.remove('hidden');
    
    // Speel winst geluid (als browser het toestaat)
    try {
        const audio = new Audio('data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4GmmQQUeqbE6C0A=');
        audio.play();
    } catch (e) {
        console.log('Audio niet afgespeeld (autoplay blocked)');
    }
}

function showLoseScreen() {
    loseScreen.classList.remove('hidden');
    
    // Griezelig geluid
    try {
        const audio = new Audio('data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4GmmQQUeqbE6C0A=');
        audio.play();
    } catch (e) {}
}

// Spel resetten
function resetGame() {
    winScreen.classList.add('hidden');
    loseScreen.classList.add('hidden');
    startGame();
}

// Highscore opslaan in database via PHP
function saveHighscore(playerName, timeInSeconds) {
    fetch('save_score.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `name=${encodeURIComponent(playerName)}&time=${timeInSeconds}`
    })
    .then(response => response.json())
    .then(data => {
        console.log('Score opgeslagen:', data);
    })
    .catch(error => {
        console.error('Fout bij opslaan score:', error);
    });
}

// Highscores ophalen en tonen
function showHighscores() {
    fetch('get_highscores.php')
    .then(response => response.json())
    .then(data => {
        highscoresList.innerHTML = '';
        
        if (data.length === 0) {
            highscoresList.innerHTML = '<p>Nog geen highscores beschikbaar</p>';
        } else {
            data.forEach((score, index) => {
                const minutes = Math.floor(score.time / 60);
                const seconds = score.time % 60;
                const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                const item = document.createElement('div');
                item.className = 'highscore-item';
                item.innerHTML = `
                    <span class="rank">#${index + 1}</span>
                    <span class="name">${escapeHtml(score.name)}</span>
                    <span class="time">${timeString}</span>
                `;
                highscoresList.appendChild(item);
            });
        }
        
        highscoresModal.classList.remove('hidden');
    })
    .catch(error => {
        console.error('Fout bij ophalen highscores:', error);
        highscoresList.innerHTML = '<p>Fout bij laden highscores</p>';
        highscoresModal.classList.remove('hidden');
    });
}

// HTML escaping voor veiligheid
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Initialisatie
updateTimerDisplay();
codeInput.disabled = true;