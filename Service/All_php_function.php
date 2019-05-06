<?php 

require_once('initial.php');

function recordLogs($conn,$user_id,$action){
        
    $sql = "INSERT user_action_logs(user_id,action,timestamp) values({$user_id},'{$action}',NOW())";
    $conn->query($sql);

}

?>