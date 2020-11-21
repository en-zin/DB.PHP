<?php

$user = 'root';
$password = 'root';
$db = 'inventory';
$host = 'localhost';
$port = 3306;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link,
   $host,
   $user,
   $password,
   $db,
   $port
);



try {
    $db = new PDO('mysql:dbname=laravel-news;host=localhost;charset=utf8','root','root');
    echo '接続OK';
} catch(PDOException $e) {
    echo 'Dエラー:' . $e->getMessage();
};

date_default_timezone_set('Asia/Tokyo');

$id = $_GET['id'];  //URLのパラメータを取得
$data = [];
$comment_board = [];
$date = date("Y年m月d日 H時i分s秒");
$comment = $_POST['comment'];
$error_message = [];
$limit_comment = 50;

// メイン画面のタイトル内容の取得
$mysqli = new mysqli('localhost', 'root', 'root', 'laravel-news');

// テーブルboardを取得する処理を変数に入れる
// $sqlにMySQLでのsql処理を代入している
// Q SERECTは何の処理か sql処理とはなんぞや
$sql = "SELECT * FROM board";

// DB(データベース)に対して$sql処理を実行させている
// Q query()の役割は何か $mysqli -wo> query($sql);を変数で定義する意味とは
$board = $mysqli -> query($sql);

// 上記と同じ処理テーブル名が変わっているので取得している内容が違う
$sql = "SELECT * FROM comment_board";

$data = $mysqli->query($sql);

while($row = $data->fetch_assoc()) {

    $comment_board[] = $row;

}

if(mb_strlen($comment) >= $limit_comment) $error_message[] = '50文字以内でコメントを書いてください';

// POST通信が行われたときに起動する
// REQUEST_METHOD→ページにアクセスする際にリクエストされたメソッド(操作のこと寝る食べる)名を返す
// REQUEST_METHOD→現在されたページがリクエストされたメソッ
// だから問題です
// Q POST通信とは何か また書いているコードの内容を分かりやすく話してみ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(empty($error_message)) {

        if(!empty($comment)) {

            //  mysqlのinsert文を書いている状態 この時カラム内の型と違うとinsertされない
            $sql = "INSERT INTO `comment_board`( `comment`, `main_id`) VALUES('$comment', '$id')";

            // la
            $res = $mysqli->query($sql);

            $mysqli->close();

            header ("Location:" . $_SERVER['REQUEST_URI']);

            exit;

        // 消去ボタンが押されたら起動する
        } else if (isset($_POST['del'])) {

            //
            $sql = "DELETE FROM comment_board WHERE id =" . $_POST['del'];

            $res = $mysqli->query($sql);

            $mysqli->close();

            header("Location:" . $_SERVER['REQUEST_URI']);

            exit;

        } else {

            if(empty($comment)) $error_message[] = "コメントを記入してください";

        };

    };

};

?>



<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>

    <h1>
        <a href="http://localhost/DB/DBtask.php">PHPニュース</a>
    </h1>

    <?php foreach($board as $value) :?>

        <?php if($id === $value["id"]) :?>

            <p>
                <?php echo $value['title'] ?>
            </p>

            <p>
                <?php echo $value['txt'] ?>
            </p>

        <?php endif ?>

    <?php endforeach ?>


	<?php foreach($error_message as $value): ?>

		<p>
			<?php echo $value ?>
		</p>

	<?php endforeach ?>

<!-- ここからコメントの書き込み送信表示 -->
<hr>
    <form action="" method="post" >

     	<div>

     		<label for="comment">コメント：</label>

     		<textarea name="comment" id="comment" cols="20" rows="5"></textarea>

     	</div>

      <input class="btn" type="submit" name="btn_submit" value="送信">

    </form>


    <?php foreach(array_reverse($comment_board) as $comment_kye) :?>

        <form action="" method = "post" onsubmit ="return confirm_test()" >

            <?php if($id === $comment_kye['main_id'] ) :?>

                <p>
                    <?php echo $comment_kye['comment']?>
                </p>

                <input type = "hidden" name = "del" value = "<?php echo $comment_kye['id'] ?>">

                <input class = "btn" type = "submit" value = "消去">

            <?php endif?>

        </form>

    <?php endforeach ?>


    <!-- <form action="" method = "post" onsubmit ="return confirm_test()" >
         <?php foreach($comment_board as $comment_kye) :?>
            <?php if($id === $comment_kye['main_id'] ) :?>
                <p>
                    <?php echo $comment_kye['comment']; echo $comment_kye['id']; ?>
                    <input type="hidden" name="del" value="<?php echo $comment_kye['id']; ?>">
                </p>
                <input class = "btn" type="submit" value = "消去">
            <?php endif ?>
        <?php endforeach ?>

    </form> -->

    <!-- <?php foreach($comment_board as $comment_kye) :?>
    <form action="" method = "post" onsubmit ="return confirm_test()" >
        <?php if($id === $comment_kye['main_id'] ) :?>
            <p>
                <?php echo $comment_kye['comment']; echo $comment_kye['id']; ?>
            </p>
            <input type="hidden" name="del" value="<?php echo $comment_kye['id']; ?>">
            <input class = "btn" type="submit" value = "消去">
        <?php endif ?>
    </form>
    <?php endforeach ?> -->




<script src="js.js"></script>
</body>
</html>
