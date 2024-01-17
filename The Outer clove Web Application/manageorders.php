<?php
// Include database connection file (connections.php)
include_once "controller/connections.php";
$messages = "";

// Handle deletion and success update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_btn"])) {
        $deleteOrderId = $_POST["delete_order_id"];

        // Perform the deletion query
        $deleteOrderSql = "DELETE FROM orders WHERE order_id = $deleteOrderId";
        $deleteResult = $conn->query($deleteOrderSql);

        if (!$deleteResult) {
            $messages .= '<p>Error deleting order: ' . $conn->error . '</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "manageorders.php";
                            }, 3000);
                        </script>';
        } else {
            $messages .= '<p>Order deleted successfully.</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "manageorders.php";
                            }, 3000);
                        </script>';
        }
    } elseif (isset($_POST["success_btn"])) {
        $successOrderId = $_POST["success_order_id"];

        // Update the order status to success
        $updateOrderSql = "UPDATE orders SET states = 'success' WHERE order_id = $successOrderId";
        $updateResult = $conn->query($updateOrderSql);

        if (!$updateResult) {
            $messages .= '<p>Error updating order status: ' . $conn->error . '</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "manageorders.php";
                            }, 3000);
                        </script>';
        } else {
            $messages .= '<p>Order marked as success.</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "manageorders.php";
                            }, 3000);
                        </script>';
        }
    }
}

// Retrieve orders data from the database
$selectOrdersSql = "SELECT * FROM orders";
$result = $conn->query($selectOrdersSql);
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
            
            <!-- Display orders -->
            <h2>Orders</h2>
            <input type="text" id="searchInput" placeholder="Search..." oninput="filterOrders()">
            <div class="scrollable-table">
            <table id="ordersTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Address</th>
                <th>States</th>
                <th>Order Date</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["states"] . "</td>";
                echo "<td>" . $row["order_date"] . "</td>";
                echo "<td>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='delete_order_id' value='" . $row["order_id"] . "'>";
                echo "<button type='submit' name='delete_btn'>Delete</button>";
                echo "</form>";
                echo "</td>";
                echo "<td>";
                echo "<form method='post' action=''>";
                echo "<input type='hidden' name='success_order_id' value='" . $row["order_id"] . "'>";
                echo "<button type='submit' name='success_btn'>Success</button>";
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
    // JavaScript function to filter orders
    function filterOrders() {
        var input, filter, table, tr, td, i, j, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("ordersTable");
        tr = table.getElementsByTagName("tr");

        // Start the loop from index 1 to skip the table header row
        for (i = 1; i < tr.length; i++) {
            var found = false;
            for (j = 0; j < tr[i].getElementsByTagName("td").length; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            tr[i].style.display = found ? "" : "none";
        }
    }
</script>

</body>
</html>
