<?php

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/app.css" />
    <title>ログイン</title>
    <link rel="stylesheet" href="/css/tailwind.css" />
    <style>
        .mock-box {
            position: relative;
            margin: 2em 0;
            padding: 25px 10px 7px;
            border: solid 2px #FFC107;
        }
        .mock-box .box-title {
            position: absolute;
            display: inline-block;
            top: -2px;
            left: -2px;
            padding: 0 9px;
            height: 25px;
            line-height: 25px;
            font-size: 17px;
            background: #FFC107;
            color: #ffffff;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="mock-box">
    <span class="box-title">これはモックです。UI仕様ではありません。</span>

    <div class="container">
        <div class="card card-primary mb-3">
            <div class="card-header">
                <h5 class="card-title">ユーザアカウント管理機能</h5>
            </div>
            <div class="card-body">
                <form method="post" action="/uam/login/">
                    @csrf
                    <section class="text-gray-600 body-font relative">
                        <h1 class="text-4xl text-green-700 text-center font-semibold">ログイン</h1>
                        <div class="container px-5 py-5 mx-auto flex justify-center">
                            <div class="bg-white p-8 flex flex-col mt-10 relative z-10 shadow-md">
                                <div class="relative mb-4">
                                    <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                                    <input type="email" id="email" name="email" value="" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <div class="relative mb-4">
                                    <label for="message" class="leading-7 text-sm text-gray-600">password</label>
                                    <label for="password"></label><input type="password" id="password" name="password" value="" class="w-full bg-white rounded border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                </div>
                                <button class="text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">ログイン</button>
                            </div>
                        </div>
                    </section>

                </form>
            </div>
        </div>
    </div>
    <script src="/js/app.js"></script>

</div>

</body>
</html>
