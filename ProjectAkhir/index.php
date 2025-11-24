<?php
    include 'header.php';
    
    //----select data----
    $datas=$conn->query("select * from barang");
?>
<h1>Barang</h1>

<a href="create.php" class="btn btn-primary mb-3">Tambah Barang</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($datas->fetchAll() as $row):?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['nama'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td><?= $row['harga'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    
<?php
    include 'footer.php';
?>