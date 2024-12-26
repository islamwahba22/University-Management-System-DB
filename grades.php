<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Grades</title>
    <style>
        /* Same CSS as students.php */
    </style>
</head>
<body>

<h1>Manage Grades</h1>

<!-- Search Form -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by Student ID or Course ID">
    <input type="submit" value="Search">
</form>

<!-- Insert Form -->
<form method="POST" action="">
    <input type="number" name="StudentID" placeholder="Student ID" required>
    <input type="number" name="CourseID" placeholder="Course ID" required>
    <input type="text" name="Grade" placeholder="Grade (e.g., A, B+)" maxlength="2" required>
    <input type="submit" name="insert" value="Add Grade">
</form>

<?php
$conn = new mysqli("localhost", "root", "", "university");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM Grades";
if (!empty($search)) $sql .= " WHERE StudentID LIKE '%$search%' OR CourseID LIKE '%$search%'";
$result = $conn->query($sql);

if (isset($_POST['insert'])) {
    $StudentID = $_POST['StudentID'];
    $CourseID = $_POST['CourseID'];
    $Grade = $_POST['Grade'];
    $insertSQL = "INSERT INTO Grades (StudentID, CourseID, Grade) VALUES ('$StudentID', '$CourseID', '$Grade')";
    if ($conn->query($insertSQL)) echo "<p>Grade added successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['delete'])) {
    $StudentID = $_POST['StudentID'];
    $CourseID = $_POST['CourseID'];
    $deleteSQL = "DELETE FROM Grades WHERE StudentID = '$StudentID' AND CourseID = '$CourseID'";
    if ($conn->query($deleteSQL)) echo "<p>Grade deleted successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['update'])) {
    $StudentID = $_POST['StudentID'];
    $CourseID = $_POST['CourseID'];
    $Grade = $_POST['Grade'];
    $updateSQL = "UPDATE Grades SET Grade = '$Grade' WHERE StudentID = '$StudentID' AND CourseID = '$CourseID'";
    if ($conn->query($updateSQL)) echo "<p>Grade updated successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}
?>

<table>
    <tr>
        <th>Student ID</th>
        <th>Course ID</th>
        <th>Grade</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['StudentID']}</td>
                <td>{$row['CourseID']}</td>
                <td>{$row['Grade']}</td>
                <td>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='StudentID' value='{$row['StudentID']}'>
                        <input type='hidden' name='CourseID' value='{$row['CourseID']}'>
                        <input type='text' name='Grade' value='{$row['Grade']}' maxlength='2' required>
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
