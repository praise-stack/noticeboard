<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>mission_5-1</title>
</head>
<body>
    <!-- 投稿用フォーム -->
    <form method="POST">
        名前：<input type="text" name="name"><br>
        コメント：<input type="text" name="comment"><br>
        パスワード：<input type="password" name="password">
        <input type="submit" name="submit">
    </form>
    <br>
    <!-- 編集用フォーム -->
    <form method="POST">
        投稿番号：<input type="number" name="editnum" placeholder="番号を選択"><br>
        コメント：<input type="text" name="editcom"><br>
        パスワード：<input type="password" name="editpass">
        <input type="submit" name="edit" value="編集">
    </form>
    <br>
    <!-- 削除用フォーム -->
    <form method="POST">
        投稿番号：<input type="number" name="deletenum" placeholder="番号を選択"><br>
        パスワード：<input type="password" name="deletepass">
        <input type="submit" name="delete" value="削除">
    </form>
    
    <?php
        // 投稿処理
        if (isset($_POST["submit"])) {
            if (isset($_POST["name"], $_POST["comment"], $_POST["password"])) {
                $dsn = 'mysql:dbname=*****;host=localhost';
                $user = '*****';
                $password = '*****';
                $pdo = new PDO($dsn, $user, $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                $date = date("y/m/d h:i:s");
                
                $sql = $pdo -> prepare("INSERT INTO posts (name, comment, password, date) 
                VALUES (:name, :comment, :password, :date)");
            	$sql -> bindParam(':name', $_POST["name"], PDO::PARAM_STR);
            	$sql -> bindParam(':comment', $_POST["comment"], PDO::PARAM_STR);
            	$sql -> bindParam(':password', $_POST["password"], PDO::PARAM_STR);
            	$sql -> bindParam(':date', $date, PDO::PARAM_STR);
            	$sql -> execute();
            }
        }
        
        // 編集処理
        if (isset($_POST["edit"])) {
            if (isset($_POST["editnum"], $_POST["editcom"], $_POST["editpass"])) {
                $dsn = 'mysql:dbname=*****;host=localhost';
                $user = '*****';
                $password = '*****';
                $pdo = new PDO($dsn, $user, $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                $editnum = $_POST["editnum"];
                $editcom = $_POST["editcom"];
                $date = date("y/m/d h:i:s");
                
                $sql = "SELECT * FROM posts WHERE id = $editnum";
            	$stmt = $pdo->query($sql);
            	$result = $stmt->fetch();
            	
            	if ($result['password'] == $_POST["editpass"]) {
            	    $sql = "UPDATE posts SET comment = :comment, date = :date WHERE id = :id";
            	    $stmt = $pdo->prepare($sql);
            	    $params = array(':comment' => $editcom, ':id' => $editnum, ':date' => $date);
            	    $stmt->execute($params);
            	}
            }
        }
        
        // 削除処理
        if (isset($_POST["delete"])) {
            if (isset($_POST["deletenum"], $_POST["deletepass"])) {
                $dsn = 'mysql:dbname=*****;host=localhost';
                $user = '*****';
                $password = '*****';
                $pdo = new PDO($dsn, $user, $password,
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
                $deletenum = $_POST["deletenum"];
                $deletepass = $_POST["deletepass"];
                
                $sql = "SELECT * FROM posts WHERE id = $deletenum";
            	$stmt = $pdo->query($sql);
            	$result = $stmt->fetch();
            	
            	if ($result['password'] == $deletepass) {
            	    $sql = "DELETE FROM posts WHERE id = :id";
            	    $stmt = $pdo->prepare($sql);
            	    $params = array(':id' => $deletenum);
            	    $stmt->execute($params);
            	}
            }
        }
        
        // 投稿表示処理
        $dsn = 'mysql:dbname=*****;host=localhost';
        $user = '*****';
        $password = '*****';
        $pdo = new PDO($dsn, $user, $password,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = 'SELECT id, name, comment, date FROM posts ORDER BY date DESC';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
    		//$rowの中にはテーブルのカラム名が入る
    		echo $row['id'].',';
    		echo $row['name'].',';
    		echo $row['comment'].',';
    		echo $row['date']. '<br>';
    	echo "<hr>";
    	}
    ?>
</body>
</html>