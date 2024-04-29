<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['username'])) {
    header("location: Drivers Login.php");
    exit;
}

// Connect to the database
$con = mysqli_connect("localhost", "root", "", "finalerd");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit;
}

// Function to retrieve user information
function getUserInfo($con, $username) {
    $stmt = mysqli_prepare($con, "SELECT * FROM account WHERE username=?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    } else {
        echo "User not found.";
        exit;
    }
}

$username = $_SESSION['username'];
$row = getUserInfo($con, $username);

// Handle update
if(isset($_POST['update'])) {
    $firstName = mysqli_real_escape_string($con, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($con, $_POST['last_name']);
    $university = mysqli_real_escape_string($con, $_POST['university_name']);
    $driverLicense = mysqli_real_escape_string($con, $_POST['driver_license']);
    $licensePlate = mysqli_real_escape_string($con, $_POST['license_plate']);
    $yearsOfService = mysqli_real_escape_string($con, $_POST['years_of_service']);

    $updateSql = "UPDATE account SET 
                    first_name = '$firstName', 
                    last_name = '$lastName', 
                    university_name = '$university', 
                    driver_license = '$driverLicense', 
                    license_plate = '$licensePlate', 
                    years_of_service = '$yearsOfService' 
                  WHERE username = '$username'";

    if(mysqli_query($con, $updateSql)) {
        $_SESSION['message'] = 'Update successful.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        // Display error if update fails
        echo "<script>alert('Update failed: " . mysqli_error($con) . "');</script>";
    }
}

// Handle delete
if(isset($_POST['delete'])) {
    $deleteSql = "DELETE FROM account WHERE username = '$username'";
    if(mysqli_query($con, $deleteSql)) {
        echo "<script>alert('Account deleted.'); window.location.href = 'Drivers Login.php';</script>";
    } else {
        echo "<script>alert('Deletion failed.');</script>";
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver's Dashboard</title>
    <style>
        /* General styles */
       /* General styles */
body {
    font-family: Arial, sans-serif;
    background-color: #1c1c1c;
    color: #ffffff;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    width: 40%; 
    background-color: #333333;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); 
    padding: 30px; 
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
}

h1 {
    text-align: center;
    color: #f2f2f2;
    margin-bottom: 20px; 
}

.section {
    width: 100%;
    margin-bottom: 20px;
}

.section-header {
    font-weight: bold;
    margin-bottom: 10px;
    color: #1e88e5;
    text-align: center; 
}

.info-item {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #424242;
    border-radius: 5px;
    width: 100%; 
}

/* Input styles */
input[type="text"], input[type="email"], input[type="submit"] {
    width: 100%;
    padding: 12px; 
    margin-bottom: 10px; 
    border: none;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #0a66c2;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s; /* Smooth transition for hover effect */
}

input[type="submit"]:hover, .button:hover {
    background-color: #084298; /* Darker shade on hover for better interaction feedback */
}

/* CRUD buttons */
.crud-btns {
    display: flex;
    justify-content: space-around;
    width: 100%; 
}

.button {
    padding: 10px 20px;
    background-color: #0a66c2;
    color: #ffffff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    transition: background-color 0.3s; /* Consistency in transitions */
}

.logout-btn {
    width: 100%;
    padding: 12px;
    background-color: #1e88e5;
    color: #0000FF;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.logout-btn:hover {
    background-color: #0f5abc; /* Darker shade on hover for logout button */
}

    </style>

</head>
<body>
    <div class="container">
        <h1>Driver's Dashboard</h1>
        <?php if (isset($row)): ?>
        <form method="post">
            <div class="section-header">Profile</div>
            <input type="text" name="first_name" value="<?php echo $row['first_name']; ?>" required>
            <input type="text" name="last_name" value="<?php echo $row['last_name']; ?>" required>
            <input type="text" name="university_name" value="<?php echo $row['university_name']; ?>" required>
            <input type="text" name="driver_license" value="<?php echo $row['driver_license']; ?>" required>
            <input type="text" name="license_plate" value="<?php echo $row['license_plate']; ?>" required>
            <input type="text" name="years_of_service" value="<?php echo $row['years_of_service']; ?>" required>
            <button type="submit" name="update" onclick="return confirm('Are you sure you want to update your account?');">Update Information</button>
            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
        </form>
        <?php else: ?>
            <p>User data not available.</p>
        <?php endif; ?>
        <a href="Drivers Login.php" class="button" style="width: 100%; margin-top: 20px;">Logout</a>
    </div>
</body>
</html>



