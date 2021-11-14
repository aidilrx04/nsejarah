<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nsejarah';

$root = realpath('../');
$IMAGE_DIR = 'images/';
// $PROJECT_ROOT_DIR = 'nsejarah';

/**@var mysqli $conn Sambungan ke database */
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (mysqli_connect_errno()) {
    die('Sambungan gagal: ' . mysqli_connect_errno());
} else {
    # echo 'Sambungan berjaya';
}

# start session by default if not start yet
if (session_status() == PHP_SESSION_NONE) {

    session_start();
}

// idk why i implement this
/* 
function getAbsolutePath($append = '')
{
    global $PROJECT_ROOT_DIR;
    var_dump($_SERVER['REQUEST_URI']);

    // get project path only
    $path = explode('/' . $PROJECT_ROOT_DIR, $_SERVER['REQUEST_URI']);

    // remove parent directories from path
    array_shift($path);

    // inject back project root dir to the path
    $abs_path = join('/' . $PROJECT_ROOT_DIR, $path);

    var_dump($abs_path);

    // remove front '/' & separate path dir
    $sections = explode('/', ltrim($abs_path, '/'));

    var_dump($sections);

    // determine is file is under subfolder or not

}

var_dump(getAbsolutePath());
 */
/* 
    MURID
*/

/**
 * Daftar murid
 * @param string $nokp Aksara yang panjangnya 12
 * @param string $nama Nama murid, maksimum 255 aksara
 * @param string $katalaluan Katalaluan, maksimum 255 aksara
 * @param int $kelas ID Kelas
 * @return bool TRUE sekiranya berjaya, FALSE sebaliknya
 */
function registerMurid(string $nokp, string $nama, string $katalaluan, int $kelas)
{

    global $conn;
    $query = "INSERT INTO murid(m_nokp, m_nama, m_katalaluan, m_kelas) VALUE (?,?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ssss', $nokp, $nama, $katalaluan, $kelas);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return true;
    }

    return false;
}

/**
 * Login murid menggunakan No. Kad Pengenalan($nokp) dan Katalaluan($password)
 * @param string $nokp No. Kad Pengenalan
 * @param string $password Katalaluan
 * @return array|void Tatasusunan murid jika berjaya void sebaliknya.
 */
function loginMurid($nokp, $password)
{

    global $conn;
    $table = 'murid';
    $col_1 = 'm_nokp';
    $col_2 = 'm_katalaluan';

    $query = "SELECT * FROM {$table} WHERE {$col_1} = ? AND {$col_2} = ?";
    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $nokp, $password);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            return $res->fetch_assoc();
        }
    }

    return;
}

/**
 * Dapatkan senarai murid
 * @param int $limit Had carian
 * @param int $offset Titik mula carian
 * @return array Senarai Murid
 */
function getMuridList(int $limit = 10, int $offset = 0)
{

    global $conn;
    $tambahan = $limit <= 0 ? '' : ' LIMIT ' . $limit . ' OFFSET ' . $offset;
    $query = "SELECT * FROM murid {$tambahan}";
    $murid_list = [];

    if ($stmt = $conn->prepare($query)) {

        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            # simpan data
            while ($murid = $res->fetch_assoc()) array_push($murid_list, $murid);
        }
    }
    return $murid_list;
}

/**
 * Dapatkan data murid
 * @param int $id_murid ID Murid
 * @return array|void Data murid
 */
function getMuridById(int $id_murid)
{

    global $conn;
    $col_1 = 'm_id';
    $query = "SELECT * FROM murid WHERE {$col_1} = '{$id_murid}'";
    $res = $conn->query($query);

    if ($res->num_rows > 0) return $res->fetch_assoc();

    return;
}

function getMuridByTing(int $id_ting)
{

    global $conn;
    $query = "SELECT * FROM murid WHERE m_kelas = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_ting);
        $stmt->execute();
        $res = $stmt->get_result();
        $murid_list = [];

        if ($res->num_rows > 0) {

            while ($murid = $res->fetch_assoc()) array_push($murid_list, $murid);
        }

        return $murid_list;
    }

    return false;
}

/**
 * Kemaskini data murid
 * @param int $id_murid ID Murid yang ingin dikemaskini
 * @param string $nnokp No. K/P Baru
 * @param string $nnama Nama Baru
 * @param string $nkatalaluan Katalaluan Baru
 * @param int $nkelas ID Kelas Baru
 * @return bool TRUE jika berjaya, FALSE jika gagal
 */
function updateMurid(int $id_murid, string $nnokp, string $nnama, string $nkatalaluan, int $nkelas)
{

    global $conn;
    $query = "UPDATE murid SET m_nokp = ?, m_nama = ?, m_katalaluan = ?, m_kelas = ? WHERE m_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('sssss', $nnokp, $nnama, $nkatalaluan, $nkelas, $id_murid);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt) return true;
    }

    return false;
}

