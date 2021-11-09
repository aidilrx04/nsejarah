<base href="../">

<?php

/**
 * Kemaskini murid
 */

require '../php/conn.php';

accessAdmin('Akses tanpa kebenaran!');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit'] == 'kemaskini_murid') {

    $id = $_POST['id'];
    $nokp = $_POST['nokp'];
    $nama = $_POST['nama'];
    $katalaluan = $_POST['katalaluan'];
    $kelas = $_POST['kelas'];

    if ($berjaya = updateMurid($id, $nokp, $nama, $katalaluan, $kelas)) {

        echo alert('Data berjaya dikemaskini!') . (isset($_GET['redir']) ? redirect($_GET['redir']) : '');
    } else {

        die(alert('Data gagal dikemaskini!') . back());
    }
}

_assert(isset($_GET['id_murid']), alert('Sila masukkan ID Murid') . back(), 1);
_assert($murid = getMuridById($_GET['id_murid']), alert('ID Murid tidak sah!') . back(), 1);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kemaskini Murid</title>

    <link rel="stylesheet" href="base.css">
</head>

<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php'; ?>
        </div>

        <main>
            <div class="kemaskini-form">
                <h2>Kemaskini Maklumat Murid</h2>

                <form action="" method="post">
                    <input type="hidden" name="id" value="<?= $murid['m_id'] ?>">

                    <label for="nokp" class="input-container">
                        <span>No. Kad Pengenalan</span>

                        <input type="text" name="nokp" id="nokp" class="input-field" value="<?= $murid['m_nokp'] ?>" maxlength="12" required="required">
                    </label>

                    <label for="nama" class="input-container">
                        <span>Nama</span>

                        <input type="text" name="nama" id="nama" class="input-field" value="<?= $murid['m_nama'] ?>" maxlength="255" required="required">
                    </label>

                    <label for="katalaluan" class="input-container">
                        <span>Katalaluan</span>

                        <input type="text" name="katalaluan" id="katalaluan" class="input-field" value="<?= $murid['m_katalaluan'] ?>" maxlength="15" required="required">
                    </label>

                    <label for="kelas" class="input-container">
                        <span>Kelas</span>

                        <select name="kelas" id="kelas" class="input-field">
                            <?php
                            $ting_list = getTingList(-1);

                            foreach ($ting_list as $t) {

                                $muridKelas = $murid['m_kelas'] == $t['kt_id'] ? 1 : 0;
                                $kelas = getKelasById($t['kt_kelas']);

                            ?>
                                <option value="<?= $t['kt_id'] ?>" <?= $muridKelas ? 'selected' : '' ?>>
                                    <?= $t['kt_ting'] . ' ' . $kelas['k_nama'] ?>
                                </option>
                            <?php

                            }

                            ?>
                        </select>
                    </label>

                    <button type="submit" name="submit" value="kemaskini_murid">Kemaskini</button>
                </form>
            </div>
        </main>
        <?php require '../footer.php'; ?>
    </div>
</body>

</html>