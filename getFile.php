<?php
include("conf.php");
header('Content-Type: application/json; charset=utf-8');
$files = array_diff(scandir("".$dir."".$_GET['dir'].""), array('.', '..'));
$data['list'] = array();
$data['dir'] = "".$_GET['dir']."";
foreach($files as $value){
  if(is_dir("".$dir."".$_GET['dir']."".$value."")) {
    $data['list'][] = array(
      'is_file' => false,
      'name' => $value,
      'size' => filesize("".$dir."".$_GET['dir']."".$value."")
    );
  } else {
    $data['list'][] = array(
      'is_file' => true,
      'name' => $value,
      'size' => filesize("".$dir."".$_GET['dir']."".$value."")
    );
  }
}

echo json_encode($data);