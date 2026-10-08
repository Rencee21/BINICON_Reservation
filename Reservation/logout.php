<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out</title>
    <style>
        /* CSS for the Logout button */
        .logout-btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #f44336; /* Red color for the logout button */
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; /* Removes underline */
            text-align: center;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #d32f2f; /* Darker red on hover */
        }

        .message {
            text-align: center;
            margin-top: 50px;
            font-size: 20px;
            color: #333;
        }

        .container {
            text-align: center;
            margin-top: 100px; /* Centered on page */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>You have been logged out successfully.</h1>
            <a href="index.php">
                <button class="logout-btn">Back to Home</button>
            </a>
        </div>
    </div>
</body>
</html>
