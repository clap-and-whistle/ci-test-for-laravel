<?php

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="/css/app.css" />
    <script src="/js/app.js"></script>
</head>
<body>
<div>
    <h1>HomeController:index</h1>
    <hr />

    <div>
        <h2>公開されたURL</h2>
        <h3>ユーザアカウント管理</h3>
        <ul>
            <li><a href="./uam/create-account/new">Sing Up</a></li>
            <li><a href="./uam/login/input">Login</a></li>
        </ul>

    </div>

    <hr />

    <div>
        <h2>認証保護されたURL</h2>
        <h3>仮のコンテキスト</h3>
        <ul>
            <li><a href="/desk/index">Myデスク</a></li>
        </ul>

    </div>

</div>
</body>
</html>
