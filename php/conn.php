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

/* KUIZ */
/**
 * Dapatkan senarai kuiz
 * @param int $limit Had bilangan kuiz
 * @param int $offset Titik permulaan pencarian data
 * @return array Tatasusunan data kuiz jika berjaya, tatasusunan kosong jika tidak
 */
function getKuizList( int $limit = 10, int $offset = 0 )
{

    global $conn;
    $query = "SELECT * FROM kuiz LIMIT {$limit} OFFSET {$offset}";

    if( $stmt = $conn->prepare( $query ) )
    {

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