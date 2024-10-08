<?php
session_start();
//destroying all the session
if(session_destroy()) {
header("Location: header.php");
//kembali ke halaman utama
}
?>