<?php
include '../include/header.php';
include '../include/db.php';

// Fetch vote count for each candidate
$query = "SELECT candidates.name, candidates.photo, COUNT(votes.id) AS vote_count
          FROM candidates
          LEFT JOIN votes ON candidates.id = votes.candidate_id
          GROUP BY candidates.id";
$result = $conn->query($query);

// Prepare data for Chart.js
$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['name'];
    $data[] = $row['vote_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <h1>Vote Results</h1>
    
    <canvas id="voteChart" width="400" height="200"></canvas>

    <script>
        const ctx = document.getElementById('voteChart').getContext('2d');
        const voteData = {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Votes',
                data: <?= json_encode($data) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const config = {
            type: 'bar',
            data: voteData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const voteChart = new Chart(ctx, config);
    </script>
</body>
</html>
