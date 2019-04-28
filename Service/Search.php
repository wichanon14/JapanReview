<?php 

require_once './initial.php';

if(isset($_POST['keyword'])){

    $keyword = $_POST['keyword'];
    $sql = "SELECT * 
            FROM japanese_review.words 
            where KANJI like '%{$keyword}%' or 
            HIRAGANA like '%{$keyword}%' or
            ROMANJI like '%{$keyword}%'";

    $result = $conn->query($sql);

    if($result){
        $results = '';
        while($row = $result->fetch_assoc()){
            //echo json_encode($row);
            $results .= '<tr>
            <td>'.$row['KANJI'].'</td>
            <td>'.$row['HIRAGANA'].'</td>
            <td>'.$row['ROMANJI'].'</td>
            <td>'.$row['MEANING'].'</td>
            </tr>';
        }
        //echo json_encode($results);
        echo $results;
    }
    
}

$conn->close();

?>