<?php

$messages = "";
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password match
    if ($_POST["username"] == "admin" && $_POST["password"] == "1234") {
        // Redirect to admin panel if credentials are correct
        header("Location: admin.php");
        exit();
    } else {
        // Display error message if credentials are incorrect
        $messages = "Wrong username or password. Please try again.";
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "adminlogin.php";
                            }, 3000);
                        </script>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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
                    <input type="text" name="username" id="username" placeholder="username" required>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" id="password" placeholder="Password" required>
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
