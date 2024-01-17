<?php
// Include database connection file (connections.php)
include_once "controller/connections.php";
$messages = "";

// Function to handle form submission for adding a new product
function addads($conn, $adName, $adImage)
{
    global $messages;

    $sql = "INSERT INTO ads (ad_name, ad_image) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $adName, $adImage); // Change 'sds' to 'ss'

    if ($stmt->execute()) {
        $messages = " added successfully";
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "ad.php";
                        }, 3000);
                    </script>';
    } else {
        $messages = "Error adding : " . $stmt->error;
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "ad.php";
                        }, 3000);
                    </script>';
    }

    $stmt->close();
}

// Function to handle deleting a product
function deleteads($conn, $productId)
{
    global $messages;

    $sql = "DELETE FROM ads WHERE ad_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $productId);

    if ($stmt->execute()) {
        $messages = "deleted successfully";
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "ad.php";
                        }, 3000);
                    </script>';
    } else {
        $messages = "Error deleting: " . $stmt->error;
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "ad.php";
                        }, 3000);
                    </script>';
    }

    $stmt->close();
}

// Handle form submissions for adding or updating product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        $adName = $_POST['ad_name'];

        // Handle file upload
        $adImage = "";  // Default value if no file is uploaded
        if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] == 0) {
            $uploadDir = "uploads/";
            $uploadFile = $uploadDir . basename($_FILES['ad_image']['name']);

            if (move_uploaded_file($_FILES['ad_image']['tmp_name'], $uploadFile)) {
                $adImage = $uploadFile;
            } else {
                // Handle file upload failure
            }
        }

        addads($conn, $adName, $adImage);
    }
}

// Handle product deletion
if (isset($_POST['delete']) && !empty($_POST['delete'])) {
    $productId = $_POST['delete'];
    deleteads($conn, $productId);
}

// Retrieve existing products for display
$sql = "SELECT * FROM ads";
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
                    Name: <input type="text" name="ad_name" required><br>
                    Image: <input type="file" name="ad_image" required><br>
                    <button type="submit" name="add">OK</button>
                </form>
            </div>

            <!-- Display products -->
            <h2>Advertiestment</h2>
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Image</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['ad_name']}</td>";
                            echo "<td><img src='{$row['ad_image']}' alt='Product Image' style='max-width: 100px; max-height: 100px;'></td>";
                            echo "<td>
                                    <form method=\"post\">
                                        <input type=\"hidden\" name=\"delete\" value=\"" . $row['ad_id'] . "\">
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
    </script>
</body>
</html>