function isMurid()
{

    return $_SESSION['jenis'] == 'murid' ? true : false;
}

function accessMurid($err_msg = '', $reroute = '/')
{

    if (!(isMurid()))
        die(($err_msg != '' ? alert($err_msg) : '') . redirect($reroute));

    return true;
}

/** SKOR_MURID */
function getSkorMurid($id_skor)
{

    global $conn;
    $query = "SELECT * FROM skor_murid WHERE sm_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_skor);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) return $res->fetch_assoc();
    }

    return false;
}

/* function getSkorMuridByKuiz( $id_murid, $id_kuiz )
{

    global $conn;
    $query = "SELECT * FROM skor_murid WHERE sm_murid = ? AND sm_kuiz = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ss', $id_murid, $id_kuiz );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) return $res->fetch_assoc();

    }

    return false;

} */

function getSkorByMurid($id_murid, $id_kuiz)
{

    global $conn;
    $query = "SELECT * FROM skor_murid WHERE sm_murid = ? AND sm_kuiz = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $id_murid, $id_kuiz);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) return $res->fetch_assoc();
    }

    return false;
}

function countSkorMurid(array $jawapan_murid, $id_kuiz)
{
    global $conn;

    $betul = 0;
    $salah = 0;
    $tidak_dijawab = 0;

    // change jumlah cuz $jawapan_murid jawapan can be less than total soalan
    // get soalan total
    $jumlah = (int)$conn->query("SELECT COUNT(s_id) as jumlah FROM soalan WHERE s_kuiz = '{$id_kuiz}'")->fetch_assoc()['jumlah'];
    $peratus = 0;

    foreach ($jawapan_murid as $i => $j) {

        $id_soalan = $j['jm_soalan'];
        $id_jawapan = $j['jm_jawapan'];

        if ($id_jawapan === NULL) {

            ++$tidak_dijawab;
            continue;
        }

        $jawapan_betul = isJawapantoSoalan($id_jawapan, $id_soalan);

        if ($jawapan_betul) ++$betul;
        else ++$salah;
    }

    $peratus = ($betul / $jumlah) * 100;

    return [
        'betul' => $betul,
        'salah' => $salah,
        'tidak_dijawab' => $tidak_dijawab,
        'jumlah' => $jumlah,
        'peratus' => round($peratus, 2)
    ];
}

// $jm = getJawapanMurid( '15', '33' );
// $skor_murid = countSkorMurid( $jm );
// var_dump( $skor_murid );

function getJawapanMurid($id_murid, $id_kuiz)
{

    global $conn;
    // echo $id_kuiz;
    $kuiz = getKuizById($id_kuiz);
    $soalan_list = getSoalanByKuiz($kuiz['kz_id']);
    // var_dump( $soalan_list );
    $id_soalan = array_map(function ($soalan) {
        return $soalan['s_id'];
    }, $soalan_list);
    $jm_list = [];
    // var_dump( $id_soalan );

    foreach ($id_soalan as $id) {

        // echo $id."<br>";
        $query = "SELECT * FROM jawapan_murid WHERE jm_murid = {$id_murid} AND jm_soalan = {$id}";
        // echo $query;

        if ($stmt = $conn->prepare($query)) {

            // $stmt->bind_param( 'ss', $id_murid, $id );
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res->num_rows > 0) {

                // while( $jm = $res->fetch_assoc() ) array_push( $jm_list , $jm );
                array_push($jm_list, $res->fetch_assoc());
            }
        }
        // var_dump( $jm_list );

    }

    return !empty($jm_list) ? $jm_list : false;
}

function registerSkorMurid($id_murid, $id_kuiz, $skor)
{

    global $conn;
    $query = "INSERT INTO skor_murid(sm_murid, sm_kuiz, sm_skor) VALUES(?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('sss', $id_murid, $id_kuiz, $skor);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $stmt->insert_id;
    }

    return false;
}

function registerJawapanMurid($id_murid, $id_soalan, $id_jawapan)
{

    global $conn;
    $betul = isJawapanToSoalan($id_jawapan, $id_soalan);
    $query = "INSERT INTO jawapan_murid(jm_murid, jm_soalan, jm_jawapan, jm_status) 
              VALUES (?,?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ssss', $id_murid, $id_soalan, $id_jawapan, $betul);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $betul ? true : false;
    }

    return null;
}

/* GURU */

/**
 * Login menggunakan No. Kad Pengenalan(nokp) dan Katalaluan(password) guru
 * @param string $nokp No. Kad Pengenalan
 * @param string $password Katalaluan
 * @return array|void Tatasusunan guru jika berjaya sebaliknya void
 */
