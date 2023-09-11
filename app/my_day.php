<?php
if (isset($_POST['id']) && isset($_POST['isChecked'])) {
    require '../db_conn.php';

    $id = $_POST['id'];
    $isChecked = $_POST['isChecked'];

    if (empty($id)) {
        echo 'error';
    } else {
        $stmt = $conn->prepare("UPDATE todos SET my_day = ? WHERE id = ?");
        $res = $stmt->execute([$isChecked ? 1 : 0, $id]);

        if ($res) {
            echo $isChecked ? '1' : '0';
        } else {
            echo 'error';
        }
        $conn = null;
        exit();
    }
} else {
    header("Location: ../index.php?mess=error");
}
?>
