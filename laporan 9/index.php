<?php
require_once "koneksi.php";
 
function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

$action = $_REQUEST['action'] ?? '';
$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($name === '' || $email === '') {
        $message = "Nama dan Email wajib diisi!";
    } else {
        // 🔍 Cek apakah email sudah ada
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $message = "Email sudah terdaftar, gunakan email lain!";
        } else {
            // 🧩 Coba tambahkan data baru
            try {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, phone) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $phone]);
                $message = "Data berhasil ditambahkan.";
            } catch (Exception $ex) {
                $message = "Gagal menambah data: " . e($ex->getMessage());
            }
        }
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if ($id <= 0 || $name === '' || $email === '') {
        $message = "ID, Nama, dan Email wajib diisi!";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=?");
        try {
            $stmt->execute([$name, $email, $phone, $id]);
            $message = "Data berhasil diperbarui.";
        } catch (Exception $ex) {
            $message = "Gagal memperbarui data: " . e($ex->getMessage());
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        try {
            $stmt->execute([$id]);
            $message = "Data berhasil dihapus.";
        } catch (Exception $ex) {
            $message = "Gagal menghapus data: " . e($ex->getMessage());
        }
    }
}

$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();



$editUser = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        $editUser = $stmt->fetch();
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>CRUD PHP - index.php</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    body{font-family:Arial, sans-serif;padding:20px;background:#f5f5f5;}
    .card{background:#fff;border-radius:12px;padding:16px;box-shadow:0 6px 18px rgba(0,0,0,0.08);max-width:900px;margin:auto;}
    table{width:100%;border-collapse:collapse;margin-top:12px;}
    th,td{padding:10px;border-bottom:1px solid #ddd;text-align:left;}
    form{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
    input[type=text],input[type=email]{padding:8px;border-radius:8px;border:1px solid #ccc;}
    button{padding:8px 12px;border-radius:8px;border:0;background:#007bff;color:#fff;cursor:pointer;}
    .danger{background:#d9534f;}
    .muted{color:#666;font-size:0.9rem;}
  </style>
</head>
<body>
  <div class="card">
    <h2>CRUD Data Users</h2>

    <?php if($message): ?>
      <p><strong><?= e($message) ?></strong></p>
    <?php endif; ?>

    <h3><?= $editUser ? 'Edit User' : 'Tambah User' ?></h3>
    <form method="post" action="index.php">
      <?php if($editUser): ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= e($editUser['id']) ?>">
        <input type="text" name="name" placeholder="Nama" value="<?= e($editUser['name']) ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?= e($editUser['email']) ?>" required>
        <input type="text" name="phone" placeholder="Telepon" value="<?= e($editUser['phone']) ?>">
        <button type="submit">Simpan</button>
        <a href="index.php" style="text-decoration:none;color:#007bff;">Batal</a>
      <?php else: ?>
        <input type="hidden" name="action" value="create">
        <input type="text" name="name" placeholder="Nama" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Telepon">
        <button type="submit">Tambah</button>
      <?php endif; ?>
    </form>

    <h3 style="margin-top:20px">Daftar User</h3>
<?php if(count($users) === 0): ?>
  <p class="muted">Belum ada data.</p>
<?php else: ?>
  <table>
    <thead>
      <tr><th>No</th><th>Nama</th><th>Email</th><th>Telepon</th><th>Tanggal</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      <?php $no = 1; foreach($users as $u): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= e($u['name']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['phone']) ?></td>
          <td><?= e($u['created_at']) ?></td>
          <td>
            <a href="index.php?edit=<?= e($u['id']) ?>">Edit</a> |
            <form method="post" action="index.php" style="display:inline" onsubmit="return confirm('Yakin ingin hapus?');">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= e($u['id']) ?>">
              <button type="submit" class="danger">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>
