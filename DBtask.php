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

date_default_timezone_set('Asia/Tokyo');



//今回使ってないけどpdoでの接続
try {
    $db = new PDO('mysql:dbname=laravel-news;host=localhost;charset=utf8','root','root');
    echo '接続OK';
} catch(PDOException $e) {
    echo 'Dエラー:' . $e->getMessage();
};




// $Fail = 'message.txt';	//ファイルのパスを指定してあげる
$date = date("Y-m-d- H:i:s");	//現在時刻の生成
$title = $_POST['title'];	//titleを引っ張ってくる
$text = $_POST['txt'];	//txtを引っ張ってくる
$data = []; //ボードから持ってきた情報をいったん保存する
$board = []; // 配列の配列として保存するための変数

$error_message = [];	//エラーメッセージを格納する
$limit_title = 10; //文字数制限
$limit_text = 50;	//文字数制限


$mysqli = new mysqli( 'localhost', 'root', 'root','laravel-news');

$sql = "SELECT * FROM board";

$data = $mysqli->query($sql);

// $dataで取得してきたデータを一つ一つ配列にして $rowにいれている
// $boardの配列のなかに保存する  [[]]こんな状態
// $rowに関しては特にいみがない
// Q 素の$dataだとどんな状態なのか... まじでわかってないので調べる必要があるかも
while($row = $data->fetch_assoc()) {

	$board[] = $row;

};

//文字が多いと起動
if(mb_strlen($title) >= $limit_title)  $error_message[] = 'タイトルは10文字以内でお願いします';
if(mb_strlen($text) >= $limit_text)  $error_message[] = '記事の内容50文字以内でお願いします';

// //	ボタンを押したら起動する
// //	Ｑ	詳しい処理内容は何か
if ($_SERVER["REQUEST_METHOD"] === 'POST') {

	//もしエラーメッセージが空だったら起動する
	if(empty($error_message)) {


		//両方空でなければ起動する
		if(!empty($title) && !empty($text)) {

			$mysqli->set_charset('utf8');

			// INSERT
			$sql = "INSERT INTO `board`( `title`, `txt`) VALUES ('$title','$text')";

			$res = $mysqli->query($sql);

			var_dump($res);

			$mysqli->close();

			header('Location:'.$_SERVER["SCRIPT_NAME"]);

			exit;

		  } else {

			if(empty($_POST['title'])) $error_message[] = 'タイトルを入力してください';

			if(empty($_POST['txt'])) $error_message[] = '記事を入力してください';

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

	<?php foreach($error_message as $value): ?>

		<p>
			<?php echo $value ?>
		</p>

	<?php endforeach ?>

	<form action="" method="post" onsubmit="return confirm_test()">

    	<div>

     		<label for="title">タイトル</label>

        	<input id="title" type="text" name="title" value="">

    	</div>

    	<div>

     		<label for="contents">記事の内容</label>

     		<textarea name="txt" id="contents" cols="30" rows="10"></textarea>

    	</div>

    	<input class="btn" type="submit" name="btn_submit" value="送信">

    </form>

<hr>


	<?php foreach(array_reverse($board) as $value):?>

		<p>
			<?php echo $value['title']?>
		</p>

		<p>
			<?php echo $value['txt']?>
		</p>

		<p>
			<a href="http://localhost/DB/DBdetails.php/?id=<?php echo $value['id'] ?>">明細ページ</a>
		</p>

	<?php endforeach ?>

<script src="js.js"></script>
</body>
</html>
