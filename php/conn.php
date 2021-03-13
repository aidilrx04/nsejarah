<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nsejarah';

$root = realpath('../');

/**@var mysqli $conn Sambungan ke database */
$conn = mysqli_connect( $dbhost, $dbuser, $dbpass, $dbname );

if( mysqli_connect_errno() ) {
    die( 'Sambungan gagal: ' . mysqli_connect_errno() );
} else {
   # echo 'Sambungan berjaya';
}

# start session by default if not start yet
if( session_status() == PHP_SESSION_NONE )
{

    session_start();

}

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
function registerMurid( string $nokp, string $nama, string $katalaluan, int $kelas )
{

    global $conn;
    $query = "INSERT INTO murid(m_nokp, m_nama, m_katalaluan, m_kelas) VALUE (?,?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ssss', $nokp, $nama, $katalaluan, $kelas );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return true;

    }

    return false;

}

/**
 * Login murid menggunakan No. Kad Pengenalan($nokp) dan Katalaluan($password)
 * @param string $nokp No. Kad Pengenalan
 * @param string $password Katalaluan
 * @return array|void Tatasusunan murid jika berjaya void sebaliknya.
 */
function loginMurid( $nokp, $password )
{

    global $conn;
    $table = 'murid';
    $col_1 = 'm_nokp';
    $col_2 = 'm_katalaluan';

    $query = "SELECT * FROM {$table} WHERE {$col_1} = ? AND {$col_2} = ?";
    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ss', $nokp, $password );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) 
        {

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
function getMuridList( int $limit = 10, int $offset = 0 )
{

    global $conn;
    $tambahan = $limit <= 0 ? '' : ' LIMIT '. $limit . ' OFFSET ' . $offset;
    $query = "SELECT * FROM murid {$tambahan}";
    $murid_list = [];

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 )
        {

            # simpan data
            while( $murid = $res->fetch_assoc() ) array_push( $murid_list, $murid );

        }

    }
    return $murid_list;

}

/**
 * Dapatkan data murid
 * @param int $id_murid ID Murid
 * @return array|void Data murid
 */
function getMuridById( int $id_murid )
{

    global $conn;
    $col_1 = 'm_id';
    $query = "SELECT * FROM murid WHERE {$col_1} = '{$id_murid}'";
    $res = $conn->query( $query );

    if( $res->num_rows > 0 ) return $res->fetch_assoc();

    return;

}

function getMuridByTing( int $id_ting )
{

    global $conn;
    $query = "SELECT * FROM murid WHERE m_kelas = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_ting );
        $stmt->execute();
        $res = $stmt->get_result();
        $murid_list = [];

        if( $res->num_rows > 0 )
        {

            while( $murid = $res->fetch_assoc() ) array_push( $murid_list, $murid );

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
function updateMurid( int $id_murid, string $nnokp, string $nnama, string $nkatalaluan, int $nkelas )
{

    global $conn;
    $query = "UPDATE murid SET m_nokp = ?, m_nama = ?, m_katalaluan = ?, m_kelas = ? WHERE m_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'sssss', $nnokp, $nnama, $nkatalaluan, $nkelas, $id_murid );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt ) return true;

    }

    return false;

}

function isMurid()
{

    return $_SESSION['jenis'] == 'murid' ? true : false;

}

function accessMurid( $err_msg = '', $reroute = '/' )
{

    if( !( isMurid() ) ) 
        die( ( $err_msg != '' ? alert( $err_msg ) : '' ) . redirect( $reroute ) );

    return true;

}

/** SKOR_MURID */
function getSkorMurid( $id_skor )
{

    global $conn;
    $query = "SELECT * FROM skor_murid WHERE sm_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_skor );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) return $res->fetch_assoc();

    }

    return false;

}

function getSkorMuridByKuiz( $id_murid, $id_kuiz )
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

}

function getSkorByMurid( $id_murid, $id_kuiz )
{

    global $conn;
    $query = "SELECT * FROM skor_murid WHERE sm_murid = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_murid );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) return $res->fetch_assoc();

    }

    return false;

}

