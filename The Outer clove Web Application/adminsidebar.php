<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/2b0896e54c.js" crossorigin="anonymous"></script>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>



<header class="header">
        <h1>Admin Dashboard</h1>
        <button class="toggle-button">&#9776;</button>
    </header>
    
    <div class="container">
        <div class="navigation">
        
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                        </span>
                        <span class="title">Admin</span>
                    </a>
                </li>

                <li>
                    <a href="admin.php">
                        <span class="icon">
                        <i class="fa-solid fa-house"></i>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="staff.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Staff</span>
                    </a>
                </li>

                <li>
                    <a href="products.php">
                        <span class="icon">
                        <i class="fa-brands fa-product-hunt"></i>
                        </span>
                        <span class="title">Products</span>
                    </a>
                </li>
                <li>
                    <a href="ad.php">
                        <span class="icon">
                        <i class="fa-brands fa-adversal"></i>
                        </span>
                        <span class="title">Advertiestment</span>
                    </a>
                </li>
                <li>
                    <a href="managereservations.php">
                        <span class="icon">
                        <i class="fa-solid fa-utensils"></i>
                        </span>
                        <span class="title">Reservations</span>
                    </a>
                </li>
                <li>
                    <a href="manageorders.php">
                        <span class="icon">
                        <i class="fa-solid fa-bell-concierge"></i>
                        </span>
                        <span class="title">Orders</span>
                    </a>
                </li>
            </ul>
        </div>

    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const navigation = document.querySelector('.navigation');
    const toggleButton = document.querySelector('.toggle-button');

    toggleButton.addEventListener('click', function () {
        navigation.classList.toggle('show');
    });
});
    </script>

    <!-- =========== Scripts =========  -->
    <script src="js/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>