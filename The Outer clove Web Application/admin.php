<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- ======= Styles ======= -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php
    include "adminsidebar.php";
    include_once "controller/connections.php";

    function getRowCount($conn, $tableName) {
        $sql = "SELECT COUNT(*) AS count FROM $tableName";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['count'];
        }

        return 0; // Default count if query fails
    }
    ?>

    <div class="row">
        <div class="card">
            <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="title">Total products</span>
            <h5><?php echo getRowCount($conn, 'products'); ?></h5>
        </div>
        <div class="card">
            <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="title">Total promotions</span>
            <h5><?php echo getRowCount($conn, 'ads'); ?></h5>
        </div>
        <div class="card">
            <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="title">Total customers</span>
            <h5><?php echo getRowCount($conn, 'customers'); ?></h5>
        </div>
        <div class="card">
            <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="title">Total orders</span>
            <h5><?php echo getRowCount($conn, 'orders'); ?></h5>
        </div>
        <div class="card">
            <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="title">Total reservations</span>
            <h5><?php echo getRowCount($conn, 'reservation'); ?></h5>
        </div>
        <div class="card">
            <span class="icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <span class="title">Total members</span>
            <h5><?php echo getRowCount($conn, 'staff_members'); ?></h5>
        </div>

    </div>

    <!-- =========== Scripts =========  -->
    <script src="js/main.js"></script>
    <script src="https://kit.fontawesome.com/2b0896e54c.js" crossorigin="anonymous"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <?php
    $conn->close();
    ?>
</body>

</html>