function loginGuru($nokp, $password)
{

    global $conn;
    $table = 'guru';
    $col_1 = 'g_nokp';
    $col_2 = 'g_katalaluan';
    $query = "SELECT * FROM {$table} WHERE {$col_1} = ? AND {$col_2} = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $nokp, $password);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            return $res->fetch_assoc();
        }
    }

    return;
}

/**
 * Daftar guru baru
 * @param string $nokp No. KP guru. 12 aksara
 * @param string $nama Nama Guru
 * @param string $katalaluan Katalaluan guru
 * @param string $jenis enum('guru','admin'). guru default
 * @return bool TRUE jika berjaya, FALSE jika gagal.
 */
function registerGuru(string $nokp, string $nama, string $katalaluan, string $jenis = 'guru')
{

    global $conn;
    $query = "INSERT INTO guru(g_nokp, g_nama, g_katalaluan, g_jenis) VALUE(?,?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ssss', $nokp, $nama, $katalaluan, $jenis);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return true;
    }

    return false;
}

/**
 * Kemaskini data guru
 * @param int $id_guru ID Guru
 * @param string $nnokp No. KP Baru
 * @param string $nnama Nama Baru
 * @param string $nkatalaluan Katalaluan Baru
 * @param string $njenis Jenis Guru Baru
 * @return bool TRUE jika berjaya, FALSE jika gagal.
 */
function updateGuru(int $id_guru, string $nnokp, string $nnama, string $nkatalaluan, string $njenis)
{

    global $conn;
    $query = "UPDATE guru SET g_nokp = ?, g_nama = ?, g_katalaluan = ?, g_jenis = ? WHERE g_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('sssss', $nnokp, $nnama, $nkatalaluan, $njenis, $id_guru);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt) return true;
    }

    return false;
}

/**
 * Dapatkan senarai guru
 * @param int $limit Had carian
 * @param int $offset Titik mula carian
 * @return array|void Senarai Guru
 */
function getGuruList(int $limit = 10, int $offset = 0)
{

    global $conn;
    $query = "SELECT * FROM guru LIMIT {$limit} OFFSET {$offset}";
    $res = $conn->query($query);
    $guru_list = [];

    if ($res->num_rows > 0) {

        # simpan guru
        while ($guru = $res->fetch_assoc()) array_push($guru_list, $guru);
    }

    return $guru_list;
}

/**
 * Dapatkan data guru menggunakan ID Guru($id)
 * @param int $id ID Guru
 * @return array|void Tatasusunan guru jika berjaya, void sebaliknya.
 */
function getGuru($id)
{

    global $conn;
    $table = 'guru';
    $col_1 = 'g_id';
    $query = "SELECT * FROM {$table} WHERE {$col_1} = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            return $res->fetch_assoc();
        } else {

            return;
        }
    } else {

        return;
    }
}

/**
 * Pengguna terkini ialah guru?
 * @return bool
 */
function isGuru()
{

    return isset($_SESSION['jenis']) && $_SESSION['jenis'] == 'guru';
}

/**
 * Pengguna terkini ialah admin?
 * @return bool
 */
function isAdmin()
{

    return isset($_SESSION['jenis']) && $_SESSION['jenis'] == 'admin';
}

/**
 * Set akses untuk guru/admin sahaja
 * Apabila gagal, mesej ralat dipaparkan, kemudian lokasi terkini ditukar
 * @param string $err_msg Mesej ralat
 * @param string $reroute Lokasi hendak ditukar
 * @return bool True jika akses adalah guru/admin
 */
function accessGuru($err_msg = '', $reroute = '/')
{

    if (!(isGuru() || isAdmin()))
        die(($err_msg != '' ? alert($err_msg) : '') . redirect($reroute));

    return true;
}

/**
 * Set akses untuk admin sahaja
 * Apabila gagal, mesej ralat dipaparkan, kemudian lokasi terkini ditukar
 * @param string $err_msg Mesej ralat
 * @param string $reroute Lokasi hendak ditukar
 * @return bool True jika akses adalah admin
 */
function accessAdmin($err_msg = '', $reroute = '/')
{

    if (!(isAdmin()))
        die(($err_msg != '' ? alert($err_msg) : '') . redirect($reroute));

    return true;
}

/* KELAS */
/**
 * Daftar kelas baru
 * @param string $nama Nama kelas baru
 * @return bool TRUE jika berjaya, FALSE sebaliknya
 */
function registerKelas(string $nama)
{

    global $conn;
    $query = "INSERT INTO kelas(k_nama) VALUE(?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $nama);
        $stmt->execute();

        if ($stmt && !$stmt->errno) return true;
    }

    return false;
}

