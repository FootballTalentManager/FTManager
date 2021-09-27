<?php
if(!isset($_SESSION))
    session_start();
session_destroy();
header('Refresh: 1; index.php');
