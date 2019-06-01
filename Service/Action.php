<?php 
header("Content-type:application/json");
require_once('initial.php');
require_once('All_php_function.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    //echo "Connected successfully";
}   
    $_POST = $_POST;
    if(file_get_contents('php://input')){
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    if(isset($_POST['action'])){
        $action = $_POST['action'];

        if($action == "SearchWord"){
            
            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }
            
            if(isset($_POST['keyword'])){

                $keyword = $_POST['keyword'];
                $sql = "SELECT * 
                        FROM japanese_review.words 
                        where KANJI like '%{$keyword}%' or 
                        HIRAGANA like '%{$keyword}%' or
                        ROMANJI like '%{$keyword}%'";
            
                $result = $conn->query($sql);
            
                if($result){
                    $results = array();
                    while($row = $result->fetch_assoc()){
                        array_push($results,$row);
                    }
                    echo json_encode($results);
                    
                    recordLogs($conn,$user_id,'Search Word >> '.$keyword);

                    mysqli_free_result($result);
                }
                
            }
        }

        if($action == "UpdateWord"){
            
            if(isset($_POST['kanji'])){
                $kanji = $_POST['kanji'];
            }else{
                die("Variable not set");
            }
        
            if(isset($_POST['hiragana'])){
                $hiragana = $_POST['hiragana'];
            }else{
                die("Variable not set");
            }
        
            if(isset($_POST['romanji'])){
                $romanji = $_POST['romanji'];
            }else{
                die("Variable not set");
            }

            if(isset($_POST['meaning'])){
                $meaning = $_POST['meaning'];
            }else{
                die("Variable not set");
            }

            if(isset($_POST['ID'])){
                $ID = $_POST['ID'];
            }else{
                die("Variable not set");
            }

            $sql = "UPDATE `words` set `KANJI`= '{$kanji}' ,
            `HIRAGANA`= '{$hiragana}' ,
            `ROMANJI` = '{$romanji}',
            `MEANING`='{$meaning}' WHERE ID='{$ID}'";

            if ($conn->query($sql) == TRUE) {
                header("Content-type:text/html");
                echo "<p class=\"text-success\">".$kanji." edited complete!</p>";
            }else{
                $_SESSION['edit_result']="fail";
            }

        }

        if($action == "DeleteWord"){

            if(isset($_POST['ID'])){
                $ID = $_POST['ID'];
            }else{
                die("Variable not set");
            }

            if(isset($_POST['kanji'])){
                $kanji = $_POST['kanji'];
            }else{
                die("Variable not set");
            }

            $sql = "DELETE FROM `words` WHERE ID={$ID} ";
            if ($conn->query($sql) == TRUE) {
                header("Content-type:text/html");
                echo "<p class=\"text-success\">".$kanji." was deleted</p>";
                
            }

        }

        if($action == "getAllWord"){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            $sql = "SELECT * FROM `words` ";
            
            $result = $conn->query($sql);

            if($result){

                $AllWords = array();
                while($row = $result->fetch_assoc()){
                    array_push($AllWords,$row);
                }
                echo json_encode($AllWords);
                recordLogs($conn,$user_id,'get All word');
                mysqli_free_result($result);
            }

        }

        if($action == "addGroup"){

            if(isset($_POST['user_id'])){
                $user_id = $_SESSION['user_id'];
            }else{
                die("Variable not set");
            }

            if(isset($_POST['group'])){
                $group = $_POST['group'];
            }else{
                die("Variable not set");
            }

            if(isset($_POST['group_id'])){
                $group_id = $_POST['group_id'];
            }
            $groups = (object)$group;

            $datas = $groups->data;
            //example format $datas[0]['ID'];

            if($group_id){

                $sql = "UPDATE `group_detail` SET user_id = {$user_id},group_name= '{$groups->groupName}'
                WHERE  ID={$group_id}";
                
                $conn->query($sql);

                $sql = "DELETE FROM `group_word_relation` WHERE group_id = {$group_id}";
                $conn->query($sql);

                $value = "";
                foreach($datas as $data){
                    $value .= "({$group_id},{$data['data_id']}),";
                }

                $sql = "INSERT INTO `group_word_relation`(group_id,word_id) 
                values".substr($value,0,-1);
                if($conn->query($sql)){
                    echo '{"msg":"Save Success"}';
                    recordLogs($conn,$user_id,'update Group >> '.$group_id);
                }else{
                    echo '{"msg":"Save Empty Group"}';
                    recordLogs($conn,$user_id,'update Group >> '.$group_id);
                }

            }else{

                $sql = "INSERT INTO `group_detail`(user_id,group_name) values({$user_id},'{$groups->groupName}')";
                
                $conn->query($sql);

                $sql = "SELECT ID FROM `group_detail` WHERE user_id = {$user_id} AND group_name = '{$groups->groupName}'
                ORDER BY ID desc LIMIT 1";

                $result = $conn->query($sql);
                $ID = $result->fetch_assoc()['ID'];
                
                $value = "";
                foreach($datas as $data){
                    $value .= "({$ID},{$data['data_id']}),";
                }

                $sql = "INSERT INTO `group_word_relation`(group_id,word_id) 
                values".substr($value,0,-1);

                if($result){
                    echo '{"msg":"Save Success"}';
                    recordLogs($conn,$user_id,'add Group >> '.$group_id);
                    mysqli_free_result($result);
                }else{
                    echo '{"msg":"Save Empty Group "}';
                    recordLogs($conn,$user_id,'add Empty Group >> '.$group_id);
                }
                
                $conn->query($sql);       
            }

        }

        if($action == "getGroupList"){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            if(isset($_POST['keyword'])){
                $keyword = $_POST['keyword'];
            }else{
                $keyword = "";
            }

            $sql = "SELECT * FROM `group_detail` WHERE user_id = {$user_id} 
            AND LOWER(group_name) like CONCAT('%',LOWER('{$keyword}'),'%') 
            ORDER BY LOWER(group_name)";
            
            $result = $conn->query($sql);

            if($result){

                $Groups = array();
                while($row = $result->fetch_assoc()){
                    array_push($Groups,$row);
                }
                echo json_encode($Groups);
                recordLogs($conn,$user_id,'get GroupList');
                mysqli_free_result($result);
            }

        }

        if($action == "getGroup"){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            if(isset($_POST['group_id'])){
                $group_id = $_POST['group_id'];
            }

            $sql = "SELECT w.KANJI as word,w.KANJI,w.HIRAGANA,w.ROMANJI,w.MEANING,gwr.word_id as data_id FROM `group_detail` gd
            INNER JOIN `group_word_relation` gwr on
            gd.ID = gwr.group_id
            INNER JOIN `words` w on 
            w.ID = gwr.word_id
            WHERE gd.user_id = {$user_id} AND gwr.group_id = {$group_id} ";
            
            $result = $conn->query($sql);

            if($result){

                $Groups = array();
                while($row = $result->fetch_assoc()){
                    array_push($Groups,$row);
                }
                echo json_encode($Groups);
                recordLogs($conn,$user_id,'get Group >> '.$group_id);
                mysqli_free_result($result);
            }
            

        }

        if($action == "deleteGroup"){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            if(isset($_POST['group_id'])){
                $group_id = $_POST['group_id'];
            }

            $sql = "DELETE FROM `group_word_relation` WHERE group_id = {$group_id} ";
            
            $conn->query($sql);

            $sql = "DELETE FROM `group_detail` WHERE user_id = {$user_id} AND ID = {$group_id} ";
            
            $conn->query($sql);

            echo '{"msg":"Delete Success"}';
            recordLogs($conn,$user_id,'delete Group >> '.$group_id);

        }

        if($action == "AddingWord"){

            if(isset($_POST['kanji'])){
                $kanji = $_POST['kanji'];
                
            }else{
                $kanji = '';
            }
        
            if(isset($_POST['hiragana'])){
                $hiragana = $_POST['hiragana'];
                
            }else{
                $hiragana = '';
            }
        
            if(isset($_POST['romanji'])){
                $romanji = $_POST['romanji'];
                
            }else{
                $romanji = '';
            }

            if(isset($_POST['meaning'])){
                $meaning = $_POST['meaning'];
                
            }else{
                $meaning = '';
            }

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];   
            }

            $sql = "SELECT ID FROM words WHERE KANJI = '{$kanji}'";
            $result = $conn->query($sql);
            
            $row = $result->fetch_assoc();

            if(!$row['ID']){
                
                $sql = "INSERT INTO words(`KANJI`,`HIRAGANA`,`ROMANJI`,`MEANING`,`ADD_DATE`) 
                    VALUES ('{$kanji}','{$hiragana}','{$romanji}','{$meaning}',NOW())";

                if ($conn->query($sql) == TRUE) {
                    
                    $sql = "SELECT ID FROM words WHERE KANJI = '{$kanji}'";
                    $result = $conn->query($sql);
                    
                    $row = $result->fetch_assoc();

                    $_SESSION['message'] = "{$kanji} was added on ". date("Y/m/d");
                    $_SESSION['add_result'] = "complete"; 
                    echo '{ "msg":"add successfull" ,"ID":'.$row['ID'].' }';
                    recordLogs($conn,$user_id,'Add Word >> '.$kanji);
                } else {
                    $_SESSION['message'] = "{$kanji} wasn't added on ". date("Y/m/d");
                    $_SESSION['add_result'] = "incomplete";
                    
                    echo '{ "msg":"add fail" }';
                    recordLogs($conn,$user_id,"{$sql}");
                }

            }else {
                $_SESSION['message'] = "{$kanji} wasn't added on ". date("Y/m/d");
                $_SESSION['add_result'] = "incomplete";
                
                echo '{ "msg":"add fail" , "ID": '.$row['ID'].'}';
                recordLogs($conn,$user_id,'Add Word Fail');
            }

        }

        if($action == "getMultiGroup"){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            $listGroup='';
            if(isset($_POST['group_id'])){
                $group_id = $_POST['group_id'];
                for($i = 0;$i<sizeof($group_id);$i++){
                    $listGroup .= $group_id[$i].',';
                }
                if($listGroup){
                    $listGroup = substr($listGroup,0,-1);
                }
            }

            $sql = "SELECT DISTINCT(gwr.word_id) ,w.KANJI as word,w.KANJI,w.HIRAGANA,w.ROMANJI,w.MEANING,gwr.word_id as data_id FROM `group_detail` gd
            INNER JOIN `group_word_relation` gwr on
            gd.ID = gwr.group_id
            INNER JOIN `words` w on 
            w.ID = gwr.word_id
            WHERE gd.user_id = {$user_id} AND gwr.group_id IN ({$listGroup}) ";
            
            $result = $conn->query($sql);
            
            if($result){

                $Groups = array();
                while($row = $result->fetch_assoc()){
                    array_push($Groups,$row);
                }
                echo json_encode($Groups);
                recordLogs($conn,$user_id,'get MultiGroup >> '.$listGroup);
                mysqli_free_result($result);
            }
            

        }
    
        if($action == 'deleteAllGroup'){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            $sql = "DELETE FROM group_word_relation where group_id in 
            ( select ID FROM group_detail where user_id = {$user_id} )";

            if($conn->query($sql)){
                $sql = "DELETE FROM group_detail where user_id = {$user_id}";                

                if($conn->query($sql)){
                    echo '{ "msg":"Success! Delete all"}';
                    recordLogs($conn,$user_id,'delete All Group ');
                }else{
                    echo '{ "msg":"Fail Delete all"}';
                    recordLogs($conn,$user_id,'Fail delete All Group ');
                }

            }

        }
        
        if($action == 'deleteAllWordInGroup'){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
            }

            if(isset($_POST['group_id'])){
                $group_id = $_POST['group_id'];
            }

            $sql = "DELETE FROM group_word_relation where group_id = {$group_id} and group_id in (
                SELECT ID FROM group_detail WHERE user_id = {$user_id}
            )";

            if($conn->query($sql)){
                echo '{ "msg":"Success! Delete all"}';
                recordLogs($conn,$user_id,'delete all word in group >> '.$group_id);
            }else{
                echo '{ "msg":"Fail Delete all"}';
                recordLogs($conn,$user_id,'Fail delete all word in group >> '.$group_id);
            }

        }

        if($action == 'membership-signup'){

            if(isset($_POST['username'])){
                $username = $_POST['username'];
            }

            if(isset($_POST['password'])){
                $password = $_POST['password'];
            }

            if(isset($_POST['confirm_password'])){
                $confirm_password = $_POST['confirm_password'];
            }

            if(isset($_POST['email'])){
                $email = $_POST['email'];
            }

            $sql = "SELECT ID FROM user_detail ORDER BY ID DESC LIMIT 1";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            $month = (getdate()['mon']<10)?str_pad(getdate()['mon'], 2, "0", STR_PAD_LEFT):getdate()['mon'];
            $date = (getdate()['mday']<10)?str_pad(getdate()['mday'], 2, "0", STR_PAD_LEFT):getdate()['mday'];
            $user_id = getdate()['year'].$month.$date.($row['ID']+1);

            $sql = "SELECT count(*) as member FROM user_detail WHERE username = '{$username}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $isUsernameExist = $row['member'];

            $sql = "SELECT count(*) as member FROM user_detail WHERE email = '{$email}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $isEmailExist = $row['member'];

            if($password == $confirm_password && $isEmailExist<1&&$isUsernameExist<1){
                $sql = "INSERT INTO user_detail(user_id,username,password,email) VALUES( 
                    {$user_id},'{$username}','{$password}','{$email}'
                )";

                if($conn->query($sql)){
                    echo '{"msg":"Register Successfull"}';
                    recordLogs($conn,$user_id,' Registered Successfull');
                }else{
                    echo '{"msg":"Register Fail"}';
                    recordLogs($conn,$user_id,' Registered Fail');
                }
            }else{
                if($isUsernameExist>0){
                    echo '{"msg":"This username already exist!"}';
                    recordLogs($conn,$user_id,' Registered This username already exist!');
                }else if($password != $confirm_password){
                    echo '{"msg":"Your password is not match!"}';
                    recordLogs($conn,$user_id,' Registered This password is not match!');
                }else if($isEmailExist>0){
                    echo '{"msg":"This email already exist!"}';
                    recordLogs($conn,$user_id,' Registered This email already exist!');
                }
                
                
            }
            mysqli_free_result($result);

        }

        if($action == 'check_email_exist'){

            if(isset($_POST['email'])){
                $email = $_POST['email'];
            }

            $sql = "SELECT count(*) as n FROM user_detail WHERE email = '{$email}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if($row['n']<1){
                echo '{"msg":false}';
            }else{
                echo '{"msg":true}';
            }
            recordLogs($conn,$_SESSION['user-id'],'Check email Exist >> '.$email);
            mysqli_free_result($result);

        }

        if($action == 'check_username_exist'){

            if(isset($_POST['username'])){
                $username = $_POST['username'];
            }

            $sql = "SELECT count(*) as n FROM user_detail WHERE username = '{$username}'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();

            if($row['n']<1){
                echo '{"msg":false}';
            }else{
                echo '{"msg":true}';
            }
            recordLogs($conn,$_SESSION['user-id'],'Check email Exist >> '.$email);
            mysqli_free_result($result);

        }

        if($action == 'sign_in'){

            if(isset($_POST['username'])){
                $username = $_POST['username'];
            }

            if(isset($_POST['password'])){
                $password = $_POST['password'];
            }

            $sql = "SELECT user_id,username FROM user_detail WHERE username = '{$username}' AND password = '{$password}'";
            $result = $conn->query($sql);

            if($result){                
                $row = $result->fetch_assoc();
                $_SESSION['user-id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                echo '{"user-id":"'.$_SESSION['user-id'].'"}';
                recordLogs($conn,$row['user_id'],'Sign In Successful');

            }else{
                $_SESSION['user-id'] = '';
                recordLogs($conn,$row['user_id'],'Sign In Fail');
            }

        }

        if($action == 'get_point'){
            
            if($_SESSION['user-id']){
                $user_id = $_SESSION['user-id'];
            }

            if(isset($_POST['all_word'])){
                $n = $_POST['all_word'];
            }else{
                die();
            }

            if(isset($_POST['correct_word'])){
                $c = $_POST['correct_word'];
            }else{
                die();
            }

            $score = ($n / 10)*($c/$n)*100;

            $sql = "SELECT score FROM user_detail WHERE user_id = {$user_id}";
            $result = $conn->query($sql);

            if($result && $user_id){                
                $row = $result->fetch_assoc();
                $new_score = $row['score']+$score;

                $sql = "UPDATE user_detail SET score = {$new_score} WHERE user_id = {$user_id}";
                $result = $conn->query($sql);

                echo "{ \"msg\": \"".$new_score."\" }";
            }

            

            
        }

    }else{
        
    } 

    $conn->close();
?>