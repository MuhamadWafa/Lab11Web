# Lab11Web
# Muhamad Wafa Mufida Zulfi
# 312410334
# TI.24.A4
# Agung Nugroho, S.Kom., M.Kom.

## 1) Struktur Folder
```
lab11_php_oop/
├── .htaccess
├── config.php
├── index.php
├── class/
│   ├── Database.php
│   └── Form.php
├── module/
│   ├
│   └── artikel/
│       ├── index.php
│       ├── tambah.php
│       └── ubah.php
└── template/
    ├── header.php
    ├── footer.php
    └── sidebar.php
```
<img width="223" height="233" alt="image" src="https://github.com/user-attachments/assets/3c31a51b-fe3b-4f00-bf47-c3c981ade92e" />

## 2) Konfigurasi Routing-Htaccess
Saya membuat file .htaccess agar URL bisa diakses seperti:

`localhost/lab11_php_oop/artikel/tambah`

ISI FILE;
```python
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /Lab11Web/

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f

    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```
## 3) file config.php
```python
<?php
$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'db_name' => 'latihan_oop'
];
?>
```
## 4) Memasukkan Database.php & Form.php ke folder class
### class/database.php
```python
<?php

class Database
{
    protected $host;
    protected $user;
    protected $password;
    protected $db_name;
    protected $conn;

    public function __construct()
    {
        $this->getConfig();
        $this->conn = new mysqli(
            $this->host,
            $this->user,
            $this->password,
            $this->db_name
        );

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function getConfig()
    {
        include("config.php");
        $this->host = $config['host'];
        $this->user = $config['username'];
        $this->password = $config['password'];
        $this->db_name = $config['db_name'];
    }

    public function query($sql)
    {
        return $this->conn->query($sql);
    }

    public function get($table, $where = null)
    {
        if ($where) {
            $where = " WHERE " . $where;
        }
        $sql = "SELECT * FROM " . $table . $where;
        $sql = $this->conn->query($sql);
        return $sql->fetch_assoc();
    }

    public function insert($table, $data)
    {
        foreach ($data as $key => $val) {
            $column[] = $key;
            $value[] = "'{$val}'";
        }

        $columns = implode(",", $column);
        $values = implode(",", $value);

        $sql = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
        return $this->conn->query($sql);
    }

    public function update($table, $data, $where)
    {
        foreach ($data as $key => $val) {
            $update_value[] = "$key='{$val}'";
        }

        $update_value = implode(",", $update_value);

        $sql = "UPDATE " . $table . " SET " . $update_value . " WHERE " . $where;
        return $this->conn->query($sql);
    }
}
?>
```
### class/form.php
```python
<?php

/**
 * Nama Class: Form
 * Deskripsi: Class untuk membuat form inputan dinamis
 */

class Form
{
    private $fields = array();
    private $action;
    private $submit = "Submit Form";
    private $jumField = 0;

    public function __construct($action, $submit)
    {
        $this->action = $action;
        $this->submit = $submit;
    }

    public function displayForm()
    {
        echo "<form action='" . $this->action . "' method='POST'>";
        echo '<table width="100%" border="0">';

        foreach ($this->fields as $field) {
            echo "<tr><td align='right' valign='top'>" . $field['label'] . "</td>";
            echo "<td>";

            switch ($field['type']) {

                case 'textarea':
                    echo "<textarea name='" . $field['name'] . "' cols='30' rows='4'></textarea>";
                    break;

                case 'select':
                    echo "<select name='" . $field['name'] . "'>";
                    foreach ($field['options'] as $value => $label) {
                        echo "<option value='" . $value . "'>" . $label . "</option>";
                    }
                    echo "</select>";
                    break;

                case 'radio':
                    foreach ($field['options'] as $value => $label) {
                        echo "<label><input type='radio' name='" . $field['name'] . "' value='" . $value . "'> " . $label . "</label> ";
                    }
                    break;

                case 'checkbox':
                    foreach ($field['options'] as $value => $label) {
                        echo "<label><input type='checkbox' name='" . $field['name'] . "[]' value='" . $value . "'> " . $label . "</label> ";
                    }
                    break;

                case 'password':
                    echo "<input type='password' name='" . $field['name'] . "'>";
                    break;

                default:
                    echo "<input type='text' name='" . $field['name'] . "'>";
                    break;
            }

            echo "</td></tr>";
        }

        echo "<tr><td colspan='2'>";
        echo "<input type='submit' value='" . $this->submit . "'></td></tr>";
        echo "</table>";
        echo "</form>";
    }

    public function addField($name, $label, $type = "text", $options = array())
    {
        $this->fields[$this->jumField]['name'] = $name;
        $this->fields[$this->jumField]['label'] = $label;
        $this->fields[$this->jumField]['type'] = $type;
        $this->fields[$this->jumField]['options'] = $options;
        $this->jumField++;
    }
}
?>
```
## 4) router utama, index.php
File index.php berfungsi sebagai front controller (router):
```python
<?php
include "config.php";
include "class/Database.php";
include "class/Form.php";

session_start();

// Ambil PATH_INFO → /artikel/tambah
$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/home/index';

$segments = explode('/', trim($path, '/'));

$mod  = $segments[0] ?? 'home';
$page = $segments[1] ?? 'index';

$file = "module/{$mod}/{$page}.php";

include "template/header.php";

if (file_exists($file)) {
    include $file;
} else {
    echo "<h3>Modul tidak ditemukan: $mod / $page</h3>";
}

include "template/footer.php";
```
## membuat template (header,footer)
`header.php`
```python
<!DOCTYPE html>
<html>
<head>
    <title>Lab 11 PHP OOP</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .menu a { margin-right: 20px; }
    </style>
</head>
<body>

<div class="menu">
    <a href="/Lab11Web/home/index">Home</a>
    <a href="/Lab11Web/artikel/index">Artikel</a>
    <a href="/Lab11Web/artikel/tambah">Tambah Artikel</a>
</div>
<hr>
```
`footer.php`
```python
<hr>
<footer>
    <p>Modular Routing — Praktikum 11</p>
</footer>
</body>
</html>
```
## 7) membuat modul artikel
### Modul: `artikel/index.php`
Menampilkan seluruh data;
```python
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
```
<img width="1919" height="353" alt="image" src="https://github.com/user-attachments/assets/0dd7a179-832f-4811-ab7f-d9d6ef703bdc" />

