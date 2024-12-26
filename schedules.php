<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Schedules</title>
    <style>
        /* Same CSS as students.php */
    </style>
</head>
<body>

<h1>Manage Schedules</h1>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by Course ID or Day">
    <input type="submit" value="Search">
</form>

<form method="POST" action="">
    <input type="number" name="CourseID" placeholder="Course ID" required>
    <input type="text" name="Day" placeholder="Day (e.g., Monday)" required>
    <input type="submit" name="insert" value="Add Schedule">
</form>

<?php
$conn = new mysqli("localhost", "root", "", "university");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM Schedules";
if (!empty($search)) $sql .= " WHERE CourseID LIKE '%$search%' OR Day LIKE '%$search%'";
$result = $conn->query($sql);

if (isset($_POST['insert'])) {
    $CourseID = $_POST['CourseID'];
    $Day = $_POST['Day'];
    $insertSQL = "INSERT INTO Schedules (CourseID, Day) VALUES ('$CourseID', '$Day')";
    if ($conn->query($insertSQL)) echo "<p>Schedule added successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['delete'])) {
    $CourseID = $_POST['CourseID'];
    $Day = $_POST['Day'];
    $deleteSQL = "DELETE FROM Schedules WHERE CourseID = '$CourseID' AND Day = '$Day'";
    if ($conn->query($deleteSQL)) echo "<p>Schedule deleted successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

?>

<table>
    <tr>
        <th>Course ID</th>
        <th>Day</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['CourseID']}</td>
                <td>{$row['Day']}</td>
                <td>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='CourseID' value='{$row['CourseID']}'>
                        <input type='hidden' name='Day' value='{$row['Day']}'>
                        <input type='submit' name='delete' value='Delete'>
                    </form>
                </td>
            </tr>";
        }
    } else echo "<tr><td colspan='3'>No records found</td></tr>";
    ?>
</table>

</body>
</html>
