<?php
session_start();
if (!isset($_SESSION['IDUser'])) {
    header('Location: index.php');
}
    $imageUrl = $_GET['imageURL'];
    
    $thumbFileNameAux = $imageUrl;
    $thumbMimeFileName = "image";
    $thumbTypeFileName = "jpeg";

    header( "Content-type: $thumbMimeFileName/$thumbTypeFileName");
    header( "Content-Length: " . filesize($thumbFileNameAux) );

    $thumbFileHandler = fopen( $thumbFileNameAux, 'rb' );
    fpassthru( $thumbFileHandler );

    fclose( $thumbFileHandler );
?>