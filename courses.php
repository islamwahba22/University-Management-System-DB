<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <style>
        /* Same CSS as students.php */
    </style>
</head>
<body>

<h1>Manage Courses</h1>

<!-- Search Form -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by Title or ID">
    <input type="submit" value="Search">
</form>

<!-- Insert Form -->
<form method="POST" action="">
    <input type="number" name="CourseID" placeholder="Course ID" required>
    <input type="text" name="Title" placeholder="Title" required>
    <input type="number" name="Credits" placeholder="Credits" required>
    <input type="number" name="FacultyID" placeholder="Faculty ID" required>
    <input type="submit" name="insert" value="Add Course">
</form>

<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "university");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search Logic
$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM Courses";
if (!empty($search)) {
    $sql .= " WHERE Title LIKE '%$search%' OR CourseID LIKE '%$search%'";
}
$result = $conn->query($sql);

// Insert Logic
if (isset($_POST['insert'])) {
    $CourseID = $_POST['CourseID'];
    $Title = $_POST['Title'];
    $Credits = $_POST['Credits'];
    $FacultyID = $_POST['FacultyID'];

    $insertSQL = "INSERT INTO Courses (CourseID, Title, Credits, FacultyID) VALUES ('$CourseID', '$Title', '$Credits', '$FacultyID')";
    if ($conn->query($insertSQL) === TRUE) {
        echo "<p>Course added successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Delete Logic
if (isset($_POST['delete'])) {
    $CourseID = $_POST['CourseID'];
    $deleteSQL = "DELETE FROM Courses WHERE CourseID = '$CourseID'";
    if ($conn->query($deleteSQL) === TRUE) {
        echo "<p>Course deleted successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}

// Update Logic
if (isset($_POST['update'])) {
    $CourseID = $_POST['CourseID'];
    $Title = $_POST['Title'];
    $Credits = $_POST['Credits'];
    $FacultyID = $_POST['FacultyID'];

    $updateSQL = "UPDATE Courses SET Title = '$Title', Credits = '$Credits', FacultyID = '$FacultyID' WHERE CourseID = '$CourseID'";
    if ($conn->query($updateSQL) === TRUE) {
        echo "<p>Course updated successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!-- Courses Table -->
<table>
    <tr>
        <th>Course ID</th>
        <th>Title</th>
        <th>Credits</th>
        <th>Faculty ID</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['CourseID']}</td>
                <td>{$row['Title']}</td>
                <td>{$row['Credits']}</td>
                <td>{$row['FacultyID']}</td>
                <td>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='CourseID' value='{$row['CourseID']}'>
                        <input type='text' name='Title' value='{$row['Title']}' required>
                        <input type='number' name='Credits' value='{$row['Credits']}' required>
                        <input type='number' name='FacultyID' value='{$row['FacultyID']}' required>
                        <input type='submit' name='update' value='Update'>
                    </form>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='CourseID' value='{$row['CourseID']}'>
                        <input type='submit' name='delete' value='Delete'>
                    </form>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No records found</td></tr>";
    }
    ?>
</table>

</body>
</html>
