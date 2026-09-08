<?php
require_once "../Mentra/backend/auth_check.php";
require_once "../Mentra/backend/db.php";

if($_SERVER["REQUEST_METHOD"]!=="POST"){
    header("Location: submit_project.php");
    exit();
}
$student_id = $_SESSION['user_id'];
$title = trim($_POST['title']);
$category = trim($_POST['category']);
$description = trim($_POST['description']);

if(empty($title) || empty($category) || empty($description)){
    die("Please fill all required fields");
}

$sql = "INSERT INTO projects(student_id, title, description,category) VALUES (?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

if(!$stmt){
    die("Databse error: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt,"isss,$student_id,$title,$description,$category");
if(mysqli_stmt_execute($stmt)){
    $project_id = mysqli_insert_id($conn);

     $version_sql = "INSERT INTO project_versions (project_id, version_number, description, status) VALUES (?, 1, ?, 'Submitted')";
     $version_stmt = mysqli_prepare($conn,$version_sql);
     mysqli_stmt_bind_param($version_stmt,"is",$project_id,$description);

     mysqli_stmt_execute($version_stmt);
     mysqli_stmt_close($version_stmt);

     header("Location: view_project.php?id=" . $project_id);
     exit();

}else{
    die("Project submission failed: " . mysqli_stmt_error($stmt));
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>