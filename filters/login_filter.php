<?php
    // ログインユーザーに保存されているかを確認
    require_once 'models/User.php';
    session_start();
    $login_user = $_SESSION['login_user'];
    if($login_user === null) {
        header('Location: login.php');
        exit;
    }