<?php
include '../include/header.php';
include '../include/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    
    // Handle Image Upload
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $sql = "INSERT INTO candidates (name, description, image) VALUES ('$name', '$description', '$image')";
        if ($conn->query($sql) === TRUE) {
            echo "Candidate added successfully!";
            header("Location:datas.php");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Failed to upload image.";
    }
}
?>

                    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h4>ADD-CANDIDATE</h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="image">Image:</label>
                            <input type="file" id="image" name="image" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Add Candidate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../include/footer.php';
?>