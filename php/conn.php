<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nsejarah';

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
    echo $query;
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
 * Dapatkan data tingkatan dengan id tingkatan
 * @param int $id_ting ID Tingkatan
 * @return array|void Data Tingkatan
 */
function getKelasByTingId( int $id_ting )
{

    global $conn;
    $col_1 = 'kz_id';
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
function getKelasByGuru( int $id_guru )
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