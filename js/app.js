let answerStates = {};
let currentBoxIndex = null;

// Deze functie opent de modal en toont de vraag
function openModal(index) {
  currentBoxIndex = index;

  // Zoek het element met de class 'box' en het bijbehorende data-index
  let box = document.querySelector(`.box[data-index='${index}']`);

  // Haal de vraag en het juiste antwoord uit de dataset van de box
  let riddleText = box.dataset.riddle;
  let correctAnswer = box.dataset.answer;

  // Zet de vraagtekst in het modalvenster
  document.getElementById('riddle').innerText = riddleText;

  // Zet het correcte antwoord in de modal, zodat we het later kunnen vergelijken
  document.getElementById('modal').dataset.answer = correctAnswer;
  document.getElementById('modal').dataset.index = index;

  // Maak het antwoordveld leeg
  document.getElementById('answer').value = '';
  document.getElementById('feedback').innerText = '';

  // Toon de overlay en de modal door de display-stijl te veranderen naar 'block'
  document.getElementById('overlay').style.display = 'block';
  document.getElementById('modal').style.display = 'block';
}

// Deze functie sluit de modal en de overlay
function closeModal() {
  // Zet de overlay en modal weer op 'none' zodat ze niet meer zichtbaar zijn
  document.getElementById('overlay').style.display = 'none';
  document.getElementById('modal').style.display = 'none';

  // Maak de feedback tekst leeg
  document.getElementById('feedback').innerText = '';
}

// Deze functie controleert het antwoord
function checkAnswer() {
  let input = document.getElementById('answer').value.toLowerCase();
  let correct = document.getElementById('modal').dataset.answer;

  // Haal het juiste antwoord op uit de modal
  let correctAnswer = document.getElementById('modal').dataset.answer;
  let index = document.getElementById('modal').dataset.index;

  // Haal het feedback element op om de gebruiker te informeren
  let feedback = document.getElementById('feedback');

  if (!index) {
    feedback.innerText = 'Er is geen vraag geselecteerd.';
    feedback.style.color = 'orange';
    return;
  }

  if (answerStates[index]) {
    feedback.innerText = 'Deze vraag is al beantwoord.';
    feedback.style.color = 'orange';
    return;
  }

  // Vergelijk het antwoord van de gebruiker met het juiste antwoord (hoofdlettergevoeligheid negeren)
  if (userAnswer.toLowerCase() === correctAnswer.toLowerCase()) {
    // Als het antwoord juist is, geef positieve feedback
    feedback.innerText = 'Correct! Goed gedaan!';
    feedback.style.color = 'green';

    answerStates[index] = 'correct';
    document.querySelector(`.box[data-index='${index}']`).classList.add('box-correct');

    // Sluit de modal na 1 seconde
    setTimeout(closeModal, 1000);
  } else {
    // Als het antwoord fout is, geef negatieve feedback
    feedback.innerText = 'Fout, vraag is vastgelegd als fout.';
    feedback.style.color = 'red';

    answerStates[index] = 'wrong';
    document.querySelector(`.box[data-index='${index}']`).classList.add('box-wrong');
  }

  checkGameResult();
}

function checkGameResult() {
  const totalBoxes = document.querySelectorAll('.box').length;
  const answeredCount = Object.keys(answerStates).length;
  const correctCount = Object.values(answerStates).filter(value => value === 'correct').length;
  const wrongCount = Object.values(answerStates).filter(value => value === 'wrong').length;

  if (answeredCount !== totalBoxes) {
    return;
  }

  if (correctCount === totalBoxes) {
    window.location.href = '../win.php';
  } else if (wrongCount === totalBoxes) {
    window.location.href = '../lose.php';
  }
}