<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Faculty Info</title>
    <style>
        /* Same CSS as students.php */
    </style>
</head>
<body>

<h1>Manage Faculty Info</h1>

<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by Name or Department">
    <input type="submit" value="Search">
</form>

<form method="POST" action="">
    <input type="number" name="FacultyID" placeholder="Faculty ID" required>
    <input type="text" name="Name" placeholder="Name" required>
    <input type="text" name="Department" placeholder="Department" required>
    <input type="submit" name="insert" value="Add Faculty">
</form>

<?php
$conn = new mysqli("localhost", "root", "", "university");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM Faculty_Info";
if (!empty($search)) $sql .= " WHERE Name LIKE '%$search%' OR Department LIKE '%$search%'";
$result = $conn->query($sql);

if (isset($_POST['insert'])) {
    $FacultyID = $_POST['FacultyID'];
    $Name = $_POST['Name'];
    $Department = $_POST['Department'];
    $insertSQL = "INSERT INTO Faculty_Info (FacultyID, Name, Department) VALUES ('$FacultyID', '$Name', '$Department')";
    if ($conn->query($insertSQL)) echo "<p>Faculty added successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['delete'])) {
    $FacultyID = $_POST['FacultyID'];
    $deleteSQL = "DELETE FROM Faculty_Info WHERE FacultyID = '$FacultyID'";
    if ($conn->query($deleteSQL)) echo "<p>Faculty deleted successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}

if (isset($_POST['update'])) {
    $FacultyID = $_POST['FacultyID'];
    $Name = $_POST['Name'];
    $Department = $_POST['Department'];
    $updateSQL = "UPDATE Faculty_Info SET Name = '$Name', Department = '$Department' WHERE FacultyID = '$FacultyID'";
    if ($conn->query($updateSQL)) echo "<p>Faculty updated successfully!</p>"; else echo "<p>Error: " . $conn->error . "</p>";
}
?>

<table>
    <tr>
        <th>Faculty ID</th>
        <th>Name</th>
        <th>Department</th>
        <th>Actions</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['FacultyID']}</td>
                <td>{$row['Name']}</td>
                <td>{$row['Department']}</td>
                <td>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='FacultyID' value='{$row['FacultyID']}'>
                        <input type='text' name='Name' value='{$row['Name']}' required>
                        <input type='text' name='Department' value='{$row['Department']}' required>
                        <input type='submit' name='update' value='Update'>
                    </form>
                    <form style='display:inline-block;' method='POST' action=''>
                        <input type='hidden' name='FacultyID' value='{$row['FacultyID']}'>
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
