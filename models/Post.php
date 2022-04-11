<?php
    require_once 'models/Model.php';
    // モデル(M)
    // 投稿の設計図
    class Post extends Model{
        // プロパティ
        public $id; // ID
        public $user_id; // ユーザーID
        public $title; // タイトル
        public $content; // 内容
        public $image; // 画像
        public $created_at; // 登録日時
        public $updated_at; // 更新日時
        // コンストラクタ
        public function __construct($user_id='', $title='', $content='', $image=''){
            // プロパティの初期化
            $this->user_id = $user_id;
            $this->title = $title;
            $this->content = $content;
            $this->image = $image;
        }
        // 入力値を検証するメソッド
        public function validate(){
            $errors = array();
            if($this->title === '') {
                $errors[] = 'タイトルを入力してください';
            }
            if($this->content === '') {
                $errors[] = '内容を入力してください';
            }
            if($this->image === '') {
                $errors[] = '画像を選択してください';
            }
            
            return $errors;
            
        }
        
        // 全テーブル情報を取得するメソッド
        public static function all(){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->query('SELECT posts.id, users.name, posts.title, posts.content, posts.image, posts.created_at FROM posts JOIN users ON posts.user_id=users.id ORDER BY posts.id DESC');
                // フェッチの結果を、Postクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Post');
                $posts = $stmt->fetchAll();
                self::close_connection($pdo, $stmt);
                // Postクラスのインスタンスの配列を返す
                return $posts;
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
        // データを1件登録するメソッド
        public function save(){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->prepare("INSERT INTO posts(user_id, title, content, image) VALUES(:user_id, :title, :content, :image)");
                // バインド処理
                $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
                $stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
                $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);
                $stmt->bindParam(':image', $this->image, PDO::PARAM_STR);
                // 実行
                $stmt->execute();
                self::close_connection($pdo, $stmt);
                return "新規投稿が成功しました。";
                
            } catch (PDOException $e) {
                return '';
            }
        }
        
        // 指定されたidからPostデータを1件取得するメソッド
        public static function find($id){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id");
                // バインド処理
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                // 実行
                $stmt->execute();
                // フェッチの結果を、Postクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Post');
                $post = $stmt->fetch();
                self::close_connection($pdo, $stmt);
                // Postクラスのインスタンスを返す
                return $post;
                
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
        // ファイルをアップロードするメソッド
        public function upload(){
            // ファイルを選択していれば
            if (!empty($_FILES['image']['name'])) {
                // ファイル名をユニーク化
                $image = uniqid(mt_rand(), true); 
                // アップロードされたファイルの拡張子を取得
                $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
                $file = "uploads/posts/{$image}";
            
                // uploadディレクトリにファイル保存
                move_uploaded_file($_FILES['image']['tmp_name'], $file);
                
                // 画像ファイル名を更新
                $this->image = $image;
                
                return $image;
                
            }else{
                return null;
            }
        }
        
    }