<?php
$db = new Database();
$data = $db->query("SELECT * FROM artikel");
?>

<h2>Daftar Artikel</h2>

<table border="1" width="100%" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Aksi</th>
    </tr>

    <?php while ($row = $data->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['judul'] ?></td>
        <td>
            <a href="/Lab11Web/artikel/ubah?id=<?= $row['id'] ?>">Ubah</a>
        </td>
    </tr>
    <?php } ?>
</table>
