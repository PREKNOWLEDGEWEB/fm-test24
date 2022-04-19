<?php session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
    <title>CFM</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .spinner {
        height: 60px;
        width: 60px;
        margin: auto;
        display: flex;
        position: absolute;
        -webkit-animation: rotation .6s infinite linear;
        -moz-animation: rotation .6s infinite linear;
        -o-animation: rotation .6s infinite linear;
        animation: rotation .6s infinite linear;
        border-left: 6px solid rgba(0, 174, 239, .15);
        border-right: 6px solid rgba(0, 174, 239, .15);
        border-bottom: 6px solid rgba(0, 174, 239, .15);
        border-top: 6px solid rgba(0, 174, 239, .8);
        border-radius: 100%;
      }
      
      @-webkit-keyframes rotation {
        from {
          -webkit-transform: rotate(0deg);
        }
        to {
          -webkit-transform: rotate(359deg);
        }
      }
      
      @-moz-keyframes rotation {
        from {
          -moz-transform: rotate(0deg);
        }
        to {
          -moz-transform: rotate(359deg);
        }
      }
      
      @-o-keyframes rotation {
        from {
          -o-transform: rotate(0deg);
        }
        to {
          -o-transform: rotate(359deg);
        }
      }
      
      @keyframes rotation {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(359deg);
        }
      }
      
      #overlay {
        position: absolute;
        display: none;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0,0,0,0.5);
        z-index: 2;
      }
    </style>
  </head>
  <body>
    <div id="overlay" class="justify-content-center align-items-center">
      <div id="justify-content-center align-items-center">
          <div class="spinner"></div>
      </div>
    </div>
    <?php if(isset($_SESSION['user'])){ ?>
      <?php include("inc/dash.php"); ?>
    <?php }else{ ?>
      <?php include("inc/login.php"); ?>
    <?php } ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      function login_in(){
        on();
        $.ajax({
          url:`./verify_me.php?user=${$('#emailaddr').val()}&pass=${$('#password').val()}`,
          success:function(data) {
            off();
            console.log(data);
            if(data == "no") setAlert({ type:"danger",message:"Invalid Creditnals" });
            if(data == "yeah") window.location.reload();
            off();
          }
        });
      }
      function on() {
        document.getElementById("overlay").style.display = "flex";
      }
      
      function off() {
        document.getElementById("overlay").style.display = "none";
      }
      function setAlert(opt){
  			$('#alert_area').show();
  			$('#alert_area').html(`
  				<div class="alert alert-${opt.type} alert-dismissible fade show" role="alert">
  				  ${opt.message}
  				  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  				</div>
  			`);
  			if(opt.type=="danger") toastr.error(opt.message);
  			if(opt.type=="success") toastr.success(opt.message);
  			
  		}
      var current_dir;
      var prev_dir;
      var is_file;
      <?php if(isset($_SESSION['user'])){ ?>
        on();
        aopen("./");
        function aopen(dir){
          prev_dir = current_dir;
          on();
          var img = "";
          $.ajax({
            url:`./getFile.php?dir=${dir}`,
            success:function(data) {
              off();
              console.log(data);
              $('#current_file').html(data.dir);
              current_dir = data.dir;
              $('#files').html("");
              if(data.list.length == 0) $('#files').html("<p>No files found</p>");
              $.each(data.list,(index,value) => {
                console.log(value);
                if(value.is_file == true){
                  img = "./icons/unknown.png";
                  is_file = false;
                }else{
                  img = "./icons/folder.png";
                  is_file = true
                }
                addRow({
                  id:"#files",
                  title:`${value.name}`,
                  desc:`${value.size} Bytes`,
                  image:img,
                  is_folder:is_file
                });
              })
            }
          }); 
        }

        function backdir(){
          aopen(prev_dir);
        }

        function readFile(txt){
          on();
          $.ajax({
            url:`./readfile.php?dir=${txt}`,
            dataType : "text",
            contentType: "application/text; charset=utf-8",
            success:function(data) {
              off();
              $('#files').html(data);
            }
          });
        }

        function fold_remove(name){
          $.ajax({
            url:`./delFold.php?dir=${name}`,
            dataType : "text",
            contentType: "application/text; charset=utf-8",
            success:function(data) {
              off();
              window.location.reload();
            }
          });
        }
                                         
        function file_remove(name){
          on();
          setTimeout(() => {
            window.location.reload();                   
          },1200);
          //return console.log(name);
          $.ajax({
            url:`./del_file.php?dir=${name}`,
            success:function(data) {
              off();
              window.location.reload();
            }
          });
        }
      <?php } ?>
      
      function addRow(arg){
        if(arg.is_folder !== true){
          $(arg.id).append(`
              <div class="col-sm-3 mb-4">
                  <div class="card">
                    <div class="d-flex justify-content-center">
                      <img class="card-img-top " src="${arg.image}" alt="" style="width:50%; height:50%;">
                    </div>
                      <div class="card-body">
                          <h5 class="card-title">${arg.title}</h5>
                          <p class="card-text">
                              ${arg.desc}
                          </p>
                            
                          <a href="#" onclick="file_remove('${current_dir}/${arg.title}')" class="btn btn-danger btn-sm">
                              Remove
                          </a>

                        <a href="#" onclick="readFile('${current_dir}/${arg.title}')" class="btn btn-primary btn-sm">
                              View
                          </a>
                      </div>
                  </div>
              </div>
          `);
        }else{
          $(arg.id).append(`
                <div class="col-sm-3 mb-4" onclick="aopen('${current_dir}/${arg.title}')">
                    <div class="card">
                      <div class="d-flex justify-content-center">
                        <img class="card-img-top " src="${arg.image}" alt="" style="width:50%; height:50%;">
                      </div>
                        <div class="card-body">
                            <h5 class="card-title">${arg.title}</h5>
                            <p class="card-text">
                                ${arg.desc}
                            </p>
                           <a href="#" onclick="fold_remove('${current_dir}/${arg.title}')" class="btn btn-danger btn-sm">
                              Remove
                          </a>
                        </div>
                    </div>
                </div>
            `);
        }
      }
    </script>
  </body>
</html>