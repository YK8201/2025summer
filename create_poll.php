<?php
session_start(); // セッションを使うので必須

// もし$_SESSION['user_id']が存在しない（セットされていない）なら
if (!isset($_SESSION['user_id'])) {
    // ログインページへリダイレクト
    header('Location: login.php');
    exit();
}

// --- ここから下に、ログインしているユーザーだけが見れるHTMLを書いていく ---
?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>投票作成</title>
    </head>
    <body>
        <form action="create_poll_process.php" method="post">
            <div class="form-group">
                <label for="title">議題</label><br>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label>選択肢</label>
                <div id="choices-container">
                    <div class="choice-item">
                        <input type="text" name="choices[]" class="choice-input" placeholder="選択肢 1" required>
                    </div>
                    <div class="choice-item">
                        <input type="text" name="choices[]" class="choice-input" placeholder="選択肢 2" required>
                    </div>
                </div>
            </div>
            
            <button type="button" id="add-choice-btn">選択肢を追加</button>
            <hr>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="allow_multiple" value="1"> 複数回答を許可する
                </label>
            </div>
    
            <button type="submit">投票を作成する</button>
        </form>
        <script>
        // id="add-choice-btn" の要素（追加ボタン）を取得
        const addChoiceBtn = document.getElementById('add-choice-btn');

        // id="choices-container" の要素（入力欄の親DIV）を取得
        const choicesContainer = document.getElementById('choices-container');

        // 「選択肢を追加」ボタンがクリックされた時の処理
        addChoiceBtn.addEventListener('click', function() {
            
            // --- ここからがメインの処理 ---

            // 1. 新しい要素たちを準備
            const newItemWrapper = document.createElement('div'); // 入力欄と削除ボタンをまとめるDIV
            newItemWrapper.className = 'choice-item';

            const newChoiceInput = document.createElement('input'); // 新しい入力欄
            newChoiceInput.type = 'text';
            newChoiceInput.name = 'choices[]';
            newChoiceInput.className = 'choice-input';
            newChoiceInput.placeholder = '追加の選択肢';
            newChoiceInput.required = true;

            const removeBtn = document.createElement('button'); // 新しい削除ボタン
            removeBtn.type = 'button'; // フォームが送信されないようにtypeを指定
            removeBtn.textContent = '削除'; // ボタンのテキスト

            // 2. ★重要★ 削除ボタンに「クリックされたら自分自身を消す」機能を追加
            removeBtn.addEventListener('click', function() {
                // このボタンが所属している親のDIV(newItemWrapper)を削除する
                newItemWrapper.remove();
            });

            // 3. 準備した要素を画面に配置
            //    まとめるDIVの中に、入力欄と削除ボタンを入れる
            newItemWrapper.appendChild(newChoiceInput);
            newItemWrapper.appendChild(removeBtn);

            //    入力欄とボタンのセットを、大元のコンテナに追加
            choicesContainer.appendChild(newItemWrapper);
        });
        </script>
    </body>
</html>