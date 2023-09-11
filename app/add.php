<?php
if(isset($_POST['title']) && isset($_POST['description']) && isset($_POST['due_date']) && isset($_POST['status'])){
    require '../db_conn.php';

    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    if(empty($title)){
        header("Location: ../index.php?mess=error");
    }else {
        $stmt = $conn->prepare("INSERT INTO todos(title, description, due_date, status) VALUES(?, ?, ?, ?)");
        $res = $stmt->execute([$title, $description, $due_date, $status]);

        if($res){
            header("Location: ../index.php?mess=success"); 
        }else {
            header("Location: ../index.php");
        }
        $conn = null;
        exit();
    }
}else {
    header("Location: ../index.php?mess=error");
}
?>
