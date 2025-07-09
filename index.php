<?php
session_start();
require_once 'db_connect.php';

// SQL: pollsテーブルとusersテーブルを結合して必要な情報を取得
// ORDER BY polls.created_at DESC で新しい順に並び替え
$sql = "SELECT 
            p.id, 
            p.title, 
            u.name AS author_name 
        FROM 
            polls AS p 
        JOIN 
            users AS u ON p.user_id = u.id 
        ORDER BY 
            p.created_at DESC";

$stmt = $pdo->query($sql);

// 結果をすべて連想配列として取得
$polls = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>トップページ</title>
</head>
<body>
    <h1>投票一覧</h1>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>ようこそ、<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?>さん</p>
        <a href="create_poll.php">新しい投票を作成する</a> | <a href="logout.php">ログアウト</a>
    <?php else: ?>
        <a href="login.php">ログイン</a> | <a href="register.php">ユーザー登録</a>
    <?php endif; ?>

    <hr>

    <?php foreach ($polls as $poll): ?>
        <div>
            <h3>
                <a href="poll.php?id=<?php echo $poll['id']; ?>">
                    <?php echo htmlspecialchars($poll['title'], ENT_QUOTES, 'UTF-8'); ?>
                </a>
            </h3>
            <p>作成者: <?php echo htmlspecialchars($poll['author_name'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <hr>
    <?php endforeach; ?>

</body>
</html>