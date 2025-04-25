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
    echo "<p class='success'>Data successfully added.</p>";
}

function readItems($pdo) {
    $sql = "SELECT * FROM items";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='record'>";
        echo "<p><strong>ID:</strong> {$row['id']}</p>";
        echo "<p><strong>Name:</strong> {$row['name']}</p>";
        echo "<p><strong>Description:</strong> {$row['description']}</p>";
        echo "</div>";
    }
}

function readItemById($pdo, $id) {
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "<div class='record'>";
        echo "<p><strong>ID:</strong> {$row['id']}</p>";
        echo "<p><strong>Name:</strong> {$row['name']}</p>";
        echo "<p><strong>Description:</strong> {$row['description']}</p>";
        echo "</div>";
    } else {
        echo "<p class='error'>No record found with ID {$id}.</p>";
    }
}

function updateItem($pdo, $id, $newName, $newDescription) {
    $sql = "UPDATE items SET name = ?, description = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$newName, $newDescription, $id]);
    echo "<p class='success'>Record successfully updated.</p>";
}

function deleteItem($pdo, $id) {
    $sql = "DELETE FROM items WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo "<p class='success'>Record successfully deleted.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input, textarea, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
        }
        .record {
            padding: 10px;
            margin-bottom: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
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
