<base href="../">
<?php
/**
 * Kemaskini kelas
 */

require '../php/conn.php';

accessAdmin('Akses tanpa kebenaran!');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'kemaskini_kelas') {

    $id = $_POST['id'];
    $nama = $_POST['nama'];

    $redirect = isset($_GET['redir']) ? $_GET['redir'] : '';

    if (updateKelas($id, $nama)) {

        echo alert('Kemaskini berjaya.') . redirect($redirect);
    } else {

        die(alert('Kemaskini gagal.') . back());
    }
}

_assert(isset($_GET['id_kelas']) && $kelas = getKelasById($_GET['id_kelas']), alert('Sila masukkan ID Kelas yang sah!') . back(), 1);

/**
 * Kemaskini Kelas berdasarkan ID Kelas
 * @param int $id_kelas ID Kelas hendak dikemaskini
 * @param string $n_nama Nama Kelas Baharu
 * @return bool TRUE jika berjaya, FALSE sebaliknya.
 */
function updateKelas(int $id_kelas, string $n_nama)
{

    global $conn;
    $query = "UPDATE kelas SET k_nama = ? WHERE k_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $n_nama, $id_kelas);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return true;
    }

    return false;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kemaskini Kelas | NSejarah</title>

    <link rel="stylesheet" href="base.css">
</head>

<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php'; ?>
        </div>
        <main>
            <div id="kemaskini-kelas" class="kemaskini-kelas-form">
                <h2>[&bigoplus;] Kemaskini Kelas</h2>

                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $kelas['k_id'] ?>">

                    <label for="nama" class="input-container">
                        <span>Nama Kelas</span>

                        <input type="text" id="nama" class="input-field" name="nama" value="<?= $kelas['k_nama'] ?>" maxlength="255" required="required">
                    </label>

                    <button type="submit" name="submit" value="kemaskini_kelas">Kemaskini</button>
                </form>
                <p style="white-space: pre-wrap;">
                    Nota: Semua tingkatan yang menggunakan nama kelas ini akan turut berubah.
                </p>
            </div>
        </main>

        <?php require '../footer.php'; ?>
    </div>
</body>

</html>