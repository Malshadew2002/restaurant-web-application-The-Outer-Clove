<?php
include_once "controller/connections.php";
$messages = "";

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check if there are products
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

if (isset($_POST['add_to_cart'])) {
    // Get product and customer IDs from the form
    $productId = $_POST['product_id'];
    $customerId = 1; // Replace with the actual customer ID (implement user authentication to get this dynamically)
    $quantity = $_POST['quantity'];

    // Use prepared statements to prevent SQL injection
    $insertSql = "INSERT INTO cart (product_id, customer_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iiii", $productId, $customerId, $quantity, $quantity);

    if ($stmt->execute()) {
        $messages .= '<p>Added to cart</p>';
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "menuitems.php";
                        }, 3000);
                    </script>';
    } else {
        $messages .= "Error: " . $insertSql . "<br>" . $conn->error;
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "menuitems.php";
                        }, 3000);
                    </script>';
    }

    $stmt->close();
}

// Close the database connection
$conn->close();
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
<div class="foodmenu">
<header>
  <!--prenav start-->

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
            <div class="search-btn-wrapper nav-icon">
                <i class="searchbtn bx bx-search-alt-2"></i>
                <div id="search-form" class="search-form">
                   <form action="#">
                    <input type="search" class="search-data" placeholder="search" required>
                    <button type="submit" class="bx bx-search-alt-2"></button>
                </form>
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
<section id="menu" class="menu-section">
        <div class="container">
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search products...">
                <button onclick="filterProducts()">Search</button>
            </div>

            <div class="filter-container">
                <label for="categoryFilter">Filter by Category:</label>
                <select id="categoryFilter">
                    <option value="">All Categories</option>
                    <!-- Add options for each category dynamically from your database -->
                    <!-- Example: <option value="category1">Category 1</option> -->
                    <option>Main Dishes</option>
                    <option>Starters</option>
                    <option>Drinks</option>
                    <option>Desserts</option>
                </select>
                <button onclick="filterProducts()">Filter</button>
            </div>

            <div class="menu-cards">
            <?php foreach ($products as $product) : ?>
                <div class="menu-card">
                    <?php if (isset($product['product_image'])) : ?>
                        <img src="<?php echo $product['product_image']; ?>" alt="<?php echo isset($product['product_name']) ? $product['product_name'] : ''; ?>">
                    <?php endif; ?>
                    <h3><?php echo isset($product['product_name']) ? $product['product_name'] : ''; ?></h3>
                    <p>Price: Rs.<?php echo isset($product['product_price']) ? $product['product_price'] : ''; ?></p>
                    <form method="post">

                        <input type="hidden" name="product_id" value="<?php echo isset($product['id']) ? $product['id'] : ''; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        </div>
    </section>
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
   <script>
        function filterProducts() {
            // Get search input value
            var searchInput = document.getElementById('searchInput').value.toLowerCase();

            // Get selected category
            var categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();

            // Filter products based on search input and category
            var filteredProducts = <?php echo json_encode($products); ?>.filter(function (product) {
                var productName = product.product_name.toLowerCase();
                var productCategory = product.product_category.toLowerCase();

                return productName.includes(searchInput) && (categoryFilter === '' || productCategory === categoryFilter);
            });

            // Render filtered products
            renderProducts(filteredProducts);
        }

        function renderProducts(products) {
            var menuCardsContainer = document.querySelector('.menu-cards');
            menuCardsContainer.innerHTML = '';

            products.forEach(function (product) {
                var menuCard = document.createElement('div');
                menuCard.className = 'menu-card';

                if (product.product_image) {
                    var img = document.createElement('img');
                    img.src = product.product_image;
                    img.alt = product.product_name;
                    menuCard.appendChild(img);
                }

                var h3 = document.createElement('h3');
                h3.textContent = product.product_name || '';
                menuCard.appendChild(h3);

                var p = document.createElement('p');
                p.textContent = 'Price: Rs.' + (product.product_price || '');
                menuCard.appendChild(p);

                var addToCartBtn = document.createElement('button');
                addToCartBtn.className = 'add-to-cart-btn';
                addToCartBtn.setAttribute('data-product-id', product.id || '');
                addToCartBtn.textContent = 'Add to Cart';
                menuCard.appendChild(addToCartBtn);

                menuCardsContainer.appendChild(menuCard);
            });
        }
    </script>

</body> 
</html>
