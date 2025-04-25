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

createItem($pdo, 'Example item', 'Item description');
readItems($pdo);
updateItem($pdo, 1, 'Updated name', 'Updated description');
deleteItem($pdo, 1);
?>
