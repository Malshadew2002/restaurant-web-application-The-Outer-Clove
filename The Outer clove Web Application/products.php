<?php
// Include database connection file (connections.php)
include_once "controller/connections.php";
$messages = "";

// Function to handle form submission for adding a new product
function addProduct($conn, $productName, $productPrice, $productCategory, $productImage)
{
    global $messages;

    $sql = "INSERT INTO products (product_name, product_price, product_category, product_image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $productName, $productPrice, $productCategory, $productImage);
    
    if ($stmt->execute()) {
        $messages = "Product added successfully";
        header("Refresh: 3; URL=products.php"); // Redirect after 3 seconds
    } else {
        $messages = "Error adding product: " . htmlspecialchars($stmt->error);
        header("Refresh: 3; URL=products.php"); // Redirect after 3 seconds
    }

    $stmt->close();
}

// Function to handle deleting a product
function deleteProduct($conn, $productId)
{
    global $messages; // Add this line

    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);
    if ($stmt->execute()) {
        $messages = "Product deleted successfully"; // Set success message
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "products.php";
                            }, 3000);
                        </script>';
    } else {
        $messages = "Error deleting product: " . $stmt->error; // Set error message
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "products.php";
                            }, 3000);
                        </script>';
    }

    $stmt->close();
}

// Handle form submissions for adding or updating product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $productCategory = $_POST['product_category'];

        // Handle file upload
        $productImage = "";  // Default value if no file is uploaded
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $uploadDir = "uploads/";  // Create a folder named 'uploads' to store uploaded files
            $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $productImage = $uploadFile;
            } else {
                //echo "File upload failed!";
            }
        }

        addProduct($conn, $productName, $productPrice, $productCategory, $productImage);
    } elseif (isset($_POST['update'])) {
        $productId = $_POST['id'];
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $productCategory = $_POST['product_category'];

        // Handle file upload
        $productImage = $_POST['existing_image'];  // Default value if no new file is uploaded
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $uploadDir = "uploads/";
            $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $productImage = $uploadFile;
            } else {
                //echo "File upload failed!";
            }
        }

        updateProduct($conn, $productId, $productName, $productPrice, $productCategory, $productImage);
    } elseif (isset($_POST['delete'])) {
        $productIdToDelete = $_POST['delete'];
        deleteProduct($conn, $productIdToDelete);
    }
}

// Handle product deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    deleteProduct($conn, $productId);
}

// Retrieve existing products for display
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
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
            <!-- Button to show the form -->
            <button onclick="toggleForm()">Add New</button>

            <!-- Form to add a new staff member -->
            <div id="addForm">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
                    Product Name: <input type="text" name="product_name" required><br>
                    Product Price: <input type="number" name="product_price" required><br>
                    Product Image: <input type="file" name="product_image" required><br>
                    <select id="category" name="product_category">
                        <option>Main Dishes</option>
                        <option>Starters</option>
                        <option>Drinks</option>
                        <option>Desserts</option>
                    </select>
                    <button type="submit" name="add">OK</button>
                </form>
            </div>

            <!-- Display products -->
            <h2>Products</h2>
            <input type="text" id="searchInput" placeholder="Search..." oninput="filterProducts()">
            <div class="scrollable-table">
            <table id="productsTable">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Price</th>
                            <th>Product Image</th>
                            <th>Product category</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['product_name']}</td>";
                            echo "<td>{$row['product_price']}</td>";
                            echo "<td><img src='{$row['product_image']}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                            echo "<td>{$row['product_category']}</td>";
                            echo "<td>
                            <form method=\"post\" action=\"controller/edit_product.php\">
                            <button type=\"submit\" name=\"edit\" value=\"" . $row['id'] . "\">Edit</button>
                        </form>
                                  </td>";
                            echo "<td>
                                    <form method=\"post\">
                                        <input type=\"hidden\" name=\"delete\" value=\"" . $row['id'] . "\">
                                        <button type=\"submit\">Delete</button>
                                    </form>
                                  </td>";
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
    function toggleForm() {
        var addForm = document.getElementById('addForm');
        addForm.style.display = (addForm.style.display === 'block') ? 'none' : 'block';
    }

    // JavaScript function to filter products
    function filterProducts() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("productsTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) {  // Start from index 1 to skip the table headers
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
