<?php
require 'db_conn.php';

$errors = [];
$search = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM todos WHERE title LIKE '%$search%' ";
    $todos = $conn->query($query);
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    if (empty($due_date)) {
        $errors[] = "Due Date is required.";
    }
    if (empty($status)) {
        $errors[] = "Status is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO todos (title, description, due_date, status, priority) VALUES (?, ?, ?, ?, ?)");
        $res = $stmt->execute([$title, $description, $due_date, $status, $priority]);

        if ($res) {
            header("Location: index.php?mess=success");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
    $todos = $conn->query("SELECT * FROM todos ");
} else {
    $todos = $conn->query("SELECT * FROM todos ");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="main-section">
        <h1>ToDo List</h1>
        <div class="add-section">
            <div class="form-container">
                <form action="index.php" method="POST" autocomplete="off">
                    <?php if (!empty($errors)) { ?>
                        <div class="error-message">
                            <?php foreach ($errors as $error) { ?>
                                <p><?php echo $error; ?></p>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <label for="title">Title*</label>
                    <input type="text" name="title" id="title" placeholder="Name the Task" />

                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Description"></textarea>

                    <label for="due_date">Due Date*</label>
                    <input type="date" name="due_date" id="due_date" placeholder="Due Date" />

                    <label for="status">Status*</label>
                    <select name="status" id="status">
                        <option value="" disabled selected>Select</option>
                        <option value="Not Started">Not Started</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>

                    <label for="priority">Priority*</label>
                    <select name="priority" id="priority">
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
                    </select>

                    <button type="submit">Add &nbsp; <span>&#43;</span></button>
                </form>
            </div>
        </div>

        <div class="search-section">
            <form action="index.php" method="POST">
                <label for="search">Search</label><br>
                <input type="text" name="search" id="search" placeholder="Search tasks" />
                <button type="submit">Search</button>
            </form>
        </div>

        <div class="show-todo-section">
            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item priority-<?php echo strtolower($todo['priority']); ?>">
                    <input type="checkbox" class="my-day-checkbox" data-todo-id="<?php echo $todo['id']; ?>" <?php if ($todo['my_day'] == 1) echo 'checked'; ?> />
                    <span id="<?php echo $todo['id']; ?>" class="remove-to-do">x</span>
                    <h2><?php echo $todo['title']; ?></h2>
                    <p><?php echo $todo['description']; ?></p>
                    <p>Due Date: <?php echo $todo['due_date']; ?></p>
                    <p>Status: <?php echo $todo['status']; ?></p>
                    <small>created: <?php echo $todo['date_time']; ?></small>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="js/jquery.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');

                $.post("app/remove.php",
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".my-day-checkbox").click(function () {
                const id = $(this).attr('data-todo-id');
                const isChecked = $(this).is(':checked');

                $.post('app/my_day.php',
                    {
                        id: id,
                        isChecked: isChecked
                    },
                    (data) => {
                        if (isChecked) {
                            $(this).closest('.todo-item').addClass('strikethrough');
                        } else {
                            $(this).closest('.todo-item').removeClass('strikethrough');
                        }
                    }
                );
            });
        });
    </script>
</body>
</html>
