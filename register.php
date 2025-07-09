<?php
// --- PHP処理 ---

// DB接続情報 ※自分の環境に合わせて変更してください
$db_host = 'localhost';     // データベースのホスト
$db_name = 'minna_no_iken'; // データベース名
$db_user = 'root';          // データベースのユーザー名
$db_pass = 'root';              // データベースのパスワード

// データベースへの接続
try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_pass);
    // エラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラーの際の処理
    exit("データベースに接続できませんでした。: " . $e->getMessage());
}

// フォームが送信された（POSTリクエストがあった）場合の処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送られたデータを変数に格納
    $name = $_POST['username'];
    $password = $_POST['password'];

    // 簡単なバリデーション
    if (empty($name) || empty($password)) {
        $error_message = "ユーザー名とパスワードの両方を入力してください。";
    } else {
        // パスワードをハッシュ化（セキュリティ対策）
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // データベースにユーザー情報を挿入する準備 (SQLインジェクション対策)
            $stmt = $pdo->prepare("INSERT INTO users (name, password) VALUES (:name, :password)");
            
            // 値をバインド
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            
            // SQLを実行
            $stmt->execute();
            
            $success_message = "登録が完了しました！ <a href='login.php'>ログインページへ</a>";

        } catch (PDOException $e) {
            // ユニーク制約違反などのエラー処理
            if ($e->getCode() == 23000) {
                $error_message = "そのユーザー名は既に使用されています。";
            } else {
                $error_message = "登録中にエラーが発生しました。: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
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
    <h2>ユーザー登録</h2>

    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo $success_message; // HTMLを許可するためhtmlspecialcharsは使わない ?></p>
    <?php else: ?>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">ユーザー名</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">登録する</button>
        </form>
    <?php endif; ?>

</body>
</html>