function getJawapanMurid( $id_murid, $id_kuiz )
{

    global $conn;
    // echo $id_kuiz;
    $kuiz = getKuizById( $id_kuiz );
    $soalan_list = getSoalanByKuiz( $kuiz['kz_id'] );
    // var_dump( $soalan_list );
    $id_soalan = array_map( function( $soalan ) { return $soalan['s_id']; }, $soalan_list );
    $jm_list = [];
    // var_dump( $id_soalan );

    foreach( $id_soalan as $id )
    {

        // echo $id."<br>";
        $query = "SELECT * FROM jawapan_murid WHERE jm_murid = {$id_murid} AND jm_soalan = {$id}";
        // echo $query;

        if( $stmt = $conn->prepare( $query ) )
        {

            // $stmt->bind_param( 'ss', $id_murid, $id );
            $stmt->execute();
            $res = $stmt->get_result();

            if( $res->num_rows > 0 )
            {

                // while( $jm = $res->fetch_assoc() ) array_push( $jm_list , $jm );
                array_push( $jm_list, $res->fetch_assoc() );

            }

        }
        // var_dump( $jm_list );

    }

    return !empty( $jm_list ) ? $jm_list : false;

}

function registerSkorMurid( $id_murid, $id_kuiz, $skor )
{

    global $conn;
    $query = "INSERT INTO skor_murid(sm_murid, sm_kuiz, sm_skor) VALUES(?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'sss', $id_murid, $id_kuiz, $skor );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return $stmt->insert_id;

    }

    return false;

}

