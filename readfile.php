<?php
session_start();
if(isset($_SESSION['user'])){
  include("conf.php");
  header('Content-Type: application/json; charset=utf-8');
  $file_name = "".$dir."".$_GET['dir']."";
  $myfile = fopen($file_name, "r") or die("Unable to open file!");
  echo fread($myfile,filesize($file_name));
  fclose($myfile);
}