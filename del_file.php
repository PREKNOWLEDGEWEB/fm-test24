<?php
session_start();
if(isset($_SESSION['user'])){
  include("conf.php");
  header('Content-Type: application/json; charset=utf-8');
  $file_name = "".$dir."".$_GET['dir']."";
  
  unlink($file_name);
  echo("resp");
}