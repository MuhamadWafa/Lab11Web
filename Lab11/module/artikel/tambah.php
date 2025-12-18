<?php
$db = new Database();
$form = new Form("/lab11_php_oop/artikel/tambah", "Simpan");

if ($_POST) {
    $data = [
        'judul' => $_POST['judul'],
        'isi' => $_POST['isi']
    ];

    $db->insert("artikel", $data);
    echo "<p style='color:green'>Artikel berhasil ditambahkan!</p>";
}
?>

<h2>Tambah Artikel</h2>

<?php
$form->addField("judul", "Judul Artikel");
$form->addField("isi", "Isi Artikel", "textarea");
$form->displayForm();
?>
