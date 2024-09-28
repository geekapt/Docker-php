<?php
session_start(); // Start a session to use session variables

$servername = "mysql-db";
$username = "user";
$password = "user_password";
$dbname = "sampledb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a delete request was made
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete the record from the database
    $sql = "DELETE FROM user_data WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Record deleted successfully!";
        $_SESSION['message_type'] = 'error'; // Set message type to 'error' for deletion
    } else {
        $_SESSION['message'] = "Error deleting record: " . $conn->error;
        $_SESSION['message_type'] = 'error';
    }

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if the form for editing data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = $_POST['data'];

    if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
        // If edit_id is set, update the record
        $edit_id = $_POST['edit_id'];

        if (is_numeric($edit_id)) {
            $sql = "UPDATE user_data SET data = '$data' WHERE id = $edit_id";
            
            if ($conn->query($sql) === TRUE) {
                $_SESSION['message'] = "Record updated successfully!";
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = "Error updating record: " . $conn->error;
                $_SESSION['message_type'] = 'error';
            }
        } else {
            $_SESSION['message'] = "Invalid ID for update.";
            $_SESSION['message_type'] = 'error';
        }
    } else {
        // If no edit_id is set, insert new data
        $sql = "INSERT INTO user_data (data) VALUES ('$data')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "New record created successfully!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['message_type'] = 'error';
        }
    }

    // Redirect to avoid resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch and display inserted data
$sql = "SELECT * FROM user_data";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px;
            border: 1px solid transparent;
            border-radius: 5px;
            z-index: 1000;
            display: none; /* Initially hidden */
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .icon {
            margin-left: 10px;
            cursor: pointer;
            color: #007bff; /* Change color as needed */
        }
        input[type="text"] {
            width: 300px;
            padding: 8px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <h1>Connected successfully to MySQL database!</h1>

    <!-- Display notification if set -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="notification <?php echo ($_SESSION['message_type'] == 'error') ? 'error' : 'success'; ?>">
            <?php 
                echo htmlspecialchars($_SESSION['message']); 
                unset($_SESSION['message']); // Clear the message after displaying
                unset($_SESSION['message_type']); // Clear the message type
            ?>
        </div>
    <?php endif; ?>

    <!-- Display inserted data -->
    <?php if ($result->num_rows > 0): ?>
        <h2>Inserted Data:</h2>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <?php echo htmlspecialchars($row['data']); ?> 
                    <a href="?delete_id=<?php echo $row['id']; ?>" class="icon" title="Delete"><i class="fas fa-trash-alt"></i></a>
                    <span class="icon" onclick="editData(<?php echo $row['id']; ?>, '<?php echo addslashes($row['data']); ?>')" title="Edit"><i class="fas fa-pencil-alt"></i></span>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>0 results</p>
    <?php endif; ?>

    <!-- HTML form for data input -->
    <form method="POST" action="" id="dataForm">
        <input type="hidden" name="edit_id" id="edit_id" value="">
        <input type="text" name="data" id="data" required>
        <input type="submit" value="Insert Data">
    </form>

    <script>
        // Show notification if it exists
        const notification = document.querySelector('.notification');
        if (notification) {
            notification.style.display = 'block'; // Show the notification
            setTimeout(() => {
                notification.style.display = 'none'; // Hide after 5 seconds
            }, 5000);
        }

        // Function to populate the edit form
        function editData(id, data) {
            document.getElementById('edit_id').value = id; // Set edit_id
            document.getElementById('data').value = data; // Set data
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
    