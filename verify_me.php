<?php
session_start();
include("conf.php");
if(isset($_GET['user'])){
  if($_GET['user'] == $admin_user){
    if($_GET['pass'] == $admin_pass){
      echo("yeah");
      $_SESSION['user'] = true;
    }else{
      echo("no");
    }
  }else{
    echo("no");
  }
}else{
  echo("no");
}