function registerJawapanMurid( $id_murid, $id_soalan, $id_jawapan )
{

    global $conn;
    $betul = isJawapanToSoalan( $id_jawapan, $id_soalan );
    $query = "INSERT INTO jawapan_murid(jm_murid, jm_soalan, jm_jawapan, jm_status) 
              VALUES (?,?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ssss', $id_murid, $id_soalan, $id_jawapan, $betul );
        $stmt->execute();
        $stmt->store_result();
        
        if( $stmt && !$stmt->errno ) return $betul ? true : false;

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
function loginGuru( $nokp, $password )
{

    global $conn;
    $table = 'guru';
    $col_1 = 'g_nokp';
    $col_2 = 'g_katalaluan';
    $query = "SELECT * FROM {$table} WHERE {$col_1} = ? AND {$col_2} = ?";

    if( $stmt = $conn->prepare($query) )
    {

        $stmt->bind_param( 'ss', $nokp, $password );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 )
        {

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
function registerGuru( string $nokp, string $nama, string $katalaluan, string $jenis = 'guru' )
{

    global $conn;
    $query = "INSERT INTO guru(g_nokp, g_nama, g_katalaluan, g_jenis) VALUE(?,?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ssss', $nokp, $nama, $katalaluan, $jenis );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return true;

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
function updateGuru( int $id_guru, string $nnokp, string $nnama, string $nkatalaluan, string $njenis )
{

    global $conn;
    $query = "UPDATE guru SET g_nokp = ?, g_nama = ?, g_katalaluan = ?, g_jenis = ? WHERE g_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'sssss', $nnokp, $nnama, $nkatalaluan, $njenis, $id_guru );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt ) return true;

    }

    return false;

}

/**
 * Dapatkan senarai guru
 * @param int $limit Had carian
 * @param int $offset Titik mula carian
 * @return array|void Senarai Guru
 */
function getGuruList( int $limit = 10, int $offset = 0 )
{

    global $conn;
    $query = "SELECT * FROM guru LIMIT {$limit} OFFSET {$offset}";
    $res = $conn->query( $query );
    $guru_list = [];

    if( $res->num_rows > 0 )
    {

        # simpan guru
        while( $guru = $res->fetch_assoc() ) array_push( $guru_list, $guru );

    }

    return $guru_list;

}

/**
 * Dapatkan data guru menggunakan ID Guru($id)
 * @param int $id ID Guru
 * @return array|void Tatasusunan guru jika berjaya, void sebaliknya.
 */
function getGuru( $id )
{

    global $conn;
    $table = 'guru';
    $col_1 = 'g_id';
    $query = "SELECT * FROM {$table} WHERE {$col_1} = ?";

    if( $stmt = $conn->prepare($query) )
    {

        $stmt->bind_param( 's', $id );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 )
        {

            return $res->fetch_assoc();

        }
        else
        {
            
            return;

        }

    }
    else
    {

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
function accessGuru( $err_msg = '', $reroute = '/' )
{

    if( !( isGuru() || isAdmin() ) ) 
        die( ( $err_msg != '' ? alert( $err_msg ) : '' ) . redirect( $reroute ) );

    return true;

}

/**
 * Set akses untuk admin sahaja
 * Apabila gagal, mesej ralat dipaparkan, kemudian lokasi terkini ditukar
 * @param string $err_msg Mesej ralat
 * @param string $reroute Lokasi hendak ditukar
 * @return bool True jika akses adalah admin
 */
function accessAdmin( $err_msg = '', $reroute = '/' )
{

    if( !( isAdmin() ) ) 
        die( ( $err_msg != '' ? alert( $err_msg ) : '' ) . redirect( $reroute ) );

    return true;
    
}

/* KELAS */
/**
 * Daftar kelas baru
 * @param string $nama Nama kelas baru
 * @return bool TRUE jika berjaya, FALSE sebaliknya
 */
function registerKelas( string $nama )
{

    global $conn;
    $query = "INSERT INTO kelas(k_nama) VALUE(?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $nama );
        $stmt->execute();

        if( $stmt && !$stmt->errno ) return true;

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
function getKelasList( int $limit = 10, int $offset = 0 )
{

    global $conn;
    $query = "SELECT * FROM kelas ";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->execute();
        $res = $stmt->get_result();
        $kelas_list = [];

        if( $res->num_rows > 0 )
        {

            # simpan semua kelas ke dalam kelas list
            while( $kelas = $res->fetch_assoc() )
            {

                array_push( $kelas_list, $kelas );

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
function getKelasById( int $id_kelas )
{

    global $conn;
    $col_1 = 'k_id';
    $query = "SELECT * FROM kelas WHERE {$col_1} = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_kelas );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 )
        {

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
function registerTing( int $ting, int $kelas, int $guru )
{

    global $conn;
    $query = "INSERT INTO kelas_tingkatan(kt_ting, kt_kelas, kt_guru) VALUE(?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'iii', $ting, $kelas, $guru );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return true;

    }
    
    return false;

}

/**
 * Dapatkan senarai tingkatan
 * @param int $limit Had carian
 * @param int $offset Titik mula carian
 * @return array Senarai tingkatan
 */
function getTingList( int $limit = 10, int $offset = 0)
{

    global $conn;
    $tambahan = $limit <= 0 ? '' : ' LIMIT ' . $limit . ' OFFSET ' . $offset;
    $query = "SELECT * FROM kelas_tingkatan {$tambahan}";
    $res = $conn->query( $query );
    $ting_list = [];

    if( $res->num_rows > 0 )
    {

        # simpan ting
        while( $ting = $res->fetch_assoc() ) array_push( $ting_list, $ting );

    }

    return $ting_list;
}

/**
 * Dapatkan data tingkatan dengan id tingkatan
 * @param int $id_ting ID Tingkatan
 * @return array|void Data Tingkatan
 */
function getTingById( int $id_ting )
{

    global $conn;
    $col_1 = 'kt_id';
    $query = "SELECT * FROM kelas_tingkatan WHERE {$col_1} = '{$id_ting}'";
    $res = $conn->query( $query );

    if( $res->num_rows > 0 )
    {

        return $res->fetch_assoc();

    }
    
    return;

}

/**
 * Dapatkan senarai kelas yang diajari guru
 * @param int $id_guru ID Guru
 * @return array|void Senarai kelas yang diajari oleh guru
 */
function getTingByGuru( int $id_guru )
{

    global $conn;
    $col_1 = 'kt_guru';
    $query = "SELECT * FROM kelas_tingkatan WHERE {$col_1} = ?";
    
    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_guru );
        $stmt->execute();
        $res = $stmt->get_result();
        $kelas_list = [];

        if( $res->num_rows > 0 )
        {

            # simpan kelas
            while( $kelas = $res->fetch_assoc() ) array_push( $kelas_list, $kelas );

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
function getKelasJumlah( int $id_kelas )
{

    global $conn;
    $col_1 = 'kt.kt_id';
    $query = "select count(m.m_id) as jumlah from murid as m, kelas_tingkatan as kt where kt.kt_id = m.m_kelas and {$col_1} = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_kelas );
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows > 0)
        {

            return (int)( $res->fetch_assoc()['jumlah'] );

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
function registerKuiz( string $nama, int $guru, int $id_ting, string $tarikh, string $jenis = 'latihan', ?int $masa = null )
{

    global $conn;
    $masa = ( $jenis == 'kuiz' ? $masa : null );
    $query = "INSERT INTO kuiz(kz_nama, kz_guru, kz_ting, kz_tarikh, kz_jenis, kz_masa) VALUE (?,?,?,?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ssssss', $nama, $guru, $id_ting, $tarikh, $jenis, $masa );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return $stmt->insert_id;

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
function registerSoalan( int $kuiz, string $teks, $image )
{

    global $conn;
    $img_url = $image ? uploadImage( $image ) : NULL;
    $query = "INSERT INTO soalan(s_kuiz, s_teks, s_gambar) VALUE (?,?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'sss', $kuiz, $teks, $img_url );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return $stmt->insert_id;

    }

    return false;

}

/**
 * Daftar jawapan.
 * @param int $soalan ID Soalan
 * @param string $teks Teks Jawapan
 * @return int|bool ID Jawapan jika berjaya. FALSE jika gagal.
 */
function registerJawapan( $soalan, $teks )
{

    global $conn;
    $query = "INSERT INTO jawapan(j_soalan, j_teks) VALUE(?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ss', $soalan, $teks );
        $stmt->execute();

        if( $stmt && !$stmt->errno ) return $stmt->insert_id;

    }

    return false;

}

/**
 * Daftar jawapan 'betul' bagi sesuatu soalan
 * @param int $soalan ID Soalan
 * @param int $jawapan ID Jawapan
 * @return int|bool ID SoalanJawapan jika berjaya. FALSE jika gagal.
 */
function registerSoalanJawapan( $soalan, $jawapan )
{

    global $conn;
    $query = "INSERT INTO soalan_jawapan(sj_soalan, sj_jawapan) VALUE(?,?)";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ss', $soalan, $jawapan );
        $stmt->execute();
        
        if( $stmt && !$stmt->errno ) return $stmt->insert_id;

    }

    return false;

}

/**
 * Muat naik gambar
 * @param resource $img Resource gambar
 * @param string $path Lokasi simpanan gambar
 * @return string|null URL gambar
 */
function uploadImage( $img, $path = '/images/' )
{

    global $root;
    $filename = basename( $img['name'] );
    $ufilename = $filename;
    $tmp = $img['tmp_name'];
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );
    $target_dir = $root . $path;

    $target_file = $target_dir . $filename;

    while( file_exists( $target_file ) ) 
    {
        $ufilename = uniqid() . '.' . $ext;
        $target_file = $target_dir . $ufilename;

    }

    if( move_uploaded_file( $tmp, $target_file ) )
    {

        $url = '/images/' . $ufilename;

        return $url;

    }

    return null;

}

function updateKuiz( $id_kuiz, $nama, $tarikh, $jenis, $masa )
{

    global $conn;
    $query = "UPDATE kuiz SET kz_nama = ?, kz_tarikh = ?, kz_jenis = ?, kz_masa = ? WHERE kz_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'sssss', $nama, $tarikh, $jenis, $masa, $id_kuiz );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return $id_kuiz;

    }

    return false;

}

function updateSoalan( $id_soalan, $teks, $gambar )
{

    global $conn;
    $gambar_url = $gambar ? uploadImage( $gambar ) : NULL;
    $query = "UPDATE soalan SET s_teks = ?, s_gambar = ? WHERE s_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'sss', $teks, $gambar_url, $id_soalan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return $id_soalan;

    }

    return false;

}

function updateJawapan( $id_jawapan, $teks )
{

    global $conn;
    $query = "UPDATE jawapan SET j_teks = ? WHERE j_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 'ss', $teks, $id_jawapan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return $id_jawapan;

    }

    return false;

}

function updateSoalanJawapan( $soalan, $jawapan )
{

    global $conn;
    $query = "UPDATE soalan_jawapan SET sj_jawapan = ? WHERE sj_soalan = ?";

    if( $stmt = $conn->prepare( $query ) )   
    {

        $stmt->bind_param( 'ss', $jawapan, $soalan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return true;

    }

    return false;

}

function deleteSoalan( $id_soalan )
{

    global $conn;
    $query = "DELETE FROM soalan WHERE s_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_soalan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt && !$stmt->errno ) return 1;

    }

    return 0;

}

/**
 * Dapatkan senarai kuiz
 * @param int $limit Had bilangan kuiz
 * @param int $offset Titik permulaan pencarian data
 * @return array Tatasusunan data kuiz jika berjaya, tatasusunan kosong jika tidak
 */
function getKuizList( int $id_guru = null, int $limit = 10, int $offset = 0 )
{

    global $conn;
    $col_1 = 'kz_guru';
    $tambahan = $id_guru ? " WHERE {$col_1} = ? " : '';
    $query = "SELECT * FROM kuiz {$tambahan} LIMIT {$limit} OFFSET {$offset}";

    if( $stmt = $conn->prepare( $query ) )
    {

        if( $id_guru ) $stmt->bind_param( 's', $id_guru );

        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 )
        {

            # simpan semua kuiz
            $kuiz_list = [];
            
            while( $kuiz = $res->fetch_assoc() )
            {
                
                array_push($kuiz_list, $kuiz);

            }

            return $kuiz_list;

        }
        else
        {

            return [];

        }

    }

}

function getKuizById( int $id_kuiz )
{

    global $conn;
    $query = "SELECT * FROM kuiz WHERE kz_id = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_kuiz );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 )
        {

            return $res->fetch_assoc();

        }

    }

    return false;

}

function getKuizByGuru( int $id_guru )
{

    global $conn;
    $query = "SELECT * FROM kuiz WHERE kz_guru = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_guru );
        $stmt->execute();
        $res = $stmt->get_result();
        $kuiz_list = [];

        if( $res->num_rows > 0 )
        {

            #simpan kuiz
            while( $kuiz = $res->fetch_assoc() ) array_push( $kuiz_list, $kuiz );

        }

        return $kuiz_list;
        
    }

    return false;

}

function getKuizByTing( int $id_ting )
{

    global $conn;
    $query = "SELECT * FROM kuiz WHERE kz_ting = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_ting );
        $stmt->execute();
        $res = $stmt->get_result();
        $kuiz_list = [];

        if( $res->num_rows > 0 )
        {

            #simpan kuiz
            while( $kuiz = $res->fetch_assoc() ) array_push( $kuiz_list, $kuiz );

        }

        return $kuiz_list;

    }

    return false;

}

function getSoalanByKuiz( int $id_kuiz )
{

    global $conn;
    $query = "SELECT * FROM soalan WHERE s_kuiz = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_kuiz );
        $stmt->execute();
        $res = $stmt->get_result();
        $soalan_list = [];

        if( $res->num_rows > 0 )
        {

            #simpan soalan
            while( $soalan = $res->fetch_assoc() ) array_push( $soalan_list, $soalan );

        }

        return $soalan_list;

    }

    return false;

}

function getJawapanBySoalan( int $id_soalan )
{

    global $conn;
    $query = "SELECT * FROM jawapan WHERE j_soalan = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_soalan );
        $stmt->execute();
        $res = $stmt->get_result();
        $jawapan_list = [];

        if( $res->num_rows > 0 )
        {

            #simpan jawapan
            while( $jawapan = $res->fetch_assoc() ) array_push( $jawapan_list, $jawapan );

        }

        return $jawapan_list;

    }

    return false;

}

