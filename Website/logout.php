<?php
session_start();

session_destroy();
header( "refresh:.05;url=../index.php");
?>