<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"], input[type="number"] {
            padding: 5px;
            margin-right: 10px;
        }
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Manage Students</h1>

<!-- Search Form -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by Name or ID">
    <input type="submit" value="Search">
</form>

<!-- Insert Form -->
<form method="POST" action="">
    <input type="number" name="StudentID" placeholder="Student ID" required>
    <input type="text" name="Name" placeholder="Name" required>
    <input type="date" name="FirstYear" required>
    <input type="submit" name="insert" value="Add Student">
</form>

<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "university");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search Logic
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM Students";
if (!empty($search)) {
    $sql .= " WHERE Name LIKE '%$search%' OR StudentID LIKE '%$search%'";
}
$result = $conn->query($sql);

// Insert Logic
if (isset($_POST['insert'])) {
    $StudentID = $_POST['StudentID'];
    $Name = $_POST['Name'];
    $FirstYear = $_POST['FirstYear'];

    $insertSQL = "INSERT INTO Students (StudentID, Name, FirstYear) VALUES ('$StudentID', '$Name', '$FirstYear')";
    if ($conn->query($insertSQL) === TRUE) {
        echo "<p>Student added successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Delete Logic
if (isset($_POST['delete'])) {
    $StudentID = $_POST['StudentID'];
    $deleteSQL = "DELETE FROM Students WHERE StudentID = '$StudentID'";
    if ($conn->query($deleteSQL) === TRUE) {
        echo "<p>Student deleted successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Update Logic
if (isset($_POST['update'])) {
    $StudentID = $_POST['StudentID'];
    $Name = $_POST['Name'];
    $FirstYear = $_POST['FirstYear'];

    $updateSQL = "UPDATE Students SET Name = '$Name', FirstYear = '$FirstYear' WHERE StudentID = '$StudentID'";
    if ($conn->query($updateSQL) === TRUE) {
        echo "<p>Student updated successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!-- Students Table -->
<table>
    <tr>
        <th>Student ID</th>
        <th>Name</th>
        <th>First Year</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['StudentID']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['FirstYear']}</td>
                <td>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='StudentID' value='{$row['StudentID']}'>
                        <input type='text' name='Name' value='{$row['Name']}' required>
                        <input type='date' name='FirstYear' value='{$row['FirstYear']}' required>
                        <input type='submit' name='update' value='Update'>
                    </form>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='StudentID' value='{$row['StudentID']}'>
                        <input type='submit' name='delete' value='Delete'>
                    </form>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No records found</td></tr>";
    }
    ?>
</table>

</body>
</html>
