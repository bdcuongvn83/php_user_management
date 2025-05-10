<?php

require 'db.php';

$id = $_GET['id'] ?? null;
$name = "";
$email = "";

// Nếu có ID, load dữ liệu để sửa
// echo "id: " . htmlspecialchars($id) . "<br>";
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM _users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    // var_dump($user); // hoặc print_r($user);
    if ($user) {
        $name = $user['name'];
        $email = $user['email'];
        // echo "Name: " . htmlspecialchars($name) . "<br>";
        // echo "Email: " . htmlspecialchars($email) . "<br>";
        // echo "<script>console.log('Name: " . addslashes($name) . "');</script>";
        // echo "<script>console.log('Email: " . addslashes($email) . "');</script>";
        //  // Ghi log vào file
        //  error_log("User ID: " . $id . " | Name: " . $name . " | Email: " . $email);
    }
}

// Xử lý thêm user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    if ($name && $email) {
        if ($id) {
            // Cập nhật thông tin user
            $stmt = $pdo->prepare("UPDATE _users SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $id]);
        } else {
            // Thêm user mới
            $stmt = $pdo->prepare("INSERT INTO _users (name, email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);
        }
    
      
    }
    header("Location: list.php");
    exit;
}


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User management</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- <style>
        table { border-collapse: collapse; width: 60%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        form { width: 60%; margin: 20px auto; }
        input[type="text"], input[type="email"] { width: 100%; padding: 6px; margin: 6px 0; }
        input[type="submit"] { padding: 6px 12px; }
    </style> -->
</head>
<body>
    <h2 style="text-align:center;">User Management</h2>

    <form method="post">
        <input type="hidden" name="action" value="add">
        <label>UserName:</label><br>
        <input type="text" name="name" value="<?= $name ?>" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= $email ?>"  required><br>
        <input type="submit" value="<?= $id ? 'Update' : 'Register' ?>"> &nbsp;
        <input type="submit" value="Cancel" onclick="window.location.href='list.php'; return false;">
    </form>
   
</body>
</html>
