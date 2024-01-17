<?php
include_once "controller/connections.php";
$messages = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["cancel"]) || isset($_POST["success"])) {
        $orderId = $_POST["order_id"];
        $status = isset($_POST["cancel"]) ? "cancelled" : "success";

        // Check the current order status
        $checkSql = "SELECT states FROM orders WHERE order_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("i", $orderId);
        $checkStmt->execute();
        $checkStmt->bind_result($currentStatus);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($currentStatus === $status) {
            $messages .= "Order is already marked as " . ucfirst($status) . ".";
            $messages .= '<script>
                                setTimeout(function() {
                                    window.location.href = "staff orderspanel.php";
                                }, 3000);
                            </script>';
        } else {
            // Update the order status in the database
            $updateSql = "UPDATE orders SET states = ? WHERE order_id = ?";
            $stmt = $conn->prepare($updateSql);
            $stmt->bind_param("si", $status, $orderId);

            if ($stmt->execute()) {
                $messages = "Order status updated successfully.";
                $messages .= '<script>
                                setTimeout(function() {
                                    window.location.href = "staff orderspanel.php";
                                }, 3000);
                            </script>';
            } else {
                $messages .= "Error updating order status: " . $stmt->error;
                $messages .= '<script>
                                setTimeout(function() {
                                    window.location.href = "staff orderspanel.php";
                                }, 3000);
                            </script>';
            }

            $stmt->close();
        }
    }
}

// Fetch orders data
$sql = "SELECT * FROM orders";
$result = $conn->query($sql);
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

    <div id="prenav-text">
        <div class="flex-row">
            <div class="contact-info">Phone no: <span>+94 702259866</span>
             or email us: <span>theouterclove@gmail.com</span>
            </div>
            <div class="opening-times flex-row">
                Mon - Fri / 9:00-23:00. Sat - Sun / 10:00-22:00
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
        <li><a href="staffpanel.php" class="nav-links">HOME</a></li>
            <li><a href="staff reservatins.php" class="nav-links">RESERVATIONS</a></li>
            <li><a href="staff orderspanel.php" class="nav-links">ORDERS</a></li>
       
        </ul>


    </nav>

    <!--navbar end-->

</header>

<section id="home">
<div class="slide slide4">  
<div class="reservation-table">
        <h2>Orders Data</h2>
        <input type="text" id="searchInput" onkeyup="searchOrders()" placeholder="Search for names...">
        <table id="orderTable">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Product ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>States</th>
            <th>Order Date</th>
            <th>Action</th>
            <th>Action</th> <!-- New column for the "success" button -->
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["order_id"] . "</td>";
                echo "<td>" . $row["product_id"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["states"] . "</td>";
                echo "<td>" . $row["order_date"] . "</td>";
                echo "<td>"; // First column for "cancel" button
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='order_id' value='" . $row["order_id"] . "'>";
                echo "<button type='submit' name='cancel' value='cancelled'>cancel</button>";
                echo "</form>";
                echo "</td>";
                echo "<td>"; // Second column for "success" button
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='order_id' value='" . $row["order_id"] . "'>";
                echo "<button type='submit' name='success' value='success'>success</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>
    </div>   
</div>
    
</section>
<footer id="footer">
<div class="footer-bottom flex-row">
    <span>Copyright &copy 2023 All rights reserved | Developed by malsha dewmini</span>
</div>

</footer>

    <!--js scripts-->
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
  
    <script>
    function searchOrders() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("orderTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows and hide those that don't match the search input
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2]; // Change index to the column you want to search (starting from 0)
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</body> 
</html>