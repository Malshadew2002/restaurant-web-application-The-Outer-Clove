<?php
include_once "controller/connections.php";
$messages = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reserve_btn"])) {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $date = $_POST["date"];
    $time_interval = $_POST["time_interval"];
    $currentDate = date("Y-m-d");

    // Check if the selected date and time interval are available
    $checkAvailabilitySql = "SELECT * FROM reservation 
                             WHERE reservation_date = '$date' 
                             AND time_interval = '$time_interval'";

    $availabilityResult = $conn->query($checkAvailabilitySql);

    if ($availabilityResult->num_rows > 0) {
        $messages .= '<p>Sorry, the selected date and time interval are not available. Please choose another.</p>';
        $messages .= '<script>
        setTimeout(function() {
            window.location.href = "reservation.php";
        }, 3000);
    </script>';
    } elseif ($date < $currentDate) {
        $messages .= '<p>Please select a date greater than or equal to the current date.</p>';
        $messages .= '<script>
        setTimeout(function() {
            window.location.href = "reservation.php";
        }, 3000);
    </script>';
    } else {
        // Insert reservation into the database
        $insertReservationSql = "INSERT INTO reservation (name, phone, reservation_date, time_interval) 
                                VALUES ('$name', '$phone', '$date', '$time_interval')";

        if ($conn->query($insertReservationSql)) {
            $messages .= '<p>Reservation successful!</p>';
            $messages .= '<script>
        setTimeout(function() {
            window.location.href = "reservation.php";
        }, 3000);
    </script>';
        } else {
            $messages .= '<p>Error making reservation: ' . $conn->error . '</p>';
            $messages .= '<script>
        setTimeout(function() {
            window.location.href = "reservation.php";
        }, 3000);
    </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta cahrset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--style sheet links-->
<link rel="stylesheet" href="css/outer clove.css">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<title>THE OUTER CLOVE.com </title>
</head>

<body>

<div class="messages-container">
        <?php echo $messages; ?>
    </div>

<header>
   <!--prenav start-->
<div class="reservation">
    <div id="prenav-text">
        <div class="flex-row">
            <div class="contact-info">Phone no: <span>+94 702259866</span>
             or email us: <span>theouterclove@gmail.com</span>
            </div>
            <div class="opening-times flex-row">
                Mon - Fri / 9:00-21:00. Sat - Sun / 10:00-20:00
                <ul class="social-links flex-row">
                    <li><a href="https://www.facebook.com/"><i class="bx bxl-facebook"></i></a></li>
                    <li><a href="https://www.facebook.com/"><i class="bx bxl-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/"><i class="bx bxl-instagram"></i></a></li>
                    <li><a href="https://www.whatsapp.com/"><i class="bx bxl-whatsapp"></i></a></li>
                    <li><a href="https://www.facebook.com/"><i class="bx bxl-youtube"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

   <!--prenav end-->

   <!--navbar start-->

    <nav id="navbar" class="navbar flex-row">
        <div class="nav-icon menu-btn-wrapper">
            <i id="menu-btn" class="menu-btn bx bx-menu" ></i>
        </div>

        <div class="logo">
            <h5>The Outer Clove <span>Restaurant</span></h5>
        </div>

        <ul id="nav-items" class="nav-items">
            <li><a href="outer clove.php" class="nav-links">HOME</a></li>
            <li><a href="menuitems.php" class="nav-links">MENU</a></li>
            <li><a href="reservation.php" class="nav-links">RESERVATION</a></li>

        </ul>

        <ul class="nav-btns">
        <div class="search-btn-wrapper">
                <i class="searchbtn bx bx-search-alt-2"></i>
                <div id="search-form" class="search-form">
                  
                </div>
            </div>

            <div class="cart-btn-wrapper nav-icon">
                <a href="cart.php"><i class="cartbtn bx bx-cart"></i></a>
            </div>

            <div class="nav-icon">
                <i class="darkbtn bx bx-moon"></i>
            </div>
        </ul>

    </nav>

    <!--navbar end-->

</header>

<div class="reservation-form">
    <form id="addForm" method="post" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>

        <label for="time_interval">Time Interval:</label>
        <select id="time_interval" name="time_interval" required>
        <option value="9:00 AM - 11:00 AM">09:00 AM - 11:00 AM</option>
            <option value="12:00 PM - 2:00 PM">12:00 PM - 2:00 PM</option>
            <option value="2:00 PM - 4:00 PM">2:00 PM - 4:00 PM</option>
            <option value="4:00 PM - 6:00 PM">4:00 PM - 6:00 PM</option>
            <option value="06:00 PM - 08:00 PM">06:00 PM - 08:00 PM</option>
            <option value="08:00 PM - 10:00 PM">08:00 PM - 10:00 PM</option>
        </select>

        <button type="submit" name="reserve_btn">Take Reservation</button>
    </form>
</div>
</div>



<footer id="footer">
<div class="footer-bottom flex-row">
    <span>Copyright &copy 2023 All rights reserved | Developed by malsha dewmini</span>
</div>

</footer>

    <!--js scripts-->
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
   <script src="js/outer clove.js"></script>

</body> 
</html>
