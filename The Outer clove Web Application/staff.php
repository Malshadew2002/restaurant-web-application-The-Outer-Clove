<?php
include_once "controller/connections.php";
$messages = ""; // Initialize $messages variable

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert data into the database
function addNewStaffMember($name, $username, $password, $conn) {
    global $messages; // Use global to access the $messages variable

    // Check if the username is already in the database
    $checkSql = "SELECT * FROM staff_members WHERE username = '$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // Username already exists
        $messages .= '<p>Error: Please choose a different username.</p>';
        $messages .= '<script>
                        setTimeout(function() {
                            window.location.href = "staff.php";
                        }, 3000);
                    </script>';
    } else {
        // Username is available, proceed to insert into the database
        $sql = "INSERT INTO staff_members (name, username, password) VALUES ('$name', '$username', '$password')";

        if ($conn->query($sql) === TRUE) {
            $messages .= '<p>New staff member added successfully.</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "staff.php";
                            }, 3000);
                        </script>';
        } else {
            $messages .= '<p>Error: ' . $conn->error . '</p>';
            $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "staff.php";
                            }, 3000);
                        </script>';
        }
    }
}

// Function to delete a staff member from the database
function deleteStaffMember($id, $conn) {
    global $messages; // Use global to access the $messages variable

    $sql = "DELETE FROM staff_members WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        $messages .= '<p>Staff member deleted successfully.</p>';
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "staff.php";
                            }, 3000);
                        </script>';
    } else {
        $messages .= '<p>Error: ' . $conn->error . '</p>';
        $messages .= '<script>
                            setTimeout(function() {
                                window.location.href = "staff.php";
                            }, 3000);
                        </script>';
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if delete button is clicked
    if (isset($_POST['delete'])) {
        $deleteId = $_POST['delete'];
        deleteStaffMember($deleteId, $conn);
    } else {
        // Process form data for adding a new staff member
        $name = isset($_POST["name"]) ? $_POST["name"] : '';
        $username = isset($_POST["username"]) ? $_POST["username"] : '';
        $password = isset($_POST["password"]) ? $_POST["password"] : '';

        // Add new staff member to the database
        addNewStaffMember($name, $username, $password, $conn);
    }
}

function getStaffMembers($conn) {
    $sql = "SELECT * FROM staff_members";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return array(); // Return an empty array if no records found
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard | Korsat X Parmaga</title>
    <script src="https://kit.fontawesome.com/2b0896e54c.js" crossorigin="anonymous"></script>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="css/style.css">
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
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    Name: <input type="text" name="name" required><br>
                    Username: <input type="text" name="username" required><br>
                    Password: <input type="password" name="password" required><br>
                    <input type="submit" value="Submit">
                </form>
            </div>

            <!-- Display staff members -->
            <h2>Staff Members</h2>
            <input type="text" id="searchInput" placeholder="Search...." oninput="filterStaffMembers()">
            <div class="scrollable-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $staffMembers = getStaffMembers($conn);

                        foreach ($staffMembers as $member) {
                            echo "<tr>";
                            echo "<td>" . $member['id'] . "</td>";
                            echo "<td>" . $member['name'] . "</td>";
                            echo "<td>" . $member['username'] . "</td>";
                            echo "<td>" . $member['password'] . "</td>";
                            echo "<td><button onclick=\"editStaffMember(" . $member['id'] . ")\">Edit</button></td>";
                            echo "<td>
                                    <form method=\"post\">
                                        <button type=\"submit\" name=\"delete\" value=\"" . $member['id'] . "\">Delete</button>
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
        function editStaffMember(id) {
        window.location.href = "controller/edit_staff_member.php?id=" + id;
    }
    function filterStaffMembers() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.querySelector(".scrollable-table table"); // Update the table selector
    tr = table.getElementsByTagName("tr");

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
