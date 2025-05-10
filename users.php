<?php
// session_start();

// // Khởi tạo mảng user nếu chưa có
// if (!isset($_SESSION['users'])) {
//     $_SESSION['users'] = [
//         ['id' => 1, 'name' => 'Nguyễn Văn A', 'email' => 'a@example.com'],
//         ['id' => 2, 'name' => 'Trần Thị B',   'email' => 'b@example.com'],
//     ];
// }

$host = "localhost";
$db   = "demo_db";
$user = "root";
$pass = "";
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
    die("Lỗi kết nối DB: " . $e->getMessage());
}

// Xử lý thêm user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    if ($name && $email) {
        // $id = end($_SESSION['users'])['id'] + 1;
        // $_SESSION['users'][] = ['id' => $id, 'name' => $name, 'email' => $email];
        if ($name && $email) {
            $stmt = $pdo->prepare("INSERT INTO _users (name, email) VALUES (?, ?)");
            $stmt->execute([$name, $email]);
        }
    }
    header("Location: users.php");
    exit;
}

// Xử lý xoá user
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    // $_SESSION['users'] = array_filter($_SESSION['users'], fn($u) => $u['id'] !== $deleteId);
    // $_SESSION['users'] = array_values($_SESSION['users']); // reset index
    $stmt = $pdo->prepare("DELETE FROM _users WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: users.php");
    exit;
}
// Lấy danh sách user
$stmt = $pdo->query("SELECT * FROM _users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $users = $_SESSION['users'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <style>
        table { border-collapse: collapse; width: 60%; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        form { width: 60%; margin: 20px auto; }
        input[type="text"], input[type="email"] { width: 100%; padding: 6px; margin: 6px 0; }
        input[type="submit"] { padding: 6px 12px; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Quản lý người dùng</h2>

    <form method="post">
        <input type="hidden" name="action" value="add">
        <label>Họ tên:</label><br>
        <input type="text" name="name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <input type="submit" value="Thêm người dùng">
    </form>

    <table>
        <tr>
            <th>ID</th><th>Tên</th><th>Email</th><th>Hành động</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>
                <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Xoá người dùng này?');">Xoá</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
