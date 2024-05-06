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
    $wheelchair = $_POST['wheelchair'];
    $ramp = $_POST['ramp'];

    // Validate license plate format
    if (!preg_match('/^(?:[A-Z]{3}-\d{4}|(?:\d{3}-[A-Z]{3}|[A-Z]-\d{3}-[A-Z]{2}))$/', $licensePlate)) {
        echo "<script>alert('Invalid license plate format. Please use the correct format (e.g., ABC-1234 or 123-ABC or A-123-AB)');</script>";
    } else {
        $updateSql = "UPDATE account SET 
                        first_name = '$firstName', 
                        last_name = '$lastName', 
                        university_name = '$university', 
                        driver_license = '$driverLicense', 
                        license_plate = '$licensePlate', 
                        years_of_service = '$yearsOfService',
                        wheelchair = '$wheelchair',
                        ramp = '$ramp'
                      WHERE username = '$username'";

        if(mysqli_query($con, $updateSql)) {
            $_SESSION['message'] = 'Update successful.';
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<script>alert('Update failed: " . mysqli_error($con) . "');</script>";
        }
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
        input[type="text"], input[type="email"], input[type="submit"], select {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border: none;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"], .button {
            background-color: #0a66c2;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover, .button:hover {
            background-color: #084298;
        }
        .button, .logout-btn {
            padding: 10px 20px;
            background-color: #0a66c2;
            color: #ffffff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s;
            width: 100%;
            display: block;
            box-sizing: border-box;
            margin-top: 20px;
        }
        .logout-btn {
            background-color: #1e88e5;
        }
        .logout-btn:hover {
            background-color: #0f5abc;
        }
        .error {
            color: red;
            display: block;
        }
    </style>
    <script>
        function validateLicensePlate() {
            var lpInput = document.getElementById('license_plate');
            var errorSpan = document.getElementById('lpError');
            var lpValue = lpInput.value;
            var regex = /^(?:[A-Z]{3}-\d{4}|(?:\d{3}-[A-Z]{3}|[A-Z]-\d{3}-[A-Z]{2}))$/;

            if (!regex.test(lpValue)) {
                errorSpan.textContent = 'Invalid format. Use LLL-DDDD for cars or DDD-LLL/L-DDD-LL for motorcycles.';
                return false;
            }
            errorSpan.textContent = '';
            return true;
        }
    </script>
</head>
<body>
<div class="container">
        <h1>Driver's Dashboard</h1>
        <?php if (isset($row)): ?>
        <form method="post" onsubmit="return validateLicensePlate()">
            <div class="section-header">Profile</div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $row['first_name']; ?>" required>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $row['last_name']; ?>" required>
            <label for="university_name">University Name:</label>
            <input type="text" id="university_name" name="university_name" value="<?php echo $row['university_name']; ?>" required>
            <label for="driver_license">Driver License:</label>
            <input type="text" id="driver_license" name="driver_license" value="<?php echo $row['driver_license']; ?>" required>
            <label for="license_plate">License Plate:</label>
            <input type="text" id="license_plate" name="license_plate" value="<?php echo $row['license_plate']; ?>" required>
            <span class="error" id="lpError"></span>
            <label for="years_of_service">Years of Service:</label>
            <input type="text" id="years_of_service" name="years_of_service" value="<?php echo $row['years_of_service']; ?>" required>
            <label for="wheelchair">Wheelchair Accessible:</label>
            <select name="wheelchair" id="wheelchair" required>
            <option value="No" <?php echo ($row['wheelchair'] == 'No') ? 'selected' : ''; ?>>No</option>
                <option value="Yes" <?php echo ($row['wheelchair'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            </select>
            <label for="ramp">Ramp Equipped:</label>
            <select name="ramp" id="ramp" required>
            <option value="No" <?php echo ($row['ramp'] == 'No') ? 'selected' : ''; ?>>No</option>
                <option value="Yes" <?php echo ($row['ramp'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            </select>
            <button type="submit" name="update" onclick="return confirm('Are you sure you want to update your account?');">Update Information</button>
            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete your account?');">Delete Account</button>
            <a href="university.php" class="button">Vehicle Info</a>
        </form>
        <?php else: ?>
            <p>User data not available.</p>
        <?php endif; ?>
        <a href="Drivers Login.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>




