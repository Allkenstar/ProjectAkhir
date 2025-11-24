<?php
    include 'connection.php';
    if(isset($_POST['submit'])){
        $nama=$_POST['nama'];
        $harga=$_POST['harga'];
        $jumlah=$_POST['jumlah'];

        //----insert data----
        $data=$conn->prepare("insert into barang(nama,harga,jumlah) values(?,?,?)");
        $data->execute([$nama,$harga,$jumlah]);

        header("Location: index.php");
        exit;
    }

?>