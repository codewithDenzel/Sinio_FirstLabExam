<?php

$conn = mysqli_connect("localhost", "root", "", "Sinio_FirstLabExam");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


class Student {
    public $id;
    public $name;

    public function __construct($id, $name) {
        $this->id = $id;
        
        $this->name = ucwords(strtolower($name)); 
    }
}


$mode = 'list';
$edit_data = null;

//CREATE
if (isset($_POST['save'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = strtoupper($_POST['course']); 

    
    $sql = "REPLACE INTO students (id, name, email, course) VALUES ('$id', '$name', '$email', '$course')";
    mysqli_query($conn, $sql);
    header("Location: index.php");
}

// DELETE 
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM students WHERE id = '$id'");
    header("Location: index.php");
}

// EDIT
if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $mode = 'edit';
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id = '$id'");
    $edit_data = mysqli_fetch_assoc($result);
}

// ADD 
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    $mode = 'add';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <?php if ($mode == 'list') { ?>
        <h1>Student Records</h1>
        <a href="?action=add" class="btn-main">Add Student +</a>

        <?php
        $res = mysqli_query($conn, "SELECT * FROM students");
        while ($row = mysqli_fetch_assoc($res)) { 
        ?>
            <div class="card">
                <div class="dots">... ▾
                    <div class="dropdown">
                        <a href="?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="?delete=<?php echo $row['id']; ?>" style="color:red">Delete</a>
                    </div>
                </div>
                <strong><?php echo $row['name']; ?></strong><br>
                <small><?php echo $row['email']; ?></small><br>
                <small><?php echo $row['id']; ?></small><br>
                <small><?php echo $row['course']; ?></small>
            </div>
        <?php } ?>

    <?php } else { ?>
        <h1><?php echo ($mode == 'edit') ? 'Edit Student' : 'Create Student'; ?></h1>
        <form method="POST">
            <label>ID Number</label>
            <input type="text" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>" required>
            
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $edit_data['name'] ?? ''; ?>" required>
            
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $edit_data['email'] ?? ''; ?>" required>
            
            <label>Course</label>
            <input type="text" name="course" value="<?php echo $edit_data['course'] ?? ''; ?>" required>
            
            <button type="submit" name="save" class="btn-main">Save Record</button>
            <a href="index.php">Cancel</a>
        </form>
    <?php } ?>
</div>

</body>
</html>