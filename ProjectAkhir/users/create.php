<?php
    include '../header.php';
?>
<h1>Users</h1>
<div class="container container-fluid">
    <form method="POST" action="store.php">
        <div class="row mb-3">
            <label for="username" class="col-lg-3 form-label">Username</label>
            <div class="col-lg-9">
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="password" class="col-lg-3 form-label">Password</label>
            <div class="col-lg-9">
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<?php
    include '../footer.php';
?>