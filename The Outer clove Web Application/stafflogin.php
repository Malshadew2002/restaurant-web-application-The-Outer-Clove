<?php
include_once "controller/connections.php";

$messages = "";

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // Connect to the database

    // Retrieve username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to check if the entered username and password exist in the staff_members table
    $query = "SELECT * FROM staff_members WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Check if a row is returned, indicating valid credentials
        if (mysqli_num_rows($result) > 0) {
            // Valid login, redirect to staffpanel.php
            header("Location: staffpanel.php");
            exit();
        } else {
            $messages = "Invalid username or password.";
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "stafflogin.php";
                            }, 3000);
                        </script>';
        }
    } else {
        $messages = "Error executing query: " . mysqli_error($conn);
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "stafflogin.php";
                            }, 3000);
                        </script>';
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <div class="messages-container">
        <?php echo $messages; ?>
    </div>

    <div class="wrapper">
        <h2 class="text-right">Welcome</h2>
        <div class="form-wrapper login">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <h2>Login</h2>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="js/login.js"></script>
</body>

</html>
