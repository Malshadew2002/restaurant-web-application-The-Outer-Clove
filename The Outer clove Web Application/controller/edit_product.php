<?php
include_once "connections.php";

// Initialize $productData and $messages
$productData = [];
$messages = '';

// Check if the form is submitted for updating the product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        $productIdToUpdate = $_POST['id'];
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];

        // Check if a new image file is uploaded
        if ($_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            $location = "uploads/";
            $uploadDir = "../uploads/"; // Adjust the upload directory as needed
            $uploadFile = $uploadDir . basename($_FILES['product_image']['name']);
            $finalimage = $location . basename($_FILES['product_image']['name']);

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                // Update the product data with the new image path
                $updateSql = "UPDATE products SET product_name=?, product_price=?, product_image=? WHERE id=?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param('sdsi', $productName, $productPrice, $finalimage, $productIdToUpdate);
                $updateStmt->execute();

                // Check if the update was successful
                if ($updateStmt->affected_rows > 0) {
                    $messages .= '<p>Product updated successfully.</p>';
                    $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../products.php";
                            }, 3000);
                        </script>';
                } else {
                    $messages .= '<p>Product update unsuccessful.</p>';
                    if ($updateStmt->error) {
                        $messages .= '<p>Error updating product: ' . $updateStmt->error . '</p>';
                        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../products.php";
                            }, 3000);
                        </script>';
                    }
                }

                $updateStmt->close();
            } else {
                $messages .= '<p>Error uploading image.</p>';
            }
        } else {
            // Update the product data without changing the image
            $updateSql = "UPDATE products SET product_name=?, product_price=? WHERE id=?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('sdi', $productName, $productPrice, $productIdToUpdate);
            $updateStmt->execute();

            // Check if the update was successful
            if ($updateStmt->affected_rows > 0) {
                $messages .= '<p>Product updated successfully.</p>';
                $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../products.php";
                            }, 3000);
                        </script>';
            } else {
                $messages .= '<p>Product update unsuccessful.</p>';
                $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../products.php";
                            }, 3000);
                        </script>';
                if ($updateStmt->error) {
                    $messages .= '<p>Error updating product: ' . $updateStmt->error . '</p>';
                    $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../products.php";
                            }, 3000);
                        </script>';
                }
            }

            $updateStmt->close();
        }
    } elseif (isset($_POST['cancel'])) {
        // Redirect to the products.php page
        header("Location: ../products.php");
        exit(); // Ensure that no further code is executed after the redirect
    } elseif (isset($_POST['edit'])) {
        $productIdToEdit = $_POST['edit'];

        // Retrieve existing product data
        $sql = "SELECT * FROM products WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $productIdToEdit);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there is data retrieved
        if ($result->num_rows > 0) {
            $productData = $result->fetch_assoc();
        } else {
            // Handle the case where no data is found
            $messages .= '<p>Product not found.</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../products.php";
                            }, 3000);
                        </script>';
        }

        $stmt->close();
    } else {
        // Redirect or handle the case where the form is not submitted properly
        $messages .= '<p>Product update unsuccessful.</p>';
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "../products.php";
                        }, 3000);
                    </script>';
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="messages-container">
    <?php echo $messages; ?>
</div>

<div class="edit_container">
    <div class="content">
        <h2>Edit Product</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
            <?php if (isset($productData['id'])) : ?>
                <input type="hidden" name="id" value="<?php echo $productData['id']; ?>">
                Product Name: <input type="text" name="product_name" value="<?php echo $productData['product_name']; ?>" required><br>
                Product Price: <input type="number" name="product_price" value="<?php echo $productData['product_price']; ?>" required><br>
                Existing Image: <img src="../<?php echo $productData['product_image']; ?>" alt="Existing Product Image" style="max-width: 100px; max-height: 100px;"><br>
                New Image: <input type="file" name="product_image"><br>
                <button type="submit" class="btn btn-secondary" style="height:40px" name="update">Update</button>
                <button type="submit" class="btn btn-secondary" style="height:40px" name="cancel">Cancel</button>
            <?php else : ?>
                <!-- Handle the case where $productData['id'] is not set
                <p>Product not found.</p> -->
            <?php endif; ?>
        </form>
    </div>
</div>

</body>
</html>
