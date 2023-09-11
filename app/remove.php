<?php
if(isset($_POST['id'])){
    require '../db_conn.php';

    $id = $_POST['id'];

    if(empty($id)){
        echo 0; // Return 0 for empty ID (error)
    }else {
        $stmt = $conn->prepare("DELETE FROM todos WHERE id=?");
        $res = $stmt->execute([$id]);

        if($res){
            echo 1; // Return 1 for success
        }else {
            echo 0; // Return 0 for error
        }
        $conn = null;
        exit();
    }
}else {
    echo 0; // Return 0 for error if 'id' is not set in POST data
}
?>
