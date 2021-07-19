<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-2</title>
</head>
<body>
    <h1 class="midashi_1"> おすすめの映画を教えてください！ </h1>
    <?php
    $data0="";
    $data1="";
    $data2="";
    ?>
    
    
    <?php
    // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password　= 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //<CREATE文>：データベース内にテーブルを作成
    //id ・自動で登録されていうナンバリング。
    //name ・名前を入れる。文字列、半角英数で32文字。
    //comment ・コメントを入れる。文字列、長めの文章も入る。
    $sql = "CREATE TABLE IF NOT EXISTS table4"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "pass1 TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    //入力フォーム
    if(!empty($_POST["name"])&& !empty($_POST["comment"])&& !empty($_POST["password1"])&& empty($_POST["edit2"])){
        $name= $_POST["name"];
        $comment= $_POST["comment"];
        $pass1= $_POST["password1"];
        $date = date("Y年m月d日 H時i分s秒");
        
        //<INSERT文>4-5：データを入力（データレコードの挿入）
        $sql = $pdo -> prepare("INSERT INTO table4 (name, comment, date, pass1) VALUES (:name, :comment, :date, :pass1)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass1', $pass1, PDO::PARAM_STR);
        $sql -> execute();
        
        //<SELECT文>4-6：入力したデータレコードを抽出し、表示する
        //$rowの添字（[ ]内）は、4-2で作成したカラムの名称に併せる必要があります。
        $sql = 'SELECT * FROM table4';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date']."<br>";
            echo "<hr>";
        }
    }
    
    //削除機能
    if(!empty($_POST["delete"]) &&!empty($_POST["password2"])){
        $delete= $_POST["delete"];
        $pass2= $_POST["password2"];
        
        //データ抜き出し※
        $sqll = 'SELECT * FROM table4';
        $stmtt = $pdo->query($sqll);
        $resultss = $stmtt->fetchAll();
        foreach ($resultss as $row){
            //<DELETE文>4-8：入力したデータレコードを削除
            if($row['id']== $delete&&$row['pass1']== $pass2){ //←ここポイント！
                $id=$delete;
                $sql = 'delete from table4 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            if($row['id']== $delete &&$row['pass1']!=$pass2){
                echo "パスワードが違います"."<br>";
            }
        }
        
        //続けて、4-6の SELECTで表示させる機能 も記述し、表示もさせる。
        //※ データベース接続は上記で行っている状態なので、その部分は不要
        $sql = 'SELECT * FROM table4';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date']."<br>";
            echo "<hr>";
        }
    }
    
    //編集機能①フォーム表示
    if(!empty($_POST["edit"])&&!empty($_POST["password3"])){
        $edit= $_POST["edit"];
        $pass3= $_POST["password3"];
        
        
        //データ抜き出し
        $sqll = 'SELECT * FROM table4';
        $stmtt = $pdo->query($sqll);
        $lines = $stmtt->fetchAll();
        
        foreach($lines as $line){
            echo $line['id'].',';
            echo $line['name'].',';
            echo $line['comment'].',';
            echo $line['date']."<br>";
            echo "<hr>";
            if($line['id']== $edit &&$line['pass1']==$pass3){
                $data0=$line['name'];
                $data2=$line['comment'];
                $data1=$line['id'];
            }
            if($line['id']== $edit &&$line['pass1']!=$pass3){
                echo "パスワードが違います"."<br>";
            }
        }
    }
    //編集差し替え
    if(!empty($_POST["name"])&& !empty($_POST["comment"])&& !empty($_POST["password1"])&& !empty($_POST["edit2"])){
        $edit2= $_POST["edit2"];
        $name= $_POST["name"];
        $comment= $_POST["comment"];
        $pass1= $_POST["password1"];
        $date = date("Y年m月d日 H時i分s秒");
        
        $id = $edit2; //変更する投稿番号←☆ここらへん！！
        $sql = 'UPDATE table4 SET name=:name,comment=:comment,date=:date,pass1=:pass1 WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':pass1', $pass1, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        //編集済み表示
        $sql = 'SELECT * FROM table4';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].'<br>';
            echo $row['date']."<br>";
            echo "<hr>";
        }
    }
    
    ?>
    <form action="" method="post">
        <input type="hidden" name=edit2 placeholder="編集番号" value="<?php echo $data1;?>"><br>
        <input type="text" name="name" placeholder="名前" value="<?php echo $data0;?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php echo $data2;?>"><br>
        <input type="text" name="password1" placeholder="パスワード">
        <input type="submit" name="submit"><br>
        <br>
        <input type="text" name="delete" placeholder="削除対象番号"><br>
        <input type="text" name="password2" placeholder="パスワード">
        <input type="submit" name="delete2" value="削除"><br>
        
        <input type="text" name="edit" placeholder="編集対象番号"><br>
        <input type="text" name="password3" placeholder="パスワード">
        <input type="submit" name="edit2" value="編集"><br>
        
    </form>
    
</body>
</html>