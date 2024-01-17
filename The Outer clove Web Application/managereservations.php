<?php
// Include database connection file (connections.php)
include_once "controller/connections.php";
$messages = "";

// Handle removal of a reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_btn"])) {
    $removeReservationId = $_POST["remove_reservation_id"];

    // Perform the deletion query
    $deleteReservationSql = "DELETE FROM reservation WHERE id = $removeReservationId";
    $deleteResult = $conn->query($deleteReservationSql);

    if (!$deleteResult) {
        $messages .= '<p>Error deleting reservation: ' . $conn->error . '</p>';
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "managereservations.php";
                            }, 3000);
                        </script>';
    } else {
        $messages .= '<p>Reservation removed successfully.</p>';
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "managereservations.php";
                            }, 3000);
                        </script>';
    }
}

// Retrieve reservation data from the database
$selectReservationSql = "SELECT * FROM reservation";
$result = $conn->query($selectReservationSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/2b0896e54c.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php
    include "adminsidebar.php";
    include_once "controller/connections.php";
    ?>

    <div class="messages-container">
        <?php echo $messages; ?>
    </div>

    <div class="add_container">
        <div class="content">
            
            <!-- Display products -->
            <h2>Reservations</h2>
            <input type="text" id="searchInput" placeholder="Search..." oninput="filterReservations()">
            <div class="scrollable-table">
            <table id="reservationsTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Time Interval</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["phone"] . "</td>";
                            echo "<td>" . $row["reservation_date"] . "</td>";
                            echo "<td>" . $row["time_interval"] . "</td>";
                            echo "<td>";
                            echo "<form method='post' action=''>";
                            echo "<input type='hidden' name='remove_reservation_id' value='" . $row["id"] . "'>";
                            echo "<button type='submit' name='remove_btn'>Remove</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        // JavaScript function to filter reservations
        function filterReservations() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("reservationsTable");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        if (i === 0) {
            // Skip the header row
            continue;
        }

        var found = false;
        for (j = 0; j < tr[i].getElementsByTagName("td").length; j++) {
            td = tr[i].getElementsByTagName("td")[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break; // Break if the value is found in any column
                }
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}
        // Removed the initial call to filterReservations();
    </script>

</body>
</html>
