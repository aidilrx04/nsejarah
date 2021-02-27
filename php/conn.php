<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'nsejarah';

$conn = mysqli_connect( $dbhost, $dbuser, $dbpass, $dbname );

if( mysqli_connect_errno() ) {
    die( 'Sambungan gagal: ' . mysqli_connect_errno() );
} else {
   # echo 'Sambungan berjaya';
}

/* 
    USERS
*/

function login( $nokp, $password )
{

    global $conn;
    $table = 'murid';
    $col_1 = 'm_nokp';
    $col_2 = 'm_katalaluan';

    $query = "SELECT * FROM {$table} WHERE {$col_1} = ?";
    if( $stmt = $conn->prepare( $query ) )
    {

        $stmt->bind_param( 's', $nokp );
        $stmt->execute();
        $res = $stmt->get_result();

        if( $res->num_rows > 0 ) 
        {

            $user = $res->fetch_assoc();

            if( $user[$col_2] == $password )
            {

                return $user;

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

}

/* GURU */
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
            
            return [];

        }

    }
    else
    {

        return [];

    }

}

/* KUIZ */

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

function alert( $msg ) 
{

    return "<script>alert('{$msg}')</script>";

}