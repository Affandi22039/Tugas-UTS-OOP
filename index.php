<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Image</title>
</head>
<body>
    <h1>CRUD Menggunakan PHP dan MySQL dengan menerapkan Object Oriented Programming</h1>
    <?php
    include_once 'koneksi.php';
    $database = new Database();
    $db = $database->conn;
    include_once 'Item.php';

    $item = new Item($db);
    
    // Proses Update
   if (isset($_GET['update_id'])) {
    $item->id = $_GET['update_id'];

    // Mengambil data item berdasarkan ID
    $stmt = $item->readOne();
    $row = $stmt->get_result()->fetch_assoc();

    // Menangani formulir pembaruan yang dikirimkan
    if ($_POST) {
        $item->name = $_POST['name'];

        // Jika pengguna mengunggah file baru
        if ($_FILES['image']['size'] > 0) {
            $item->image = $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $_FILES['image']['name']);
        } else {
            // Jika tidak ada file baru yang diunggah, gunakan foto yang sudah ada sebelumnya
            $item->image = $row['image'];
        }

        if ($item->update()) {
            echo "<div>Item telah berhasil diperbaharui</div>";
        } else {
            echo "<div>Proses pembaharuan gagal</div>";
        }
    }

    // Memunculkan form update
    echo "<h2>Perbarui Item</h2>";
    echo "<form action='?update_id={$item->id}' method='post' enctype='multipart/form-data'>";
    echo "<label>Name:</label>";
    echo "<input type='text' name='name' value='{$row['name']}' required>";
    echo "<label>Image:</label>";
    echo "<input type='file' name='image' accept='image/*'>";
    echo "<button type='submit'>Update</button>";
    echo "</form>";
}
    // Proses Delete
    elseif (isset($_GET['delete_id'])) {
        $item->id = $_GET['delete_id'];
        if ($item->delete()) {
            echo "<div>Item telah berhasil dihapus</div>";
        } else {
            echo "<div>Item gagal dihapus</div>";
        }
    }
    // Proses Create
    elseif ($_POST) {
        $item->name = $_POST['name'];
        $item->image = $_FILES['image']['name'];

        if ($item->create()) {
            echo "<div>Item berhasil dibuat</div>";
            // Upload file gambar
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $_FILES['image']['name']);
        } else {
            echo "<div>Item gagal dibuat</div>";
        }
    }
    ?>

    <h2>Buat Item</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit">Create</button>
    </form>

    <h2>Items</h2>
    <?php
    $result = $item->read();
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Image</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td><img src='uploads/{$row['image']}' alt='Item Image' style='max-width: 100px;'></td>";
            echo "<td>";
            echo "<a href='?update_id={$row['id']}'>Update</a> | ";
            echo "<a href='?delete_id={$row['id']}' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div>Tidak ada item</div>";
    }
    ?>
</body>
</html>
