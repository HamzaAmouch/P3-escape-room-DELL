<?php
session_start();
require_once('../dbcon.php');
 
try {
    if (isset($_POST['save_review'])) {
 
        if (!isset($_SESSION['team_name'])) {
            echo "Geen team gevonden. Maak eerst een team aan.";
            exit;
        }
 
        $team_name = $_SESSION['team_name'];
        $rating = $_POST['rating'];
        $difficulty = $_POST['difficulty'];
        $comment = $_POST['comment'];
 
        if (!$rating || !$difficulty || !$comment) {
            echo "Beoordeling, moeilijkheid en review zijn verplicht.";
            exit;
        }
 
        if ($rating < 1 || $rating > 5) {
            echo "De beoordeling moet tussen 1 en 5 zijn.";
            exit;
        }
 
        $query = "INSERT INTO reviews (team_name, rating, difficulty, comment)
                  VALUES (:team_name, :rating, :difficulty, :comment)";
        $query_run = $db_connection->prepare($query);
 
        $query_run->bindParam(':team_name', $team_name);
        $query_run->bindParam(':rating', $rating);
        $query_run->bindParam(':difficulty', $difficulty);
        $query_run->bindParam(':comment', $comment);
 
        if ($query_run->execute()) {
            header("Location: show_reviews.php");
            exit;
        } else {
            echo "Er is een fout opgetreden bij het opslaan van de review.";
        }
    } else {
        echo "Ongeldige aanvraag.";
    }
} catch (PDOException $e) {
    echo "Fout bij de databaseverbinding: " . $e->getMessage();
}
 