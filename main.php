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

// Create
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $sql = "INSERT INTO items (name, description) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $description]);
    echo "Data successfully added.<br>";
}

// Read
function readItems($pdo) {
    $sql = "SELECT * FROM items";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['id'] . ": " . $row['name'] . " - " . $row['description'] . "<br>";
    }
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $newName = $_POST['new_name'];
    $newDescription = $_POST['new_description'];
    $sql = "UPDATE items SET name = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newName, $newDescription, $id]);
    echo "Record successfully updated.<br>";
}

// Delete
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
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

    <h2>Read</h2>
    <?php readItems($pdo); ?>

    <h2>Update</h2>
    <form method="POST">
        <input type="number" name="id" placeholder="ID" required>
        <input type="text" name="new_name" placeholder="New Name" required>
        <textarea name="new_description" placeholder="New Description" required></textarea>
        <button type="submit" name="update">Update</button>
    </form>

    <h2>Delete</h2>
    <form method="POST">
        <input type="number" name="delete_id" placeholder="ID" required>
        <button type="submit" name="delete">Delete</button>
    </form>
</body>
</html>
