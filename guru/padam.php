<?php

/**
 * Padam.
 */

require '../php/conn.php';

accessGuru();

_assert( $table = $_GET['table'], alert( 'Sila masukkan jadual!' ) . back(), 1 );
_assert( $col = $_GET['col'], alert( 'Sila masukkan medan!' ) . back(), 1 );
_assert( $val = $_GET['val'], alert( 'Sila masukkan nilai!' ) . back(), 1 );

$query = "DELETE FROM {$table} WHERE {$col} = '{$val}'";
$res = $conn->query( $query );

if( $res )
{

    echo alert( 'Data berjaya dipadam.' ) . ( isset( $_GET['redir'] ) ? redirect( $_GET['redir'] ) : back() );

}
else
{

    die( alert( 'Data gagal dipadam.' ) . back() );

}