<?php
    include '../connection.php';
    include '../class/user.php';
    if(isset($_POST['submit'])){
        $username=$_POST['username'];
        $password=$_POST['password'];

        $user=new User(null,$username, $password);
        $user->save($conn);

        header("Location: index.php");
        exit;
    }

?>