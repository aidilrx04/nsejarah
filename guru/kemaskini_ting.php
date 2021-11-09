<base href="../">
<?php
/**
 * Kemaskini tingkatan
 */

require '../php/conn.php';

accessAdmin('Akses tanpa kebenaran!');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'kemaskini_tingkatan') {

    $id = $_POST['id'];
    $ting = $_POST['ting'];
    $kelas = $_POST['kelas'];
    $guru = $_POST['guru'];

    $redirect = isset($_GET['redir']) ? $_GET['redir'] : '';

    if (updateTingkatan($id, $ting, $kelas, $guru)) {

        echo alert('Kemaskini berjaya!') . redirect($redirect);
    } else {

        die(alert('Kemaskini gagal!') . back());
    }
}

_assert(isset($_GET['id_ting']) && ($ting = getTingById($_GET['id_ting'])), alert('Sila masukkan ID Tingkatan') . back(), 1);

$kelas_list = getKelasList(1000000);
$guru_list = getGuruList(100000);

/**
 * Kemaskini Tingkatan berdasarkan ID Tingkatan
 * @param int $id_ting Tingkatan hendak dikemaskini
 * @param int|string $n_ting Tingkatan Baharu
 * @param int $n_kelas_id ID Kelas Baharu
 * @param int $n_guru_id ID Guru Baharu
 * @return bool TRUE jika berjaya kemaskini. FALSE sebaliknya.
 */
function updateTingkatan(
    int $id_ting,
    $n_ting,
    int $n_kelas_id,
    int $n_guru_id
) {

    global $conn;
    $query = "UPDATE kelas_tingkatan SET kt_ting = ?, kt_kelas = ?, kt_guru = ? WHERE kt_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ssss', $n_ting, $n_kelas_id, $n_guru_id, $id_ting);
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
    <title>Kemaskini Tingkatan | NSejarah</title>

    <link rel="stylesheet" href="base.css">
</head>

<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php'; ?>
        </div>
        <main>
            <div id="kemaskini-tingkatan" class="kemaskini-tingkatan-form">
                <h2>[&bigoplus;] Kemaskini Tingkatan</h2>

                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $ting['kt_id'] ?>">

                    <label for="ting" class="input-container">
                        <span>Tingkatan</span>

                        <input type="number" name="ting" id="ting" class="input-field" max="5" min="1" value="<?= $ting['kt_ting'] ?>" required="required">
                    </label>

                    <label for="kelas" class="input-container">
                        <span>Nama Kelas</span>

                        <select name="kelas" id="kelas" class="input-field">
                            <?php
                            foreach ($kelas_list as $kelas) {

                                $isKelas = $ting['kt_kelas'] == $kelas['k_id'];

                            ?>
                                <option value="<?= $kelas['k_id'] ?>" <?= $isKelas ? 'selected' : '' ?>><?= $kelas['k_nama'] ?></option>
                            <?php

                            }
                            ?>
                        </select>
                    </label>

                    <label for="guru" class="input-container">
                        <span>Guru</span>

                        <select name="guru" id="guru" class="input-field">
                            <?php
                            foreach ($guru_list as $guru) {

                                $isGuru = $guru['g_id'] == $ting['kt_guru'];

                            ?>
                                <option value="<?= $guru['g_id'] ?>" <?= $isGuru ? 'selected' : '' ?>><?= $guru['g_nama'] ?> - <?= $guru['g_nokp'] ?></option>
                            <?php

                            }
                            ?>
                        </select>
                    </label>

                    <button type="submit" name="submit" value="kemaskini_tingkatan">
                        Kemaskini
                    </button>
                </form>
            </div>
        </main>

        <?php require '../footer.php'; ?>
    </div>
</body>

</html>