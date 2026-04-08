<?php
session_start();

// Vernietig alle sessiegegevens
session_destroy();

// Stuur een succesvolle JSON response terug
header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>