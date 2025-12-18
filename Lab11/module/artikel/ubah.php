<?php
$db = new Database();

$id = $_GET['id'];
$old = $db->get("artikel", "id=$id");

$form = new Form("/Lab11Web/artikel/ubah?id=$id", "Update");

if ($_POST) {
    $data = [
        'judul' => $_POST['judul'],
        'isi' => $_POST['isi']
    ];
    $db->update("artikel", $data, "id=$id");
    echo "<p style='color:green'>Artikel berhasil diperbarui!</p>";
}
?>

<h2>Ubah Artikel</h2>
<?php
$form->addField("judul", "Judul", "text");
$form->addField("isi", "Isi", "textarea");
$form->displayForm();
?>