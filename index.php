<?php 

echo "Hello Voidek Webolutions!";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GitHub Actions CI/CD Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #0073e6;
        }
    </style>
</head>
<body>
    <h1>Welcome to GitHub Actions CI/CD Demo VW</h1>
    <p>This is a simple page to test GitHub Actions CI/CD pipeline.</p>
    <footer>
        <p>Last deployment: <span id="timestamp"></span></p>
    </footer>
    <script>
        document.getElementById('timestamp').textContent = new Date().toLocaleString();
    </script>
</body>
</html>
