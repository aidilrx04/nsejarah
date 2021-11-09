<base href="../">
<?php
/**
 * JAWAB KUIZ
 * 
 * ! IMPORTANT CHANGES
 * images url save in db is now filename only.
 * rather than full images path in previous version
 */

require '../php/conn.php';


accessMurid('Akses tanpa kebenaran!');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['submit_form'] == 'submit_jawapan') {

    # Jika murid sudah menjawab, paparan ralat akan dikeluarkan
    _assert(!($jm =  getJawapanMurid($_SESSION['id'], $_POST['kuiz']['id'])), alert('Anda telah menjawab kuiz. Tidak boleh mencuba lagi!'), 0);

    $kuiz = getKuizById($_POST['kuiz']['id']);
    $murid = getMuridById($_SESSION['id']);
    $soalan_list = $_POST['s'];
    $jumlah = count($soalan_list);
    $bil_berjaya = 0;


    $conn->begin_transaction();
    foreach ($soalan_list as $iid => $soalan) {

        $id_soalan = $soalan['id'];
        $jawapan_murid = isset($soalan['j']) ? $soalan['j'] : NULL;

        if ($berjaya = registerJawapanMurid($murid['m_id'], $id_soalan, $jawapan_murid)) {

            if ($berjaya) $bil_berjaya++;
        }
    }

    // register skor_murid
    $skor = ($bil_berjaya / $jumlah) * 100;
    if ($id_skor = registerSkorMurid($murid['m_id'], $kuiz['kz_id'], $skor)) {

        echo alert('Jawapan berjaya dimuatnaik!') . redirect("murid/jawab_semak.php?id_murid={$murid['m_id']}&id_kuiz={$kuiz['kz_id']}");

        // simpan semua executed query ke dalam database
        $conn->commit();
    } else {

        //reset semula semua query yang telah di-execute
        $conn->rollback();
        die(alert('Jawapan gagal dimuatnaik!'));
    }
}

_assert(isset($_GET['id_kuiz']) && !empty($_GET['id_kuiz']), alert('Sila masukkan ID Kuiz') . back(), 1);

$murid = getMuridById($_SESSION['id']);
$kuiz = getKuizById($_GET['id_kuiz']);

// jika murid sudah jawab, pindah lokasi ke jawab_semak.php
_assert(!($jm = getJawapanMurid($_SESSION['id'], $_GET['id_kuiz'])), redirect("murid/jawab_semak.php?id_murid={$murid['m_id']}&id_kuiz={$kuiz['kz_id']}"), 1);
// var_dump( getSkorMuridByKuiz( $_SESSION['id'], $_GET['id_kuiz']) );




_assert($murid['m_kelas'] == $kuiz['kz_ting'], alert('Akses tanpa kebenaran!') . back(), 1);

$soalan_list = getSoalanByKuiz($kuiz['kz_id']);

_assert(count($soalan_list) > 0, alert('Kuiz tiada soalan!') . back(), 1);

$mula = isset($_GET['m']) ? $_GET['m'] : 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $kuiz['kz_nama'] ?> | Nsejarah</title>

    <link rel="stylesheet" href="base.css">
    <style>
        /** Custom style */
        .soalan {
            padding: 10px 5px;
            margin-bottom: 10px;
        }

        .soalan p {

            padding: 10px 0;
            background-color: #ddd;

        }

        .soalan .jawapan {

            margin: 5px 0;
            padding: 10px 0;

        }

        .soalan .jawapan .input-container {

            display: block;

        }
    </style>
</head>

<body>
    <div class="container">
        <div id="navigasi"><?php require '../header.php' ?></div>

        <main>

            <h2><?= $kuiz['kz_nama'] ?> <span style="float: right;">Masa: <span id="masa"><?= $kuiz['kz_masa'] ? $kuiz['kz_masa'] . 'minit' : 'Tiada' ?></span></span></h2>

            <?php
            if (!$mula) {

            ?>
                <form action="">
                    <input type="hidden" name="id_kuiz" value="<?= $kuiz['kz_id'] ?>">
                    <button type="submit" name="m" value="1">Mula</button>
                </form>
            <?php

            } else {

            ?>
                <div id="jawab-kuiz">

                    <form action="" method="post" id="jawab-form">
                        <input type="hidden" name="kuiz[id]" value="<?= $kuiz['kz_id'] ?>">

                        <?php

                        foreach ($soalan_list as $bil => $soalan) {

                            $soalan_id = uniqid();

                        ?>
                            <div class="soalan">
                                <input type="hidden" name="s[<?= $soalan_id ?>][id]" value="<?= $soalan['s_id'] ?>">
                                <p>
                                    <b><?= $bil++ ?>.</b>
                                    <?= $soalan['s_teks'] ?>

                                <div style="max-width: 300px;"><?= $soalan['s_gambar'] ? "<img style=\"max-width: 100%;\" src=\"{$IMAGE_DIR}{$soalan['s_gambar']}\">" : '' ?></div>
                                </p>

                                <div class="jawapan">
                                    <?php
                                    // randomize jawapan position
                                    $jawapan_list = getJawapanBySoalan($soalan['s_id']);
                                    shuffle($jawapan_list);

                                    foreach ($jawapan_list as $jawapan) {

                                        $jawapan_id = uniqid();

                                    ?>
                                        <label for="<?= $jawapan_id ?>" class="input-container">
                                            <input type="radio" name="s[<?= $soalan_id ?>][j]" value="<?= $jawapan['j_id'] ?>" id="<?= $jawapan_id ?>">
                                            <span>
                                                <?= $jawapan['j_teks'] ?>
                                            </span>
                                        </label>
                                    <?php

                                    }
                                    ?>
                                </div>

                                <hr>

                            </div>
                        <?php

                        }
                        ?>

                        <button type="submit" name="submit_form" value="submit_jawapan">Hantar</button>

                    </form>

                    <?php
                    if ($kuiz['kz_jenis'] == 'kuiz') {
                    ?>
                        <script>
                            const form = document.querySelector("#jawab-form");
                            const submitBtn = form.querySelector('button[type="submit"]');
                            const masa_minit = parseInt(<?= $kuiz['kz_masa'] ?>);

                            const submitForm = setTimeout(function() {
                                submitBtn.click();
                            }, masa_minit * 60 * 1000);

                            function countTimer(masa, elem) {

                                var jum_masa = masa * 60;

                                setInterval(function() {
                                    const minit = parseInt(jum_masa / 60);
                                    const saat = jum_masa % 60;

                                    elem.innerHTML = `<b>${minit} minit ${saat} saat</b>`;
                                    // console.log( elem.innerHTML );
                                    jum_masa--;

                                }, 1000);

                            }
                            countTimer(masa_minit, document.querySelector('#masa'));
                        </script>
                    <?php
                    }
                    ?>
                </div>
            <?php

            }
            ?>


        </main>

        <?php require '../footer.php' ?>

    </div>
</body>

</html>