<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <style>
        /* Add your CSS styles here */
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar .profile {
            float: right;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        /* Main content container */
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333333;
        }
    </style>
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <a href="access.php">Access</a>
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="<?php echo getProfileLink(); ?>" class="profile">&#x1F464;</a> <!-- Profile icon -->
    </div>

    <!-- Main content -->
    <div class="container">
        <h1>Welcome to Our Website!</h1>
        <p>This is the landing page content. You can customize it as needed.</p>
    </div>

    <?php
        // Function to determine the profile link based on where the username exists
        function getProfileLink() {
            if (isset($_SESSION['username'])) {
                $uname = $_SESSION['username'];
                $con = mysqli_connect("localhost", "root", "", "finalerd");
        
                // Check if the username has a corresponding student ID in the student_account table
                $student_query = "SELECT * FROM student_account WHERE username='$uname'";
                $student_result = mysqli_query($con, $student_query);
                $student_count = mysqli_num_rows($student_result);
        
                // Close connection
                mysqli_close($con);
        
                if ($student_count == 1) {
                    return 'Student Edit.php';
                } else {
                    return 'Drivers Edit.php';
                }
            }
            // Default to home page if username is not set
            return '#';
        }
    ?>
</body>
</html>
