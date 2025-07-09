<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送られたデータを変数に格納
    $username = $_POST['username'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
    $stmt->bindParam(':name', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            header("Location: index.php");
            exit(); // headerの後は必ずexit()を書く癖をつける
        } else {
            $error_message = "ユーザー名またはパスワードが違います。";
        }
    } else {
        $error_message = "ユーザー名またはパスワードが違います。";
    }

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <style> /* 簡単なスタイル */
        body { font-family: sans-serif; max-width: 500px; margin: 2em auto; padding: 1em; border: 1px solid #ccc; border-radius: 5px; }
        .form-group { margin-bottom: 1em; }
        label { display: block; margin-bottom: .5em; }
        input { width: 100%; padding: .5em; box-sizing: border-box; }
        button { padding: .5em 1em; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>ログイン</h2>

    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo $success_message; // HTMLを許可するためhtmlspecialcharsは使わない ?></p>
    <?php else: ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
    <?php endif; ?>

</body>
</html>