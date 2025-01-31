<?php
include '../include/header.php';
include '../include/db.php';
session_start();




if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM candidates WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Candidate deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting candidate!";
    }

    $stmt->close();
    $conn->close();


    header("Location: datas.php");
    exit;
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: datas.php");
    exit;
}

include '../include/footer.php';
?>
