<?php
session_start();
if(isset($_SESSION['user'])){
  include("conf.php");
  header('Content-Type: application/json; charset=utf-8');
  $file_name = "".$dir."".$_GET['dir']."";
  array_map('unlink', glob("$file_name/*.*"));
  rmdir($file_name);
  echo("done!");
}