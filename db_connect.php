<?php
// --- ここから追加 ---
// DB接続情報 ※自分の環境に合わせて変更してください
$db_host = 'localhost';          // データベースのホスト
$db_name = 'minna_no_iken';  // データベース名
$db_user = 'root';               // データベースのユーザー名
$db_pass = 'root';               // データベースのパスワード (MAMPの初期設定は'root')
// --- ここまで追加 ---
// データベースへの接続
try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8", $db_user, $db_pass);
    // エラーモードを例外に設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラーの際の処理
    exit("データベースに接続できませんでした。: " . $e->getMessage());
}
?>