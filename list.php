<?php


require 'db.php';

// Xử lý xoá user
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    // $_SESSION['users'] = array_filter($_SESSION['users'], fn($u) => $u['id'] !== $deleteId);
    // $_SESSION['users'] = array_values($_SESSION['users']); // reset index
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: users.php");
    exit;
}
// Lấy danh sách user
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $users = $_SESSION['users'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- <link id="themeLink" rel="stylesheet" href="light-theme.css"> -->
    <link id="themeLink" rel="stylesheet" href="night-theme.css">
    
    <!-- <style>
        table { border-collapse: collapse; ; margin: 20px auto; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
       
        input[type="text"], input[type="email"] { width: 100%; padding: 6px; margin: 6px 0; }
        input[type="submit"] { padding: 6px 12px; }
    </style> -->
    <script>
        function toggleTheme() {
            const themeLink = document.getElementById('themeLink');
            const themeButton = document.getElementById('themeButton');
            
            // Chuyển giữa light-theme và night-theme
            if (themeLink.getAttribute('href') === 'light-theme.css') {
                themeLink.setAttribute('href', 'night-theme.css');
                themeButton.innerHTML = 'Switch to Light Theme'; // Thay đổi nội dung nút
            } else {
                themeLink.setAttribute('href', 'light-theme.css');
                themeButton.innerHTML = 'Switch to Night Theme'; // Thay đổi nội dung nút
            }
        }
    </script>
</head>
<body>
    <h2 style="text-align:center;">User List</h2>

    <div style="width: 60%; margin: 20px auto;">
        <button id="themeButton" onclick="toggleTheme()">Switch to Night Theme</button><br><br>
        <a href="form.php">+ Add User</a>
        <table  style="width: 100%; margin-top: 20px;">
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Action</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                  <a href="form.php?id=<?= $user['id'] ?>">Edit</a> |
                    <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Delete this user?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
