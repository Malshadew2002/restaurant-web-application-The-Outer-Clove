<?php
include_once "controller/connections.php";

$messages = "";

// Function to sanitize user input
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input));
}

// Check if the database connection is successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Registration logic
if (isset($_POST['register'])) {
    $customer_name = sanitizeInput($_POST['customer_name']);
    $customer_email = sanitizeInput($_POST['customer_email']);
    $customer_password = password_hash($_POST['customer_password'], PASSWORD_DEFAULT);

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM customers WHERE customer_email = '$customer_email'";
    $result = mysqli_query($conn, $checkEmailQuery);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $messages = "Email already registered. Please use a different email.";
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                        </script>';
        } else {
            // Insert user data into the database
            $insertQuery = "INSERT INTO customers (customer_name, customer_email, customer_password) VALUES ('$customer_name', '$customer_email', '$customer_password')";
            mysqli_query($conn, $insertQuery);
            $messages = "Registration successful. You can now log in.";
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                        </script>';
        }
    } else {
        $messages = "Error: " . mysqli_error($conn);
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                        </script>';
    }
}

// Login logic
if (isset($_POST['login'])) {
    $login_email = sanitizeInput($_POST['email']);
    $login_password = sanitizeInput($_POST['password']);

    // Retrieve user data based on email
    $loginQuery = "SELECT * FROM customers WHERE customer_email = '$login_email'";
    $result = mysqli_query($conn, $loginQuery);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($login_password, $row['customer_password'])) {
                header("Location: outer clove.php");
                exit();
            } else {
                $messages = "Wrong email or password. Please try again.";
                $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                        </script>';
            }
        } else {
            $messages = "Wrong email or password. Please try again.";
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                        </script>';
        }
    } else {
        $messages = "Error: " . mysqli_error($conn);
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "login.php";
                            }, 3000);
                        </script>';
    }
}

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Animated Login & Registration Form HTML CSS | Codehal</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

<div class="messages-container">
        <?php echo $messages; ?>
    </div>
    <div class="wrapper">
        <img src="img.png" alt="">
        <h2 class="text-right">Welcome</h2>

        <div class="form-wrapper login">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <h2>Login</h2>
        <div class="input-box">
            <span class="icon">
                <ion-icon name="mail"></ion-icon>
            </span>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <!-- Add name attribute for the email input -->
        </div>
        <div class="input-box">
            <span class="icon">
                <ion-icon name="lock-closed"></ion-icon>
            </span>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <!-- Add name attribute for the password input -->
        </div>
        <button type="submit" name="login">Login</button>
        <div class="sign-link">
            <p>Don't have an account? <a href="#" onclick="registerActive()">Register</a></p>
        </div>
    </form>
        </div>

        <div class="form-wrapper register">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                <h2>Registration</h2>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" id="fullname" name="customer_name" placeholder="Full Name" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" id="email" name="customer_email" placeholder="Email" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" id="pass" name="customer_password" placeholder="Password" required>
                </div>
                <button type="submit" name="register">Register</button>
                <div class="sign-link">
                    <p>Already have an account? <a href="#" onclick="loginActive()">Login</a></p>
                </div>
            </form>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="js/login.js"></script>
</body>

</html>