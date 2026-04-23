<?php
session_start();
require_once('../dbcon.php');

// START TIMER bij team aanmaken
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['teamnaam'])) {

    $teamnaam = $_POST['teamnaam'];

    $stmt = $db_connection->prepare("INSERT INTO teams (naam) VALUES (?)");
    $stmt->execute([$teamnaam]);

    $_SESSION['team'] = $teamnaam;
    $_SESSION['starttijd'] = time(); // ⬅️ TIMER START

    header("Location: team.php");
    exit;
}

// STOP TIMER (escape knop)
if (isset($_POST['finish'])) {

    $eindtijd = time();
    $totale_tijd = $eindtijd - $_SESSION['starttijd']; // seconden

    $datum = date("Y-m-d H:i:s");

    $stmt = $db_connection->prepare("UPDATE teams SET score=?, eindtijd=? WHERE naam=?");
    $stmt->execute([$totale_tijd, $datum, $_SESSION['team']]);
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Escape Room</title>

<style>
body {
    background: #020617;
    color: white;   
    font-family: Arial;
    text-align: center;
}

.container {
    margin-top: 100px;
}

button {
    padding: 10px;
    background: gold;
    border: none;
    cursor: pointer;
}
</style>

</head>
<body>

<div class="container">

<h1>Escape Room</h1>

<?php if (isset($_SESSION['team'])): ?>

    <p>Team: <?= $_SESSION['team'] ?></p>

    <!-- TIMER LIVE -->
    <p id="timer">Tijd: 0 sec</p>

    <!-- STOP BUTTON -->
    <form method="POST">
        <button name="finish">Ontsnapt!</button>
    </form>

<?php else: ?>

    <!-- FORM -->
    <form method="POST">
        <input type="text" name="teamnaam" placeholder="Teamnaam" required>
        <button type="submit">Start spel</button>
    </form>

<?php endif; ?>

</div>

<script>
// LIVE TIMER IN BROWSER
<?php if (isset($_SESSION['starttijd'])): ?>
    let start = <?= $_SESSION['starttijd'] ?>;

    function updateTimer() {
        let now = Math.floor(Date.now() / 1000);
        let diff = now - start;
        document.getElementById("timer").innerText = "Tijd: " + diff + " sec";
    }

    setInterval(updateTimer, 1000);
<?php endif; ?>
</script>

</body>
</html>