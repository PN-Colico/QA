<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("location: Drivers Login.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION['username'];

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "finalerd");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Retrieve user information from the account table
$sql = "SELECT * FROM account WHERE username='$username'";
$result = mysqli_query($con, $sql);

// Check if user exists
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    echo "User not found.";
    exit;
}

// Close the database connection
mysqli_close($con);

// Dummy data from access.php
$lp = "LicensePlateData";
$wl = "WheelchairLiftData";
$ramp = "RampData";
$sid = "StudentIDData";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver's Dashboard</title>
    <style>
        /* Add your CSS styles here */
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

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

        .section {
            margin-bottom: 20px;
        }

        .section-header {
            font-weight: bold;
            margin-bottom: 10px;
            color: #1e88e5;
        }

        .info-item {
            margin-bottom: 5px;
        }

        /* Logout button */
        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #1e88e5;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background-color: #0f5abc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Driver's Dashboard</h1>
        <!-- User information section -->
        <div class="section">
            <div class="section-header">User Information</div>
            <div class="info-item"><strong>Username:</strong> <?php echo isset($row['username']) ? $row['username'] : ''; ?></div>
            <div class="info-item"><strong>Password:</strong> <?php echo isset($row['password']) ? $row['password'] : ''; ?></div>
            <div class="info-item"><strong>First Name:</strong> <?php echo isset($row['first_name']) ? $row['first_name'] : ''; ?></div>
            <div class="info-item"><strong>Middle Name:</strong> <?php echo isset($row['middle_name']) ? $row['middle_name'] : ''; ?></div>
            <div class="info-item"><strong>Last Name:</strong> <?php echo isset($row['last_name']) ? $row['last_name'] : ''; ?></div>
            <div class="info-item"><strong>University Name:</strong> <?php echo isset($row['university_name']) ? $row['university_name'] : ''; ?></div>
            <div class="info-item"><strong>Driver's License:</strong> <?php echo isset($row['driver_license']) ? $row['driver_license'] : ''; ?></div>
            <div class="info-item"><strong>License Plate:</strong> <?php echo isset($row['license_plate']) ? $row['license_plate'] : ''; ?></div>
            <div class="info-item"><strong>Years of Service:</strong> <?php echo isset($row['years_of_service']) ? $row['years_of_service'] : ''; ?></div>
        </div>
        
        <!-- Access information section -->
        <div class="section">
            <div class="section-header">Access Information</div>
            <div class="info-item"><strong>License Plate:</strong> <?php echo $lp; ?></div>
            <div class="info-item"><strong>Wheelchair Lift:</strong> <?php echo $wl; ?></div>
            <div class="info-item"><strong>Ramp:</strong> <?php echo $ramp; ?></div>
            <div class="info-item"><strong>Student ID:</strong> <?php echo $sid; ?></div>
        </div>

        <!-- Logout button -->
        <a href="Drivers Login.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>