/**
 * Dapat senarai kelas
 * @param int|null $id_guru Mencari kuiz jika set, sebaliknya cari keseluruhan kelas
 * @param int $limit Had carian kelas
 * @param int $offset Titik mula carian
 * @return array|void Senarai kuiz hasil carian, void jika gagal.
 */
function getKelasList(int $limit = 10, int $offset = 0)
{

    global $conn;
    $query = "SELECT * FROM kelas ";

    if ($stmt = $conn->prepare($query)) {

        $stmt->execute();
        $res = $stmt->get_result();
        $kelas_list = [];

        if ($res->num_rows > 0) {

            # simpan semua kelas ke dalam kelas list
            while ($kelas = $res->fetch_assoc()) {

                array_push($kelas_list, $kelas);
            }
        }

        return $kelas_list;
    }

    return;
}

/**
 * Dapatkan data kelas berdasarkan idkelas
 * @param int $id_kelas ID Kelas
 * @return array|void Data kelas
 */
function getKelasById(int $id_kelas)
{

    global $conn;
    $col_1 = 'k_id';
    $query = "SELECT * FROM kelas WHERE {$col_1} = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_kelas);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            return $res->fetch_assoc();
        }
    }

    return;
}

/**
 * Daftar tingkatan baru
 * @param int $ting Tingkatan. 1-5.
 * @param int $kelas ID Kelas
 * @param int $guru ID Guru
 * @return bool TRUE jika berjaya, FALSE sebaliknya
 */
function registerTing(int $ting, int $kelas, int $guru)
{

    global $conn;
    $query = "INSERT INTO kelas_tingkatan(kt_ting, kt_kelas, kt_guru) VALUE(?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('iii', $ting, $kelas, $guru);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return true;
    }

    return false;
}

/**
 * Dapatkan senarai tingkatan
 * @param int $limit Had carian
 * @param int $offset Titik mula carian
 * @return array Senarai tingkatan
 */
function getTingList(int $limit = 10, int $offset = 0)
{

    global $conn;
    $tambahan = $limit <= 0 ? '' : ' LIMIT ' . $limit . ' OFFSET ' . $offset;
    $query = "SELECT * FROM kelas_tingkatan {$tambahan}";
    $res = $conn->query($query);
    $ting_list = [];

    if ($res->num_rows > 0) {

        # simpan ting
        while ($ting = $res->fetch_assoc()) array_push($ting_list, $ting);
    }

    return $ting_list;
}

/**
 * Dapatkan data tingkatan dengan id tingkatan
 * @param int $id_ting ID Tingkatan
 * @return array|void Data Tingkatan
 */
function getTingById(int $id_ting)
{

    global $conn;
    $col_1 = 'kt_id';
    $query = "SELECT * FROM kelas_tingkatan WHERE {$col_1} = '{$id_ting}'";
    $res = $conn->query($query);

    if ($res->num_rows > 0) {

        return $res->fetch_assoc();
    }

    return;
}

/**
 * Dapatkan senarai kelas yang diajari guru
 * @param int $id_guru ID Guru
 * @return array|void Senarai kelas yang diajari oleh guru
 */
function getTingByGuru(int $id_guru)
{

    global $conn;
    $col_1 = 'kt_guru';
    $query = "SELECT * FROM kelas_tingkatan WHERE {$col_1} = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_guru);
        $stmt->execute();
        $res = $stmt->get_result();
        $kelas_list = [];

        if ($res->num_rows > 0) {

            # simpan kelas
            while ($kelas = $res->fetch_assoc()) array_push($kelas_list, $kelas);
        }

        return $kelas_list;
    }

    return;
}

/**
 * Dapatkan jumlah murid dalam 1 kelas
 * @param int $id_kelas ID Kelas
 * @return int Jumlah murid dalam kelas
 */
function getKelasJumlah(int $id_kelas)
{

    global $conn;
    $col_1 = 'kt.kt_id';
    $query = "select count(m.m_id) as jumlah from murid as m, kelas_tingkatan as kt where kt.kt_id = m.m_kelas and {$col_1} = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_kelas);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            return (int)($res->fetch_assoc()['jumlah']);
        }
    }

    return 0;
}

/* KUIZ */
/**
 * Daftar kuiz baru
 * @param string $nama Nama Kuiz
 * @param int $guru ID Guru
 * @param int $id_kelas ID KELAS
 * @param string $tarikh Tarikh kuiz
 * @param string $jenis Jenis Kuiz. 'latihan' default.
 * @param int $masa Masa menjawab. null jika latihan.
 * @return int|bool ID Kuiz jika berjaya. FALSE jika gagal.
 */
