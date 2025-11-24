<?php
    include 'header.php';
?>
<h1>Barang</h1>
<div class="container container-fluid">
    <form method="POST" action="store.php">
        <div class="row mb-3">
            <label for="nama" class="col-lg-3 form-label">Nama</label>
            <div class="col-lg-9">
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="jumlah" class="col-lg-3 form-label">Jumlah</label>
            <div class="col-lg-9">
                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
            </div>
        </div>
        <div class="row mb-3">
            <label for="harga" class="col-lg-3 form-label">Harga</label>
            <div class="col-lg-9">
                <input type="text" class="form-control" id="harga" name="harga" required>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<?php
    include 'footer.php';
?>