### Modul: artikel/tambah.php
Form menambah data artikel:
```python
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
```

<img width="1919" height="493" alt="image" src="https://github.com/user-attachments/assets/ae1530d6-a194-4183-9464-4cddcdc5fea2" />

### Modul: artikel/ubah.php
```python
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
```
<img width="1919" height="372" alt="image" src="https://github.com/user-attachments/assets/ce129690-3cd4-431e-bc03-29fa374a90d9" />

## 8.  Database MySQL (artikel.sql)

```python
CREATE TABLE artikel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255),
    isi TEXT
);
```
<img width="643" height="353" alt="image" src="https://github.com/user-attachments/assets/91214ef6-c7f9-4847-910d-2920436a5201" />

## 9) kesimpulan praktikum
Praktikum ini berhasil mengimplementasikan sistem pengelolaan konten (CMS) sederhana dengan konsep Model-View-Controller (MVC) manual. Poin-poin utamanya adalah:

Penerapan OOP: Penggunaan class Database dan Form mempermudah pengelolaan kode (reusable code). Operasi database (Insert, Update, Select) dan pembuatan form dilakukan melalui objek sehingga kode lebih bersih dan terstruktur.

Modular Routing: File index.php bertindak sebagai Front Controller yang mengatur pemanggilan modul berdasarkan URL. Hal ini memungkinkan pemisahan logika antara modul artikel, home, dan tampilan template.

Optimasi URL: Penggunaan file .htaccess dan RewriteEngine berhasil mengubah URL mentah menjadi Friendly URL, sehingga navigasi menjadi lebih rapi (contoh: dari index.php?mod=artikel menjadi /artikel/index).

Efisiensi Pengembangan: Dengan sistem template (header/footer), perubahan pada tata letak cukup dilakukan di satu file saja tanpa harus mengubah setiap halaman modul.