function registerKuiz(string $nama, int $guru, int $id_ting, string $tarikh, string $jenis = 'latihan', ?int $masa = null)
{

    global $conn;
    $masa = ($jenis == 'kuiz' ? $masa : null);
    $query = "INSERT INTO kuiz(kz_nama, kz_guru, kz_ting, kz_tarikh, kz_jenis, kz_masa) VALUE (?,?,?,?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ssssss', $nama, $guru, $id_ting, $tarikh, $jenis, $masa);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $stmt->insert_id;
    }

    return false;
}

/**
 * Daftar kuiz baharu.
 * @param int $kuiz ID Kuiz
 * @param string $teks Teks Soalan
 * @param resource $image Resource Gambar.
 * @return int|bool ID Soalan jika berjaya. FALSE jika gagal.
 */
function registerSoalan(int $kuiz, string $teks, $image, $image_path = '')
{

    global $conn;
    $img_url = $image ? uploadImage($image, $image_path) : NULL;
    $query = "INSERT INTO soalan(s_kuiz, s_teks, s_gambar) VALUE (?,?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('sss', $kuiz, $teks, $img_url);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $stmt->insert_id;
    }

    return false;
}

/**
 * Daftar jawapan.
 * @param int $soalan ID Soalan
 * @param string $teks Teks Jawapan
 * @return int|bool ID Jawapan jika berjaya. FALSE jika gagal.
 */
function registerJawapan($soalan, $teks)
{

    global $conn;
    $query = "INSERT INTO jawapan(j_soalan, j_teks) VALUE(?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $soalan, $teks);
        $stmt->execute();

        if ($stmt && !$stmt->errno) return $stmt->insert_id;
    }

    return false;
}

/**
 * Daftar jawapan 'betul' bagi sesuatu soalan
 * @param int $soalan ID Soalan
 * @param int $jawapan ID Jawapan
 * @return int|bool ID SoalanJawapan jika berjaya. FALSE jika gagal.
 */
function registerSoalanJawapan($soalan, $jawapan)
{

    global $conn;
    $query = "INSERT INTO soalan_jawapan(sj_soalan, sj_jawapan) VALUE(?,?)";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $soalan, $jawapan);
        $stmt->execute();

        if ($stmt && !$stmt->errno) return $stmt->insert_id;
    }

    return false;
}

/**
 * Muat naik gambar
 * @param resource $img Resource gambar
 * @param string $path Lokasi simpanan gambar
 * @return string|null URL gambar
 */
function uploadImage($img, $path = '/images/')
{

    global $root;
    $filename = basename($img['name']);
    $ufilename = $filename;
    $tmp = $img['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $target_dir = $root . $path;


    $target_file = $target_dir . $filename;

    var_dump($target_file);


    while (file_exists($target_file)) {
        $ufilename = uniqid() . '.' . $ext;
        $target_file = $target_dir . $ufilename;
    }

    if (move_uploaded_file($tmp, $target_file)) {

        $url = $ufilename;

        return $url;
    }

    return null;
}

function updateKuiz($id_kuiz, $nama, $tarikh, $jenis, $masa)
{

    global $conn;
    $query = "UPDATE kuiz SET kz_nama = ?, kz_tarikh = ?, kz_jenis = ?, kz_masa = ? WHERE kz_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('sssss', $nama, $tarikh, $jenis, $masa, $id_kuiz);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $id_kuiz;
    }

    return false;
}

function updateSoalan($id_soalan, $teks)
{

    global $conn;
    // $gambar_url = $gambar ? uploadImage( $gambar ) : NULL;
    $query = "UPDATE soalan SET s_teks = ? WHERE s_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $teks, $id_soalan);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $id_soalan;
    }

    return false;
}

function updateJawapan($id_jawapan, $teks)
{

    global $conn;
    $query = "UPDATE jawapan SET j_teks = ? WHERE j_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $teks, $id_jawapan);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return $id_jawapan;
    }

    return false;
}

function updateSoalanJawapan($soalan, $jawapan)
{

    global $conn;
    $query = "UPDATE soalan_jawapan SET sj_jawapan = ? WHERE sj_soalan = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $jawapan, $soalan);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return true;
    }

    return false;
}

function deleteSoalan($id_soalan)
{

    global $conn;
    $query = "DELETE FROM soalan WHERE s_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_soalan);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt && !$stmt->errno) return 1;
    }

    return 0;
}

/**
 * Dapatkan senarai kuiz
 * @param int $limit Had bilangan kuiz
 * @param int $offset Titik permulaan pencarian data
 * @return array Tatasusunan data kuiz jika berjaya, tatasusunan kosong jika tidak
 */
