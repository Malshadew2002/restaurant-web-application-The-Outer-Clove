<?php
include_once "controller/connections.php";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define SQL query
$sql = "SELECT * FROM reservation";

// Execute SQL query
$result = $conn->query($sql);

// Check for query execution errors
if (!$result) {
    die("Error in SQL query: " . $conn->error);
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
<div class="slide slide3"> 
<div class="reservation-table">
        <h2>Reservation Data</h2>

        <!-- Add a search bar -->
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for names...">

        <table id="reservationTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Reservation Date</th>
                    <th>Time Interval</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . $row["reservation_date"] . "</td>";
                        echo "<td>" . $row["time_interval"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
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

    <!-- js scripts -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
        integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery-js/1.4.0/js/lightgallery.min.js"></script>
    <script>
        // JavaScript function to filter the table based on the search input
        function searchTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("reservationTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Change index to match the column you want to search
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

<?php
$conn->close();
?>