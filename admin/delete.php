<?php
include '../include/header.php';
include '../include/db.php';
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM candidates WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Candidate deleted successfully!";
        header("Location:datas.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<?php include '../include/footer.php';
?>