<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <!--- データベース接続設定　いじらない--->
        <?php
            $dsn = 'mysql:dbname="データベース名";host="ホスト名"';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            // テーブル作成用　
            $sql = "CREATE TABLE IF NOT EXISTS m51table"." ("."postnumber INT AUTO_INCREMENT PRIMARY KEY,"."name char(32),"."comment TEXT,"."date char(32),"."password TEXT".");";
            $stmt = $pdo->query($sql);
            
            $editornumber="";
            $namevalue="";
            $commentvalue="";
            if(!empty($_POST["number2"]) && !empty($_POST["editorpass"])){
                $number2 = $_POST["number2"];
                $editorpass = $_POST["editorpass"];
                $sql = 'SELECT * FROM m51table';
                $stmt = $pdo->query($sql);
                $result = $stmt->fetchAll();
                foreach($result as $row){
                    if($row['password'] == $editorpass && $row['postnumber'] == $number2){
                        $editornumber = $row['postnumber'];
                        $namevalue = $row['name'];
                        $commentvalue = $row['comment'];
                        break;
                    }
                }
            }
        ?>
        <form action="" method="post">
            <input type="text" name="name" size="8" placeholder="名前" value="<?php echo $namevalue ?>">
            <input type="text" name="comment" placeholder="コメント" value="<?php echo $commentvalue ?>">
            <input type="password" name="inputpass" size="10" placeholder="パスワード">
            <input type="hidden" name="number3" value="<?php echo $editornumber ?>">
            <input type="submit" name="submit">
            <input type="number" name="number" min="1" step="1" placeholder="削除番号を入力">
            <input type="password" name="deletepass" size="10" placeholder="パスワード">
            <input type="submit" name="submit2" value="削除">
            <input type="number" name="number2" min="1" step="1" placeholder="編集対象番号を入力">
            <input type="password" name="editorpass" size="10" placeholder="パスワード">
            <input type="submit" name="submit3" value="編集">
        </form>
        <?php
            if(!empty($_POST["name"]) && !empty($_POST["comment"])){
                $name = $_POST["name"];
                $comment = $_POST["comment"];
                if(!empty($_POST["inputpass"])){
                    $password = $_POST["inputpass"];
                }else{
                    $password = "";
                }
                if(!empty($_POST["number3"])){
                    $editornumber = $_POST["number3"];
                }else{
                    $editornumber = "";
                }
                
                if($editornumber == ""){
                    $date = date("Y/m/d h:i:s");
                    $sql = "INSERT INTO m51table (name, comment, date, password) VALUES (:name, :comment, :date, :password)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->execute();
                }else{
                    $sql = 'SELECT * FROM m51table';
                    $stmt = $pdo->query($sql);
                    $results = $stmt->fetchAll();
                    foreach ($results as $row){
                        if($row['postnumber'] == $editornumber){
                            $date = date("Y/m/d h:i:s");
                            $sql = "UPDATE m51table SET name=:name, comment=:comment, date=:date, password=:password WHERE postnumber=:editornumber";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                            $stmt->bindParam(':editornumber', $editornumber, PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    }
                    $editornumber = "";
                }
            }
            if(!empty($_POST["number"]) && !empty($_POST["deletepass"])){
                $number = $_POST["number"];
                $deletepass = $_POST["deletepass"];
                $sql = 'SELECT * FROM m51table';
                $stmt = $pdo->query($sql);
                $result = $stmt->fetchAll();
                foreach($result as $row){
                    if($row['password'] == $deletepass){
                        $sql = 'delete from m51table where postnumber=:number';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':number', $number, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            }
            
            $sql = 'SELECT * FROM m51table';
            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll();
            foreach($result as $row){
                echo $row['postnumber']." ".$row['name']." ".$row['comment']." ".$row['date']."<br>";
            }
        ?>
    </body>
</html>