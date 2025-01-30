<?php
include '../include/header.php';
include '../include/db.php';
session_start();


$sql = "SELECT * FROM candidates";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate List & Vote Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            mar
        }
        h1, h2 {
          text-align: center;
          margin-top: 49px;
}

        table {
            width: 80%;
            margin: 20px auto;
            text-align: center;
            
        }
        .table-responsive {
            margin-top: 30px;
        }
        .vote-table th, .vote-table td {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Candidate List</h2>  
    <a href="logout.php" class="btn btn-danger btn-sm mb-2">Log Out</a>
    <a href="create.php" class="btn btn-primary btn-sm mb-2">ADD-CANDIDATE</a>

    <?php if ($result->num_rows > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["name"]); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row["description"])); ?></td>
                            <td><img src="uploads/<?php echo htmlspecialchars($row["image"]); ?>" width="100" class="rounded"></td>
                            <td>
                                <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this candidate?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning text-center">No candidates found.</div>
    <?php } ?>
</div>

<!-- Fetch Vote Results -->
<?php
$query = "SELECT candidates.name, candidates.image, COUNT(votes.id) AS vote_count
          FROM candidates
          LEFT JOIN votes ON candidates.id = votes.candidate_id
          GROUP BY candidates.id";
$vote_results = $conn->query($query);
?>

<div class="container mt-5">
    <h1>Total Vote Results</h1>
    
    <table class="table table-bordered vote-table">
        <thead class="table-dark">
            <tr>
                <th>Candidate Name</th>
                <th>Total Votes</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $vote_results->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['vote_count']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../include/footer.php';?>
</body>
</html>
