<?php
include_once "controller/connections.php";
$messages = "";

// Fetch products from the database
$sql = "SELECT cart.product_id, products.product_name, products.product_price, cart.quantity
        FROM cart
        INNER JOIN products ON cart.product_id = products.id";

$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_btn"])) {
        $deleteProductId = $_POST["delete_product_id"];

        // Perform the deletion query
        $deleteSql = "DELETE FROM cart WHERE product_id = $deleteProductId";
        $deleteResult = $conn->query($deleteSql);

        if (!$deleteResult) {
            $messages .= '<p>Error deleting record: ' . $conn->error . '</p>';
            $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "menuitems.php";
                        }, 3000);
                    </script>';
        } else {
            $messages .= '<p>Product removed from the cart.</p>';
            $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "menuitems.php";
                        }, 3000);
                    </script>';
        }
    } elseif (isset($_POST["submit_order"])) {
        // Get user input from the form
        $name = $_POST["name"];
        $address = $_POST["address"];
        $states = "unsuccess";

        // Add user and order details to the orders table
        $insertOrderSql = "INSERT INTO orders (product_id, name, address, states, order_date)
                           SELECT product_id, '$name', '$address', '$states', NOW() FROM cart";

        if ($conn->query($insertOrderSql)) {
            // Clear the cart after successfully moving items to orders
            $clearCartSql = "DELETE FROM cart";
            $conn->query($clearCartSql);

            $messages .= '<p>Order placed successfully!</p>';
            $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "menuitems.php";
                        }, 3000);
                    </script>';
        } else {
            $messages .= '<p>Error placing order: ' . $conn->error . '</p>';
            $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "menuitems.php";
                        }, 3000);
                    </script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--style sheet links-->
    <link rel="stylesheet" href="css/outer clove.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/css/lightgallery.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>THE OUTER CLOVE.com </title>
</head>

<body>

<div class="messages-container">
        <?php echo $messages; ?>
    </div>
<div class="foodcart-section">
    <header>
        <!--prenav start-->

        <div id="prenav-text">
            <div class="flex-row">
                <div class="contact-info">Phone no: <span>+94 702259866</span> or email us:
                    <span>theouterclove@gmail.com</span>
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
                <i id="menu-btn" class="menu-btn bx bx-menu"></i>
            </div>

            <div class="logo">
                <h5>The Outer Clove <span>Restaurant</span></h5>
            </div>

            <ul id="nav-items" class="nav-items">
                <li><a href="outer clove.php" class="nav-links">HOME</a></li>
                <li><a href="#about" class="nav-links">ABOUT</a></li>
                <li><a href="menuitems.php" class="nav-links">MENU</a></li>
                <li><a href="reservation.php" class="nav-links">RESERVATION</a></li>
                <li><a href="cart.php" class="nav-links">CART</a></li>
                <li><a href="#contact" class="nav-links">CONTACT</a></li>
            </ul>

            <ul class="nav-btns">

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
    <div class="cart-container">
    <h2>Cart</h2>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Quantity</th> <!-- New column for Quantity -->
                    <th>Action</th>
                </tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["product_name"] . "</td>
                    <td>" . $row["product_price"] . "</td>
                    <td>" . $row["quantity"] . "</td> <!-- Display Quantity -->
                    <td>
                        <form method='post' action=''>
                            <input type='hidden' name='delete_product_id' value='" . $row["product_id"] . "'>
                            <button type='submit' name='delete_btn'>Delete</button>
                        </form>
                    </td>
                  </tr>";
        }

        echo "</table>";
    }

    $conn->close();
    ?>

    <!-- Form for adding name and address -->
    <form class="payment" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data"> 
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>

    <label for="address">Address:</label>
    <input type="text" id="address" name="address" required>

    <label for="credit_card">Credit Card Number:</label>
    <input type="text" maxlength="16" id="credit_card" name="credit_card" required>

    <label for="expiry_date">Expiry Date:</label>
    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YYYY" required>

    <label for="cvv">CVV:</label>
    <input type="text" id="cvv" name="cvv" maxlength="3" required >

    <button type="submit" name="submit_order">Submit Order</button>
    <div class="img-container">
        <div class="img1"><img src="css/images/Visa.png"></div>
        <div class="img2"><img src="css/images/master.png"></div>
        <div class="img3"><img src="css/images/PayPal.png"></div>
        <div class="img4"><img src="css/images/americanexpresspng.png"></div>
    </div>
</form>
</div>

</div>
<footer id="footer">
<div class="footer-bottom flex-row">
    <span>Copyright &copy 2023 All rights reserved | Developed by malsha dewmini</span>
</div>

</footer>
    <!--js scripts-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
    <script src="js/outer clove.js"></script>

</body>

</html>
