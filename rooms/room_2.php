<?php
require_once('../dbcon.php');

try {
  $stmt = $db_connection->query("SELECT * FROM riddles WHERE roomId = 2");
  $riddles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Databasefout: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Escape Room 2 – Verlaten Pretpark</title>

  <style>
    /* === VERLATEN PRETPARK THEMA === */

    body {
      background: black;
      font-family: 'Courier New', monospace;
      color: #fff;
      text-shadow: 2px 2px 5px #000;
      margin: 0;
      overflow-x: hidden;
    }

    h1 {
      text-align: center;
      margin-top: 40px;
      font-size: 40px;
      letter-spacing: 3px;
      animation: flicker 3s infinite;
    }

    @keyframes flicker {
      0%, 100% { opacity: 1; }
      5% { opacity: 0.6; }
      7% { opacity: 1; }
      10% { opacity: 0.2; }
      11% { opacity: 1; }
      50% { opacity: 0.85; }
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 50px;
      padding-top: 60px;
    }

    .box {
      width: 150px;
      height: 150px;
      background: rgba(20, 20, 20, 0.7);
      border: 3px solid #ffcc00;
      border-radius: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 22px;
      cursor: pointer;
      transition: .3s ease;
      position: relative;
      box-shadow: 0 0 15px #000;
    }

    .box.hidden {
      display: none;
    }

    .box:hover {
      background: rgba(255, 200, 0, 0.2);
      transform: scale(1.07);
      box-shadow: 0 0 25px #ffcc00;
    }

    .box::after {
      content: "";
      position: absolute;
      width: 200px;
      height: 200px;
      background: url("../img/spiderweb.png") no-repeat center/contain;
      opacity: 0.15;
      pointer-events: none;
    }

    /* Overlay */
    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(2px);
      display: none;
    }

    /* Modaal */
    .modal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: #fff7d1;
      width: 350px;
      padding: 20px;
      text-align: center;
      color: #222;
      border-radius: 10px;
      border: 3px solid #cc9900;
      box-shadow: 0 0 25px black;
      display: none;
    }

    .modal h2 {
      color: #663300;
    }

    button {
      padding: 10px 20px;
      background: #ffcc00;
      border: none;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
      font-weight: bold;
    }

    button:hover {
      background: #e6b800;
    }
  </style>

</head>

<body>

  <h1>Escape Room – Verlaten Pretpark</h1>

  <div class="container">
    <?php foreach ($riddles as $index => $riddle) : ?>
    <div class="box box<?php echo $index + 1; ?> <?php echo $index > 0 ? 'hidden' : ''; ?>"
      onclick="openModal(<?php echo $index; ?>)"
      data-index="<?php echo $index; ?>"
      data-riddle="<?php echo htmlspecialchars($riddle['riddle']); ?>"
      data-answer="<?php echo htmlspecialchars($riddle['answer']); ?>">
      🎡 Box <?php echo $index + 1; ?>
    </div>
    <?php endforeach; ?>
  </div>

  <section class="overlay" id="overlay" onclick="closeModal()"></section>

  <section class="modal" id="modal">
    <h2>Escape Room Vraag</h2>
    <p id="riddle"></p>
    <input type="text" id="answer" placeholder="Typ je antwoord">
    <button onclick="checkAnswer()">Verzenden</button>
    <p id="feedback"></p>
  </section>

  <script>
    let currentIndex = null;
    const totalRiddles = <?php echo count($riddles); ?>;

    function openModal(index) {
      currentIndex = index;
      const box = document.querySelector(`.box${index + 1}`);
      document.getElementById('riddle').innerText = box.dataset.riddle;
      document.getElementById('feedback').innerText = '';
      document.getElementById('overlay').style.display = 'block';
      document.getElementById('modal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('modal').style.display = 'none';
    }

    function checkAnswer() {
      const box = document.querySelector(`.box${currentIndex + 1}`);
      const correct = box.dataset.answer.toLowerCase();
      const userInput = document.getElementById('answer').value.toLowerCase();

      if (userInput === correct) {
        if (currentIndex + 1 === totalRiddles) {
          document.getElementById('feedback').innerText = 'Je hebt de kamer af! Door naar de volgende...';
        } else {
          document.getElementById('feedback').innerText = '✅ Goed gedaan! De volgende box opent...';
        }
        unlockNextBox();
        closeModalAfterDelay();
      } else {
        document.getElementById('feedback').innerText = '❌ Helaas, probeer opnieuw!';
      }
    }

    function unlockNextBox() {
      const nextIndex = currentIndex + 1;
      if (nextIndex < totalRiddles) {
        const nextBox = document.querySelector(`.box${nextIndex + 1}`);
        nextBox.classList.remove('hidden');
      } else {
        // Alle riddles klaar, ga naar kamer 3
        setTimeout(() => {
          window.location.href = 'room_3.php';
        }, 2000);
      }
    }

    function closeModalAfterDelay() {
      setTimeout(() => {
        closeModal();
        document.getElementById('answer').value = '';
      }, 1300);
    }
  </script>

</body>

</html>