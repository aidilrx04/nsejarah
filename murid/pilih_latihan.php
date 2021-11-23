<base href="../">
<?php

require '../php/conn.php';

accessMurid('Akses tanpa kebenaran!');

$murid = getMuridById($_SESSION['id']);
$ting = getTingById($murid['m_kelas']);

$kuiz_list = getKuizByTing($ting['kt_id']);

$q = $_GET['q'] ?? NULL;

if ($q) {
    $kuiz_list = array_filter($kuiz_list, function ($kuiz) {
        global $q;

        return strpos(strtolower($kuiz['kz_nama']), strtolower($q)) !== false;
    });
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Latihan/Kuiz | Nsejarah</title>

    <link rel="stylesheet" href="base.css">
</head>

<body>
    <div class="container">
        <div id="navigasi"><?php require '../header.php' ?></div>

        <main>

            <h2>Pilih Latihan</h2>

            <div id="pilih-latihan">

                <!-- QUERY -->
                <div class="search-box">
                    <form action="murid/pilih_latihan.php">
                        <input type="text" name="q" <?= $q ? 'autofocus' : '' ?> value="<?= $q ?>" placeholder="Cari">
                        <button> <i class="fas fa-search"></i> Cari</button>
                    </form>
                </div>


                <table border="1">
                    <thead>
                        <tr>
                            <th>Bil</th>
                            <th>Nama Kuiz</th>
                            <th>Jenis</th>
                            <th>Bil Soalan</th>
                            <th>Skor</th>
                            <th>Peratus</th>
                            <th>Jawab</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        foreach ($kuiz_list as $bil => $kuiz) {

                        ?>
                            <tr>
                                <td><?= $bil + 1 ?></td>

                                <td><?= $kuiz['kz_nama'] ?></td>

                                <td><?= $kuiz['kz_jenis'] ?></td>

                                <td>
                                    <?php
                                    $soalan_list = getSoalanByKuiz($kuiz['kz_id']);
                                    ?>
                                    <?= count($soalan_list) ?>
                                </td>

                                <td>
                                    <?php
                                    // $skor = getSkorByMurid( $murid['m_id'], $kuiz['kz_id'] );
                                    $jawapan_murid = getJawapanMurid($murid['m_id'], $kuiz['kz_id']);
                                    $skor = $jawapan_murid ? countSkorMurid($jawapan_murid, $kuiz['kz_id']) : false;
                                    /**
                                     * p = ( n/t ) * 100
                                     */
                                    ?>
                                    <?= $skor ? $skor['betul'] : 0 ?>
                                    /
                                    <?= count($soalan_list) ?>
                                </td>

                                <td><?= $skor ? $skor['peratus'] : 0 ?>%</td>

                                <td>
                                    <?= !$skor ? "<a href=\"murid/jawab_kuiz.php?id_kuiz={$kuiz['kz_id']}\">Pilih</a>" : "<a href=\"murid/ulangkaji.php?id_murid={$murid['m_id']}&id_kuiz={$kuiz['kz_id']}\">Ulangkaji</a>" ?>
                                </td>
                            </tr>
                        <?php

                        }
                        ?>
                    </tbody>
                </table>
            </div>


        </main>

        <?php require '../footer.php' ?>

    </div>

</body>

</html>