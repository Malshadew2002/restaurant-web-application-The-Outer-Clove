<?php
include_once "connections.php";
$messages = "";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    // Handle the case where the ID is not valid
    // Redirect or display an error message
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update'])) {
        // Retrieve form data
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['new_password'];
        $id = $_POST['id'];

        // Update record in the database
        $sql = "UPDATE staff_members SET name=?, username=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $name, $username, $password, $id);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            $messages = "Record updated successfully!";
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "../staff.php";
                            }, 3000);
                        </script>';
        } else {
            $messages = "Error updating record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } elseif (isset($_POST['cancel'])) {
        // Redirect to the staff.php page
        header("Location: ../staff.php");
        exit(); // Ensure that no further code is executed after the redirect
    }
}

// Retrieve staff member data for pre-filling the form fields
$sql = "SELECT * FROM staff_members WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$staffMember = $result->fetch_assoc();

// Close the statement
$stmt->close();

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
        <h2>Edit Staff Member</h2>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <!-- Pre-fill form fields with staff member data -->
            Name: <input type="text" name="name" value="<?php echo htmlspecialchars($staffMember['name']); ?>" required><br>
            Username: <input type="text" name="username" value="<?php echo htmlspecialchars($staffMember['username']); ?>" required><br>
            Password: <input type="text" name="new_password" value="<?php echo htmlspecialchars($staffMember['password']); ?>" required><br>
            <input type="hidden" name="id" value="<?php echo $staffMember['id']; ?>">
            <button type="submit" class="btn btn-secondary" style="height:40px" name="update">Update</button>
            <button type="submit" class="btn btn-secondary" style="height:40px" name="cancel">Cancel</button>
        </form>
    </div>
</div>
<!-- ... (existing code) -->
</body>
</html>
