<?php
if(isset($_POST['id'])){
    require '../db_conn.php';

    $id = $_POST['id'];

    if(empty($id)){
       echo 'error';
    }else {
        // Retrieve the current status of the task
        $todos = $conn->prepare("SELECT id, checked FROM todos WHERE id=?");
        $todos->execute([$id]);

        $todo = $todos->fetch();
        $uId = $todo['id'];
        $checked = $todo['checked'];

        // Toggle the status (1 for checked, 0 for unchecked)
        $uChecked = $checked ? 0 : 1;

        // Update the status in the database
        $stmt = $conn->prepare("UPDATE todos SET checked=? WHERE id=?");
        $res = $stmt->execute([$uChecked, $uId]);

        if($res){
            echo $uChecked; // Return the updated status
        }else {
            echo "error";
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}
?>
