<? 
header("Content-type:application/json");
require_once('\initial.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{
    //echo "Connected successfully";
}   
    $_POST_ = $_POST;
    if(file_get_contents('php://input')){
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    if(isset($_POST['action'])){
        $action = $_POST['action'];

        if($action == "SearchWord"){
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

            $sql = "SELECT * FROM `words` ";
            
            $result = $conn->query($sql);

            if($result){

                $AllWords = array();
                while($row = $result->fetch_assoc()){
                    array_push($AllWords,$row);
                }
                echo json_encode($AllWords);
                
                mysqli_free_result($result);
            }

        }

        if($action == "addGroup"){

            if(isset($_POST['user_id'])){
                $user_id = $_POST['user_id'];
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
                }else{
                    echo '{"msg":"Save Empty Group"}';
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
                    mysqli_free_result($result);
                }else{
                    echo '{"msg":"Save Empty Group"}';
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

        }


    }else{
        if(isset($_POST_['action'])){
            $action = $_POST_['action'];
            if($action == "AddingWord"){

                if(isset($_POST_['kanji'])){
                    $kanji = $_POST_['kanji'];
                    echo '<br>'.$kanji;
                }else{
                    die("Variable not set");
                }
            
                if(isset($_POST_['hiragana'])){
                    $hiragana = $_POST_['hiragana'];
                    echo '<br>'.$hiragana;
                }else{
                    die("Variable not set");
                }
            
                if(isset($_POST_['romanji'])){
                    $romanji = $_POST_['romanji'];
                    echo '<br>'.$romanji;
                }else{
                    die("Variable not set");
                }

                if(isset($_POST_['meaning'])){
                    $meaning = $_POST_['meaning'];
                    echo '<br>'.$meaning;
                }else{
                    die("Variable not set");
                }

                $sql = "INSERT INTO Words(`KANJI`,`HIRAGANA`,`ROMANJI`,`MEANING`,`ADD_DATE`,`UPDATE_DATE`) 
                VALUES ('{$kanji}','{$hiragana}','{$romanji}','{$meaning}',NOW(),NOW())";

                if ($conn->query($sql) == TRUE) {
                    //echo "<br>\"{$kanji}\" was added on ". date("Y/m/d");
                    $_SESSION['message'] = "{$kanji} was added on ". date("Y/m/d");
                    $_SESSION['add_result'] = "complete"; 
                } else {
                    $_SESSION['message'] = "{$kanji} wasn't added on ". date("Y/m/d");
                    $_SESSION['add_result'] = "incomplete";
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            
                echo 'connection closed';

                header("Location: /JapanReview/page-word-action.php");

            }
        }
        
    } 

    $conn->close();
?>