<?php
    include '../header.php';
    include '../class/user.php';
    
    //----select data----
    $datas=User::all($conn);
?>
<h1>Users</h1>

<a href="create.php" class="btn btn-primary mb-3">Tambah User</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($datas as $row):?>
            <tr>
                <td><?= $row->id ?></td>
                <td><?= $row->username ?></td>
                <td>
                    <form method="POST" action="delete.php" onsubmit="return confirm('Yakin ingin menghapus user <?= $row->username ?>?')">
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <button type="submit" name="submit" value="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
    
<?php
    include '../footer.php';
?>