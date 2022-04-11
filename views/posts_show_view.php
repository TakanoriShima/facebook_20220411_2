<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <!-- ViewPort Setting -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <!-- Original CSS -->
        <link rel="stylesheet" href="css/style.css">
        <!-- Favicon -->
        <link rel="icon" href="images/favicon.ico">
        <title><?= $login_user->name ?>さんの投稿番号<?= $post->id ?>の詳細</title>
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- Original JavaScript -->
        <script src="js/script.js"></script>
    </head>
    <body style="background: url(images/gray.jpg);">
        <?php include_once 'views/_navbar_view.php'; ?>
        <div class="container p-2">
            <div class="row mt-3 mb-3">
                <h1 class="col-sm-12 text-center text-primary"><?= $login_user->name ?>さんの投稿番号<?= $post->id ?>の詳細</h1>
            </div>
            <div class="row mt-2">
                <table class="table table-bordered table-striped">
                    <tr class="text-center">
                        <th>投稿番号</th>
                        <th>タイトル</th>
                        <th>内容</th>
                        <th>画像</th>
                        <th>投稿日時</th>
                    </tr>
                    <tr class="text-center">
                        <td><?= $post->id ?></td>
                        <td><?= $post->title ?></td>
                        <td><?= $post->content ?></td>
                        <td><img src="uploads/posts/<?= $post->image ?>" alt="<?= $post->image ?>" class="post_img"></td>
                        <td><?= $post->created_at ?></td>
                    </tr>
                </table>
            </div>
            <div class="row mt-5">
                <a href="posts_edit.php" class="offset-sm-3 col-sm-6 btn btn-success">編集</a>
            </div>
        </div>
    </body>
</html> 