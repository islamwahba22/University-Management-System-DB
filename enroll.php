<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], input[type="date"], input[type="submit"] {
            padding: 5px;
            margin: 5px;
        }
    </style>
</head>
<body>

<h1>Manage Enrollments</h1>

<!-- Search Form -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by Student ID or Course ID">
    <input type="submit" value="Search">
</form>

<!-- Insert Form -->
<form method="POST" action="">
    <input type="number" name="StudentID" placeholder="Student ID" required>
    <input type="number" name="CourseID" placeholder="Course ID" required>
    <input type="date" name="EDate" required>
    <input type="submit" name="insert" value="Enroll Student">
</form>

<?php
$conn = new mysqli("localhost", "root", "", "university");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM Enroll";
if (!empty($search)) $sql .= " WHERE StudentID LIKE '%$search%' OR CourseID LIKE '%$search%'";
$result = $conn->query($sql);

if (isset($_POST['insert'])) {
    $StudentID = $_POST['StudentID'];
    $CourseID = $_POST['CourseID'];
    $EDate = $_POST['EDate'];
    $insertSQL = "INSERT INTO Enroll (StudentID, CourseID, EDate) VALUES ('$StudentID', '$CourseID', '$EDate')";
    if ($conn->query($insertSQL)) echo "<p>Student enrolled successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['delete'])) {
    $StudentID = $_POST['StudentID'];
    $CourseID = $_POST['CourseID'];
    $deleteSQL = "DELETE FROM Enroll WHERE StudentID = '$StudentID' AND CourseID = '$CourseID'";
    if ($conn->query($deleteSQL)) echo "<p>Enrollment deleted successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['update'])) {
    $StudentID = $_POST['StudentID'];
    $CourseID = $_POST['CourseID'];
    $EDate = $_POST['EDate'];
    $updateSQL = "UPDATE Enroll SET EDate = '$EDate' WHERE StudentID = '$StudentID' AND CourseID = '$CourseID'";
    if ($conn->query($updateSQL)) echo "<p>Enrollment updated successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}
?>

<table>
    <tr>
        <th>Student ID</th>
        <th>Course ID</th>
        <th>Enrollment Date</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['StudentID']}</td>
                <td>{$row['CourseID']}</td>
                <td>{$row['EDate']}</td>
                <td>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='StudentID' value='{$row['StudentID']}'>
                        <input type='hidden' name='CourseID' value='{$row['CourseID']}'>
                        <input type='date' name='EDate' value='{$row['EDate']}' required>
                        <input type='submit' name='update' value='Update'>
                    </form>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='StudentID' value='{$row['StudentID']}'>
                        <input type='hidden' name='CourseID' value='{$row['CourseID']}'>
                        <input type='submit' name='delete' value='Delete'>
                    </form>
                </td>
            </tr>";
        }
    } else echo "<tr><td colspan='4'>No records found</td></tr>";
    ?>
</table>

</body>
</html>
