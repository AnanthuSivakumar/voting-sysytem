<?php
session_start();
include '../include/db.php';
include '../include/header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM candidates";
$result = $conn->query($query);

// Check if user has already voted
$vote_check = $conn->prepare("SELECT * FROM votes WHERE user_id = ?");
$vote_check->bind_param("i", $user_id);
$vote_check->execute();
$vote_result = $vote_check->get_result();
$has_voted = $vote_result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vote</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    
<div class="container mt-5">
    <h2 class="text-center my-4">Vote for a Candidate</h2>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-lg">
                    <img src="../admin/uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        
                        <?php if (!$has_voted): ?>
                            <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#confirmVoteModal" data-candidate-id="<?php echo $row['id']; ?>">Vote</button>
                        <?php else: ?>
                            <button class="btn btn-secondary w-100" disabled>You've already voted</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="text-center mt-4">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<!-- Vote Confirmation Modal -->
<div class="modal fade" id="confirmVoteModal" tabindex="-1" aria-labelledby="voteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="voteModalLabel">Confirm Your Vote</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to vote for this candidate? You cannot change your vote later.
            </div>
            <div class="modal-footer">
                <form id="voteForm" method="post" action="vote.php">
                    <input type="hidden" name="candidate_id" id="candidateId">
                    <button type="submit" class="btn btn-success">Confirm Vote</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    var confirmVoteModal = document.getElementById('confirmVoteModal');
    confirmVoteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var candidateId = button.getAttribute('data-candidate-id');
        document.getElementById('candidateId').value = candidateId;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