function getKuizList(int $id_guru = null, int $limit = 10, int $offset = 0)
{

    global $conn;
    $col_1 = 'kz_guru';
    $tambahan = $id_guru ? " WHERE {$col_1} = ? " : '';
    $query = "SELECT * FROM kuiz {$tambahan} LIMIT {$limit} OFFSET {$offset}";

    if ($stmt = $conn->prepare($query)) {

        if ($id_guru) $stmt->bind_param('s', $id_guru);

        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            # simpan semua kuiz
            $kuiz_list = [];

            while ($kuiz = $res->fetch_assoc()) {

                array_push($kuiz_list, $kuiz);
            }

            return $kuiz_list;
        } else {

            return [];
        }
    }
}

function getKuizById(int $id_kuiz)
{

    global $conn;
    $query = "SELECT * FROM kuiz WHERE kz_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_kuiz);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {

            return $res->fetch_assoc();
        }
    }

    return false;
}

function getKuizByGuru(int $id_guru)
{

    global $conn;
    $query = "SELECT * FROM kuiz WHERE kz_guru = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_guru);
        $stmt->execute();
        $res = $stmt->get_result();
        $kuiz_list = [];

        if ($res->num_rows > 0) {

            #simpan kuiz
            while ($kuiz = $res->fetch_assoc()) array_push($kuiz_list, $kuiz);
        }

        return $kuiz_list;
    }

    return false;
}

function getKuizByTing(int $id_ting)
{

    global $conn;
    $query = "SELECT * FROM kuiz WHERE kz_ting = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_ting);
        $stmt->execute();
        $res = $stmt->get_result();
        $kuiz_list = [];

        if ($res->num_rows > 0) {

            #simpan kuiz
            while ($kuiz = $res->fetch_assoc()) array_push($kuiz_list, $kuiz);
        }

        return $kuiz_list;
    }

    return false;
}

function getSoalanByKuiz(int $id_kuiz)
{

    global $conn;
    $query = "SELECT * FROM soalan WHERE s_kuiz = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_kuiz);
        $stmt->execute();
        $res = $stmt->get_result();
        $soalan_list = [];

        if ($res->num_rows > 0) {

            #simpan soalan
            while ($soalan = $res->fetch_assoc()) array_push($soalan_list, $soalan);
        }

        return $soalan_list;
    }

    return false;
}

function getJawapanBySoalan(int $id_soalan)
{

    global $conn;
    $query = "SELECT * FROM jawapan WHERE j_soalan = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_soalan);
        $stmt->execute();
        $res = $stmt->get_result();
        $jawapan_list = [];

        if ($res->num_rows > 0) {

            #simpan jawapan
            while ($jawapan = $res->fetch_assoc()) array_push($jawapan_list, $jawapan);
        }

        return $jawapan_list;
    }

    return false;
}

function getJawapanToSoalan(int $id_soalan)
{

    global $conn;
    $query = "SELECT * FROM soalan_jawapan WHERE sj_soalan = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_soalan);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) return $res->fetch_assoc();
    }

    return false;
}

function getJawapanById(int $id_jawapan)
{

    global $conn;
    $query = "SELECT * FROM jawapan WHERE j_id = ?";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('s', $id_jawapan);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) return $res->fetch_assoc();
    }

    return false;
}

function isJawapanToSoalan($id_jawapan, $id_soalan)
{

    global $conn;

    if ($id_jawapan == NULL) return NULL;

    $query = "SELECT * FROM soalan_jawapan WHERE sj_soalan = ? AND sj_jawapan = ? LIMIT 1";

    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param('ss', $id_soalan, $id_jawapan);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) return 1;
    }

    return false;
}

/* MISCELLANEOUS */
/**
 * Memaparkan amaran(alert) Javascript
 * @param string $msg Mesej|Paparan yang dikehendaki
 * @return string Blok kod javascript
 */
function alert($msg)
{

    return "<script>alert('{$msg}')</script>";
}

/**
 * Menguji sesuatu kondisi. Jika palsu, maka sebuah 'error' dipaparkan
 * @param bool $condition Kondisi ingin diuji
 * @param string $fail_msg Mesej untuk percubaan yang gagal
 * @param bool $die Perlukah berhenti apabila gagal
 * @return void
 */
function _assert($condition, $fail_msg = '', $die = false)
{

    if (!$condition) {

        if ($die) die($fail_msg);
        else echo $fail_msg;
    }
}

/**
 * Menukar lokasi.
 * @param string location Lokasi yang ingin ditukar
 * @return string Blok kod Javascript
 */
function redirect($location)
{
    return "<script>window.location.href = '{$location}'</script>";
}

/**
 * Back javascript
 * @return string blok kod javascript
 */
function back()
{

    return "<script>window.history.back()</script>";
}

?>

