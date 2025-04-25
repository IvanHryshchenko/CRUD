<?php
$host = 'localhost';
$db = 'my_database';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection error: " . $e->getMessage());
}

function createItem($pdo, $name, $description) {
    $sql = "INSERT INTO items (name, description) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description]);
    echo "Data successfully added.<br>";
}

function readItems($pdo) {
    $sql = "SELECT * FROM items";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['id'] . ": " . $row['name'] . " - " . $row['description'] . "<br>";
    }
}

function readItemById($pdo, $id) {
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "<h3>Record Details:</h3>";
        echo "ID: " . $row['id'] . "<br>";
        echo "Name: " . $row['name'] . "<br>";
        echo "Description: " . $row['description'] . "<br>";
    } else {
        echo "No record found with ID $id.<br>";
    }
}

function updateItem($pdo, $id, $newName, $newDescription) {
    $sql = "UPDATE items SET name = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newName, $newDescription, $id]);
    echo "Record successfully updated.<br>";
}

function deleteItem($pdo, $id) {
    $sql = "DELETE FROM items WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo "Record successfully deleted.<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application</title>
</head>
<body>
    <h1>CRUD Application</h1>
    
    <h2>Create</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <button type="submit" name="create">Create</button>
    </form>

    <h2>Read All</h2>
    <?php
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        createItem($pdo, $name, $description);
    }
    readItems($pdo);
    ?>

    <h2>Read by ID</h2>
    <form method="GET">
        <input type="number" name="read_id" placeholder="Enter ID" required>
        <button type="submit">View Record</button>
    </form>

    <?php
    if (isset($_GET['read_id'])) {
        $id = $_GET['read_id'];
        readItemById($pdo, $id);
    }
    ?>

    <h2>Update</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="ID" required>
        <input type="text" name="new_name" placeholder="New Name" required>
        <textarea name="new_description" placeholder="New Description" required></textarea>
        <button type="submit" name="update">Update</button>
    </form>

    <?php
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $newName = $_POST['new_name'];
        $newDescription = $_POST['new_description'];
        updateItem($pdo, $id, $newName, $newDescription);
    }
    ?>

    <h2>Delete</h2>
    <form method="POST">
        <input type="number" name="delete_id" placeholder="ID" required>
        <button type="submit" name="delete">Delete</button>
    </form>

    <?php
    if (isset($_POST['delete'])) {
        $id = $_POST['delete_id'];
        deleteItem($pdo, $id);
    }
    ?>

    <h2>Back to Main Page</h2>
    <form action="index.php" method="GET">
        <button type="submit">Back</button>
    </form>
</body>
</html>
