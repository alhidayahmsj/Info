<?php
$file = __DIR__ . "/breaking.csv";

// Pastikan file sudah ada
if (!file_exists($file)) {
    file_put_contents($file, "tanggal,jam,pesan\n");
}

// Jika ada form yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $jam     = $_POST['jam'] ?? '';
    $pesan   = $_POST['pesan'] ?? '';

    if ($tanggal && $jam && $pesan) {
        $line = "$tanggal,$jam,\"$pesan\"\n";
        file_put_contents($file, $line, FILE_APPEND);
        $msg = "✅ Breaking news berhasil ditambahkan!";
    } else {
        $msg = "⚠️ Semua field wajib diisi.";
    }
}

// Baca semua data
$rows = array_map('str_getcsv', file($file));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Breaking News</title>
  <style>
    body { font-family: Arial, sans-serif; padding:20px; }
    h1 { margin-bottom:10px; }
    form { margin-bottom:20px; }
    label { display:block; margin:8px 0 4px; }
    input, textarea { width:100%; padding:8px; margin-bottom:10px; }
    table { border-collapse: collapse; width:100%; margin-top:20px; }
    th, td { border:1px solid #ccc; padding:8px; text-align:left; }
    th { background:#eee; }
    .msg { margin-bottom:15px; padding:10px; background:#f0f8ff; border:1px solid #99c; }
  </style>
</head>
<body>

<h1>Tambah Breaking News</h1>

<?php if (!empty($msg)) echo "<div class='msg'>$msg</div>"; ?>

<form method="post">
  <label>Tanggal</label>
  <input type="date" name="tanggal" required>

  <label>Jam</label>
  <input type="time" name="jam" required>

  <label>Pesan</label>
  <textarea name="pesan" rows="3" required></textarea>

  <button type="submit">Simpan</button>
</form>

<h2>Daftar Breaking News</h2>
<table>
  <tr>
    <th>Tanggal</th>
    <th>Jam</th>
    <th>Pesan</th>
  </tr>
  <?php foreach (array_slice($rows,1) as $r): ?>
  <tr>
    <td><?= htmlspecialchars($r[0]) ?></td>
    <td><?= htmlspecialchars($r[1]) ?></td>
    <td><?= htmlspecialchars($r[2]) ?></td>
  </tr>
  <?php endforeach; ?>
</table>

</body>
</html>