<svg style="
position: absolute;
top: 0;
bottom: 0;
height: 100%;
" id="svg" viewBox="0 0 1440 600" xmlns="http://www.w3.org/2000/svg" class="transition duration-300 ease-in-out delay-150">
    <style>
        .path-0 {
            animation: pathAnim-0 30s linear alternate infinite;
            animation-timing-function: linear;
            animation-iteration-count: infinite;

        }

        @keyframes pathAnim-0 {
            0% {
                d: path("M 0,600 C 0,600 0,200 0,200 C 57.04102564102564,156.87948717948717 114.08205128205128,113.75897435897437 206,135 C 297.9179487179487,156.24102564102563 424.7128205128206,241.84358974358975 517,271 C 609.2871794871794,300.15641025641025 667.0666666666668,272.8666666666667 731,268 C 794.9333333333332,263.1333333333333 865.0205128205127,280.68974358974356 949,259 C 1032.9794871794873,237.31025641025641 1130.8512820512822,176.37435897435898 1215,160 C 1299.1487179487178,143.62564102564102 1369.5743589743588,171.8128205128205 1440,200 C 1440,200 1440,600 1440,600 Z");
            }

            25% {
                d: path("M 0,600 C 0,600 0,200 0,200 C 68.16153846153847,211.31538461538463 136.32307692307694,222.63076923076923 209,226 C 281.67692307692306,229.36923076923077 358.86923076923074,224.79230769230767 449,215 C 539.1307692307693,205.20769230769233 642.2000000000002,190.20000000000002 730,172 C 817.7999999999998,153.79999999999998 890.330769230769,132.40769230769232 963,129 C 1035.669230769231,125.59230769230768 1108.4769230769232,140.16923076923078 1188,155 C 1267.5230769230768,169.83076923076922 1353.7615384615383,184.9153846153846 1440,200 C 1440,200 1440,600 1440,600 Z");
            }

            50% {
                d: path("M 0,600 C 0,600 0,200 0,200 C 64.05641025641026,182.35641025641027 128.11282051282052,164.71282051282054 208,186 C 287.8871794871795,207.28717948717946 383.60512820512815,267.5051282051282 460,251 C 536.3948717948718,234.4948717948718 593.4666666666667,141.26666666666668 685,126 C 776.5333333333333,110.73333333333332 902.528205128205,173.42820512820512 998,184 C 1093.471794871795,194.57179487179488 1158.420512820513,153.0205128205128 1227,147 C 1295.579487179487,140.9794871794872 1367.7897435897435,170.4897435897436 1440,200 C 1440,200 1440,600 1440,600 Z");
            }

            75% {
                d: path("M 0,600 C 0,600 0,200 0,200 C 85.27435897435899,199.28717948717951 170.54871794871798,198.574358974359 256,179 C 341.451282051282,159.425641025641 427.07948717948716,120.98974358974357 513,137 C 598.9205128205128,153.01025641025643 685.1333333333334,223.4666666666667 746,240 C 806.8666666666666,256.5333333333333 842.3871794871794,219.14358974358973 922,188 C 1001.6128205128206,156.85641025641027 1125.3179487179489,131.95897435897436 1219,135 C 1312.6820512820511,138.04102564102564 1376.3410256410257,169.0205128205128 1440,200 C 1440,200 1440,600 1440,600 Z");
            }

            100% {
                d: path("M 0,600 C 0,600 0,200 0,200 C 57.04102564102564,156.87948717948717 114.08205128205128,113.75897435897437 206,135 C 297.9179487179487,156.24102564102563 424.7128205128206,241.84358974358975 517,271 C 609.2871794871794,300.15641025641025 667.0666666666668,272.8666666666667 731,268 C 794.9333333333332,263.1333333333333 865.0205128205127,280.68974358974356 949,259 C 1032.9794871794873,237.31025641025641 1130.8512820512822,176.37435897435898 1215,160 C 1299.1487179487178,143.62564102564102 1369.5743589743588,171.8128205128205 1440,200 C 1440,200 1440,600 1440,600 Z");
            }
        }
    </style>
    <path d="M 0,600 C 0,600 0,200 0,200 C 57.04102564102564,156.87948717948717 114.08205128205128,113.75897435897437 206,135 C 297.9179487179487,156.24102564102563 424.7128205128206,241.84358974358975 517,271 C 609.2871794871794,300.15641025641025 667.0666666666668,272.8666666666667 731,268 C 794.9333333333332,263.1333333333333 865.0205128205127,280.68974358974356 949,259 C 1032.9794871794873,237.31025641025641 1130.8512820512822,176.37435897435898 1215,160 C 1299.1487179487178,143.62564102564102 1369.5743589743588,171.8128205128205 1440,200 C 1440,200 1440,600 1440,600 Z" stroke="none" stroke-width="0" fill="#476bb588" class="transition-all duration-300 ease-in-out delay-150 path-0"></path>
    <style>
        .path-1 {
            animation: pathAnim-1 30s;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        @keyframes pathAnim-1 {
            0% {
                d: path("M 0,600 C 0,600 0,400 0,400 C 99.86410256410255,402.0230769230769 199.7282051282051,404.0461538461538 282,379 C 364.2717948717949,353.9538461538462 428.9512820512821,301.83846153846156 491,330 C 553.0487179487179,358.16153846153844 612.4666666666667,466.59999999999997 698,463 C 783.5333333333333,459.40000000000003 895.1820512820511,343.76153846153846 977,326 C 1058.8179487179489,308.23846153846154 1110.8051282051283,388.3538461538462 1183,417 C 1255.1948717948717,445.6461538461538 1347.5974358974358,422.8230769230769 1440,400 C 1440,400 1440,600 1440,600 Z");
            }

            25% {
                d: path("M 0,600 C 0,600 0,400 0,400 C 62.343589743589746,375.42051282051284 124.68717948717949,350.8410256410257 202,359 C 279.3128205128205,367.1589743589743 371.5948717948718,408.0564102564102 468,428 C 564.4051282051282,447.9435897435898 664.9333333333333,446.93333333333334 749,433 C 833.0666666666667,419.06666666666666 900.6717948717949,392.2102564102564 962,368 C 1023.3282051282051,343.7897435897436 1078.3794871794871,322.225641025641 1157,328 C 1235.6205128205129,333.774358974359 1337.8102564102564,366.88717948717954 1440,400 C 1440,400 1440,600 1440,600 Z");
            }

            50% {
                d: path("M 0,600 C 0,600 0,400 0,400 C 72.92051282051281,408.2307692307692 145.84102564102562,416.46153846153845 234,429 C 322.1589743589744,441.53846153846155 425.55641025641023,458.3846153846154 503,439 C 580.4435897435898,419.6153846153846 631.9333333333334,364.00000000000006 706,374 C 780.0666666666666,383.99999999999994 876.7102564102563,459.6153846153846 972,451 C 1067.2897435897437,442.3846153846154 1161.2256410256412,349.53846153846155 1239,327 C 1316.7743589743588,304.46153846153845 1378.3871794871793,352.2307692307692 1440,400 C 1440,400 1440,600 1440,600 Z");
            }

            75% {
                d: path("M 0,600 C 0,600 0,400 0,400 C 71.38205128205126,424.78717948717946 142.76410256410253,449.5743589743589 234,461 C 325.23589743589747,472.4256410256411 436.32564102564106,470.4897435897436 521,472 C 605.6743589743589,473.5102564102564 663.9333333333334,478.4666666666666 738,442 C 812.0666666666666,405.5333333333334 901.9410256410256,327.6435897435898 970,332 C 1038.0589743589744,336.3564102564102 1084.3025641025642,422.95897435897433 1159,448 C 1233.6974358974358,473.04102564102567 1336.8487179487179,436.52051282051286 1440,400 C 1440,400 1440,600 1440,600 Z");
            }

            100% {
                d: path("M 0,600 C 0,600 0,400 0,400 C 99.86410256410255,402.0230769230769 199.7282051282051,404.0461538461538 282,379 C 364.2717948717949,353.9538461538462 428.9512820512821,301.83846153846156 491,330 C 553.0487179487179,358.16153846153844 612.4666666666667,466.59999999999997 698,463 C 783.5333333333333,459.40000000000003 895.1820512820511,343.76153846153846 977,326 C 1058.8179487179489,308.23846153846154 1110.8051282051283,388.3538461538462 1183,417 C 1255.1948717948717,445.6461538461538 1347.5974358974358,422.8230769230769 1440,400 C 1440,400 1440,600 1440,600 Z");
            }
        }
    </style>
    <path d="M 0,600 C 0,600 0,400 0,400 C 99.86410256410255,402.0230769230769 199.7282051282051,404.0461538461538 282,379 C 364.2717948717949,353.9538461538462 428.9512820512821,301.83846153846156 491,330 C 553.0487179487179,358.16153846153844 612.4666666666667,466.59999999999997 698,463 C 783.5333333333333,459.40000000000003 895.1820512820511,343.76153846153846 977,326 C 1058.8179487179489,308.23846153846154 1110.8051282051283,388.3538461538462 1183,417 C 1255.1948717948717,445.6461538461538 1347.5974358974358,422.8230769230769 1440,400 C 1440,400 1440,600 1440,600 Z" stroke="none" stroke-width="0" fill="#476bb5ff" class="transition-all duration-300 ease-in-out delay-150 path-1"></path>
</svg>