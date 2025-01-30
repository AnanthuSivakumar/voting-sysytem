<?php
session_start();
include '../include/header.php';
include '../include/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$candidate_id = $_POST['candidate_id'];


$check_vote = $conn->prepare("SELECT * FROM votes WHERE user_id = ?");
$check_vote->bind_param("i", $user_id);
$check_vote->execute();
$check_vote->store_result();

if ($check_vote->num_rows > 0) {
    echo "You have already voted!";
    exit();
}

$stmt = $conn->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $candidate_id);
$stmt->execute();


$update_votes = $conn->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = ?");
$update_votes->bind_param("i", $candidate_id);
$update_votes->execute();

header("Location: index.php");
exit();
?>
