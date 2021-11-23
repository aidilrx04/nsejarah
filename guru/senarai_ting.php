<base href="../">
<?php

/**
 * Senarai Kelas
 */

require '../php/conn.php';

accessAdmin('Akses tanpa kebenaran!');

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $jenis_submit = $_POST['submit'];

    if ($jenis_submit == 'tambah_ting') {

        $ting = $_POST['ting'];
        $kelas = $_POST['kelas'];
        $guru = $_POST['guru'];

        if (registerTing($ting, $kelas, $guru)) {

            echo alert('Data berjaya dimuatnaik!');
        } else die(alert('Data gagal dimuatnaik!') . back());
    } else if ($jenis_submit == 'tambah_kelas') {

        $nama_kelas = $_POST['nama'];

        if (registerKelas($nama_kelas)) {

            echo alert('Data berjaya dimuatnaik!');
        } else die(alert('Data gagal dimuatnaik!') . back());
    }
}


$kelas_list_main = getKelasList(-1);

$qt = $_GET['qt'] ?? NULL;
$qk = $_GET['qk'] ?? NULL;

if ($qt) {
    $query = "SELECT kelas_tingkatan.* FROM kelas_tingkatan, kelas WHERE kelas.k_id = kelas_tingkatan.kt_kelas AND kelas.k_nama LIKE ?";

    $ting_list = get_query($query, '%' . $qt . '%');
} else {
    $ting_list = getTingList(-1);
}

if ($qk) {
    $kelas_list_main = array_filter($kelas_list_main, function ($kelas) {
        global $qk;
        return strpos(strtolower($kelas['k_nama']), strtolower($qk)) !== false;
    });
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Senarai Tingkatan</title>

    <link rel="stylesheet" href="base.css">
</head>

<body>
    <div class="container">
        <div id="navigasi">
            <?php require 'header_guru.php'; ?>
        </div>

        <main>

            <h2>Senarai Tingkatan & Kelas</h2>

            <div id="senarai-tingkatan">
                <h3>Senarai Tingkatan</h3>

                <!-- QUERY -->
                <div class="search-box">
                    <form action="guru/senarai_ting.php#senarai-tingkatan">
                        <input type="text" name="qt" value="<?= $qt ?>" placeholder="Cari" <?= $qt ? 'autofocus' : '' ?>>
                        <button> <i class="fas fa-search"></i> Cari</button>
                    </form>
                </div>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Tingkatan</th>

                            <th>Nama</th>

                            <th>Guru</th>

                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <form action="" method="post">
                                <td>
                                    <input type="number" name="ting" id="ting" min="1" max="5" placeholder="cth. 1" required="required">
                                </td>

                                <td>
                                    <select name="kelas" id="kelas" required="required">
                                        <?php
                                        $kelas_list = getKelasList(-1);

                                        foreach ($kelas_list as $kelas) {

                                        ?>
                                            <option value="<?= $kelas['k_id'] ?>"><?= $kelas['k_nama'] ?></option>
                                        <?php

                                        }
                                        ?>
                                    </select>
                                </td>

                                <td>
                                    <select name="guru" id="guru">
                                        <?php
                                        $guru_list = getGuruList();

                                        foreach ($guru_list as $guru) {

                                        ?>
                                            <option value="<?= $guru['g_id'] ?>"><?= $guru['g_nama'] ?></option>
                                        <?php

                                        }
                                        ?>
                                    </select>
                                </td>

                                <td>
                                    <button type="submit" name="submit" value="tambah_ting">Simpan</button>
                                </td>
                            </form>
                        </tr>

                        <?php


                        foreach ($ting_list as $ting) {

                            $kelas = getKelasById($ting['kt_kelas']);
                            $guru = getGuru($ting['kt_guru']);

                        ?>

                            <tr>
                                <td><?= $ting['kt_ting'] ?></td>

                                <td><?= $kelas['k_nama'] ?></td>

                                <td><?= $guru['g_nama'] ?></td>

                                <td>
                                    <a href="guru/kemaskini_ting.php?id_ting=<?= $ting['kt_id'] ?>&redir=guru/senarai_ting.php" class="kemaskini">Kemaskini</a>

                                    <a onclick="return deleteConfirmation()" href="guru/padam.php?table=kelas_tingkatan&col=kt_id&val=<?= $ting['kt_id'] ?>&redir=guru/senarai_ting.php" class="padam">Padam</a>
                                </td>
                            </tr>

                        <?php

                        }

                        ?>
                    </tbody>
                </table>
            </div>



            <div id="senarai-kelas">

                <h3>Senarai Kelas</h3>

                <!-- QUERY -->
                <div class="search-box">
                    <form action="guru/senarai_ting.php#senarai-kelas">
                        <input type="text" name="qk" value="<?= $qk ?>" placeholder="Cari" <?= $qk ? 'autofocus' : '' ?>>
                        <button> <i class="fas fa-search"></i> Cari</button>
                    </form>
                </div>

                <table border="1">
                    <thead>
                        <tr>
                            <th>Nama</th>

                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <form action="" method="post">
                                <td>
                                    <input type="text" name="nama" id="nama_kelas" placeholder="cth. Amanah" required="required">
                                </td>

                                <td>
                                    <button type="submit" name="submit" value="tambah_kelas">Simpan</button>
                                </td>
                            </form>
                        </tr>

                        <?php


                        foreach ($kelas_list_main as $kelas) {

                        ?>
                            <tr>
                                <td><?= $kelas['k_nama'] ?></td>

                                <td>
                                    <a href="guru/kemaskini_kelas.php?id_kelas=<?= $kelas['k_id'] ?>&redir=guru/senarai_ting.php" class="kemaskini">Kemaskini</a>
                                    <a onclick="return deleteConfirmation()" href="guru/padam.php?table=kelas&col=k_id&val=<?= $kelas['k_id'] ?>&redir=guru/senarai_ting.php" class="padam">Padam</a>
                                </td>
                            </tr>
                        <?php

                        }

                        ?>
                    </tbody>
                </table>
            </div>


        </main>

        <?php require '../footer.php'; ?>
    </div>

</body>

</html>