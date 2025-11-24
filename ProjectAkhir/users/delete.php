<?php
    include '../connection.php';
    include '../class/user.php';
    if(isset($_POST['submit'])){
        $id=$_POST['id'];

        $user= User::findById($conn, $id);
        if($user){
            $user->delete($conn);
        }
    
        header("Location: index.php");
        exit;
    }

?>