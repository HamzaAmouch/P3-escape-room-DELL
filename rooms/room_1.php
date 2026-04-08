<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 1");
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Verlaten Pretpark</title>

<style>
body {
  background: url("../img/abandoned-amusement-park-features-rusty-roller-coaster-large-ferris-wheel-weathered-merry-go-round-decaying-bumper-cars-397454253.webp") no-repeat center center fixed;
  background-size: cover;
  color: white;
  text-align: center;
  font-family: Arial;
  text-shadow: 2px 2px 5px #000;
  margin: 0;
  overflow-x: hidden;
}

.box {
  width: 120px;
  height: 120px;
  background: darkred;
  display: none;
  justify-content: center;
  align-items: center;
  margin: 10px auto;
  cursor: pointer;
}

.box.active {
  display: flex;
}

.modal, .game {
  display: none;
  background: #111;
  padding: 20px;
  margin-top: 20px;
}
</style>

</head>

<body>
  <h1>Team: DELL</h1>

<h1>🎢 Verlaten Pretpark</h1>
<p>Speel eerst het spel...</p>

<!-- GAME AREA -->
<div id="game1" class="game">
  <h2>Klik spel</h2>
  <p>Klik 5 keer!</p>
  <button onclick="clickGame()">Klik</button>
  <p id="clickCount">0</p>
</div>

<div id="game2" class="game">
  <h2>Raad het getal (1-5)</h2>
  <input id="guess">
  <button onclick="guessGame()">Raad</button>
</div>

<div id="game3" class="game">
  <h2>Kies de juiste knop</h2>
  <button onclick="wrong()">1</button>
  <button onclick="correct()">2</button>
  <button onclick="wrong()">3</button>
</div>

<!-- BOXES -->
<?php foreach ($riddles as $index => $riddle) : ?>
<div class="box"
  onclick="openModal(<?php echo $index; ?>)"
  data-answer="<?php echo strtolower($riddle['answer']); ?>"
  data-riddle="<?php echo $riddle['riddle']; ?>">
  🎁 Vraag <?php echo $index + 1; ?>
</div>
<?php endforeach; ?>

<!-- MODAL -->
<div class="modal" id="modal">
  <p id="riddleText"></p>
  <input id="answerInput">
  <button onclick="checkAnswer()">Check</button>
  <p id="feedback"></p>
</div>

<script>
let currentBox = null;
let boxes = document.querySelectorAll('.box');

// start game 1
document.getElementById('game1').style.display = 'block';

// GAME 1
let clicks = 0;
function clickGame() {
  clicks++;
  document.getElementById('clickCount').innerText = clicks;

  if (clicks >= 5) {
    alert("Game gehaald!");
    document.getElementById('game1').style.display = 'none';
    boxes[0].classList.add('active');
  }
}

// GAME 2
function guessGame() {
  let random = 3; // simpel gehouden
  let input = document.getElementById('guess').value;

  if (input == random) {
    alert("Goed!");
    document.getElementById('game2').style.display = 'none';
    boxes[1].classList.add('active');
  }
}

// GAME 3
function correct() {
  alert("Goed!");
  document.getElementById('game3').style.display = 'none';
  boxes[2].classList.add('active');
}

function wrong() {
  alert("Fout!");
}

// OPEN RIDDLE
function openModal(index) {
  currentBox = boxes[index];
  document.getElementById('riddleText').innerText = currentBox.dataset.riddle;
  document.getElementById('modal').style.display = 'block';
}

// CHECK ANSWER
function checkAnswer() {
  let input = document.getElementById('answerInput').value.toLowerCase();
  let correct = currentBox.dataset.answer;

  if (input === correct) {
    alert("Goed!");

    document.getElementById('modal').style.display = 'none';

    // volgende game starten
    if (currentBox === boxes[0]) {
      document.getElementById('game2').style.display = 'block';
    }
    if (currentBox === boxes[1]) {
      document.getElementById('game3').style.display = 'block';
    }
    if (currentBox === boxes[2]) {
      alert("🎉 Je hebt alles gehaald!");
      window.location.href = 'room_2.php';
    }

  } else {
    document.getElementById('feedback').innerText = "Fout!";
  }
}
</script>

</body>
</html>