<?php
session_start();
include '../include/db.php';

$usernameErr = $passwordErr = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = $_POST['password'];

 
    if (strlen($username) < 3) {
        $usernameErr = "Username must be at least 3 characters.";
    } 
     else {
        
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $errorMsg = "Username already taken. Please choose another.";
        } else {
           
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Registration successful! Please log in.";
                header("Location: login.php");
                exit();
            } else {
                $errorMsg = "Something went wrong. Try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
    $conn->close();
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    
                    <?php if ($errorMsg): ?>
                        <div class="alert alert-danger"><?= $errorMsg ?></div>
                    <?php endif; ?>

                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control <?= $usernameErr ? 'is-invalid' : '' ?>" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
                            <div class="invalid-feedback"><?= $usernameErr ?></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control <?= $passwordErr ? 'is-invalid' : '' ?>" required>
                            <div class="invalid-feedback"><?= $passwordErr ?></div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../include/footer.php'; ?>