function getJawapanToSoalan( int $id_soalan )
{

    global $conn;
    $query = "SELECT * FROM soalan_jawapan WHERE sj_soalan = ?";

    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $id_soalan );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) return $res->fetch_assoc();

    }

    return false;

}

function isJawapanToSoalan( $id_jawapan, $id_soalan )
{

    global $conn;
    $query = "SELECT * FROM soalan_jawapan WHERE sj_soalan = ? AND sj_jawapan = ? LIMIT 1";

    if( $stmt = $conn->prepare( $query ) ) 
    {

        $stmt->bind_param( 'ss', $id_soalan, $id_jawapan );
        $stmt->execute();
        $stmt->store_result();

        if( $stmt->num_rows > 0 ) return 1;

    }

    return false;

}

/* MISCELLANEOUS */
/**
 * Memaparkan amaran(alert) Javascript
 * @param string $msg Mesej|Paparan yang dikehendaki
 * @return string Blok kod javascript
 */
function alert( $msg ) 
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
function _assert( $condition, $fail_msg = '', $die = false )
{

    if( !$condition )
    {

        if( $die ) die( $fail_msg );
        else echo $fail_msg;

    }

}

/**
 * Menukar lokasi.
 * @param string location Lokasi yang ingin ditukar
 * @return string Blok kod Javascript
 */
function redirect( $location )
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