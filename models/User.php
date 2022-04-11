<?php
    require_once 'models/Post.php';
    // モデル(M)
    // ユーザーの設計図
    class User extends Model{
        // プロパティ
        public $id; // ID
        public $name; // 名前
        public $email; // メールアドレス
        public $password; // パスワード
        public $image; // 画像
        public $created_at; // 登録日時
        public $updated_at; // 更新日時
        // コンストラクタ
        public function __construct($name='', $email='', $password='', $image=''){
            // プロパティの初期化
            $this->name = $name;
            $this->email = $email;
            $this->password = $password;
            $this->image = $image;
        }
        // 入力値を検証するメソッド
        public function validate(){
            $errors = array();
            if($this->name === '') {
                $errors[] = '※名前を入力してください';
            }
            if($this->email === '') {
                $errors[] = '※メールアドレスを入力してください';
            }
            if(mb_strlen($this->password) < 5) {
                $errors[] = '※パスワードを5文字以上で入力してください';
            }
            if($this->image === '') {
                $errors[] = '※画像を選択してください';
            }
            
            return $errors;
            
        }
        
        // 全テーブル情報を取得するメソッド
        public static function all(){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->query('SELECT * FROM users ORDER BY id DESC');
                // フェッチの結果を、Userクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
                $users = $stmt->fetchAll();
                self::close_connection($pdo, $stmt);
                // Userクラスのインスタンスの配列を返す
                return $users;
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
        // データを1件登録するメソッド
        public function save(){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->prepare("INSERT INTO users(name, email, password, image) VALUES(:name, :email, :password, :image)");
                // バインド処理
                $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
                $stmt->bindParam(':image', $this->image, PDO::PARAM_STR);
                // 実行
                $stmt->execute();
                self::close_connection($pdo, $stmt);
                return $this->name . "さんの新規会員登録が成功しました。";
                
            } catch (PDOException $e) {
                return '';
            }
        }
        
        // ログイン判定メソッド
        public static function login($email, $password){
            try {
                $pdo = self::get_connection($email, $password);
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
                // バインド処理
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                // 実行
                $stmt->execute();
                // フェッチの結果を、Userクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
                $user = $stmt->fetch();
                self::close_connection($pdo, $stmt);
                // Userクラスのインスタンスを返す
                return $user;
                
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
        
        // 指定されたidからデータを1件取得するメソッド
        public static function find($id){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
                // バインド処理
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                // 実行
                $stmt->execute();
                // フェッチの結果を、Userクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User');
                $user = $stmt->fetch();
                self::close_connection($pdo, $stmt);
                // Userクラスのインスタンスを返す
                return $user;
                
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
                $file = "uploads/users/{$image}";
            
                // uploadディレクトリにファイル保存
                move_uploaded_file($_FILES['image']['tmp_name'], $file);
                
                // 画像ファイル名を更新
                $this->image = $image;
                
                return $image;
                
            }else{
                return null;
            }
        }
        // 注目するユーザーが投稿した投稿一覧を取得
        public function posts(){
            try {
                $pdo = self::get_connection();
                $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id=:user_id ORDER BY id DESC");
                // バインド処理
                $stmt->bindParam(':user_id', $this->id, PDO::PARAM_INT);
                // 実行
                $stmt->execute();
                // フェッチの結果を、Postクラスのインスタンスにマッピングする
                $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Post');
                $posts = $stmt->fetchAll();
                self::close_connection($pdo, $stmt);
                // Postクラスのインスタンスを返す
                return $posts;
                
            } catch (PDOException $e) {
                return 'PDO exception: ' . $e->getMessage();
            }
        }
    }