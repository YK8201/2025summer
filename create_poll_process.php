<?php
session_start();
require_once 'db_connect.php';

// ログインしていなければ、処理を中断してログインページへ
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
// POSTリクエストでなければ、処理を中断
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

$title = $_POST['title'];
$choices = $_POST['choices']; // これは配列になっているはず
$user_id = $_SESSION['user_id']; // セッションからログイン中のユーザーIDを取得

// チェックボックスは、チェックされている場合のみ値が送られる
$allow_multiple = isset($_POST['allow_multiple']) ? 1 : 0;

try {
    // 1. トランザクションを開始
    $pdo->beginTransaction();

    // 2. まず`polls`テーブルに議題をINSERT
    $stmt = $pdo->prepare("INSERT INTO polls (user_id, title, allow_multiple) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $title, $allow_multiple]);

    // 3. ★重要★ 今INSERTしたpollのIDを取得
    $poll_id = $pdo->lastInsertId();

    // 4. 次に`choices`テーブルに選択肢をループでINSERT
    $stmt = $pdo->prepare("INSERT INTO choices (poll_id, name) VALUES (?, ?)");
    foreach ($choices as $choice) {
        // 空の選択肢は無視する
        if (!empty($choice)) {
            $stmt->execute([$poll_id, $choice]);
        }
    }

    // 5. すべて成功したら、トランザクションを確定
    $pdo->commit();

    // 6. 成功ページへリダイレクト
    header("Location: index.php"); // とりあえずトップへ
    exit();

} catch (PDOException $e) {
    // 6. エラーが起きたら、トランザクションをロールバック（処理をすべて元に戻す）
    $pdo->rollBack();
    exit("エラーが発生しました: " . $e->getMessage());
}
?>