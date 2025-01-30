<?php
include '../include/header.php';
include '../include/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM candidates WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
   
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    } else {
        $image = $row['image'];
    }

    $sql = "UPDATE candidates SET name='$name', description='$description', image='$image' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Candidate updated successfully!";
        header("Location:datas.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <div class="container mt-5 ">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Update Candidate</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="form-control" required><?php echo htmlspecialchars($row['description']); ?></textarea>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label>Current Image:</label><br>
                            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="120" class="mb-2"><br>
                            <label for="image">Upload New Image:</label>
                            <input type="file" id="image" name="image" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-success w-100">Update Candidate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php include '../include/footer.php';
?>