<?php
    // (C)
    require_once 'filters/login_filter.php';
    // idというキーワードに紐づいて飛んできた値を取得
    $id  = $_GET['id'];
    // GET通信で飛んできたidからPostインスタンスを復元
    $post = Post::find($id);
    // $posts = $login_user->post_extract();
    var_dump($post);
    $flush = $_SESSION['flush'];
    $_SESSION['flush'] = null;
    include_once 'views/posts_show_view.php';