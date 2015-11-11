<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <!-- <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>-->
    <script src="<?= base_url()?>assets/scripts/js/jquery-2.1.4.min.js"></script>
    <script src="<?= base_url()?>assets/scripts/js/bootstrap.min.js"></script>
    <link href="<?= base_url()?>assets/images/icono.png" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url()?>assets/styles/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url()?>assets/styles/css/bootstrap-theme.min.css">
    <link href="<?= base_url()?>assets/styles/css/estilos.css" rel="stylesheet">
  </head>
  <body>
    <header>
      <nav class="navbar navbar-default">
        <div class="container-fluid"></div>
        <!-- Brand and toggle get grouped for better mobile display-->
        <div class="navbar-header">
          <button type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false" class="navbar-toggle collapsed"><span class="sr-only">Toggle navigation   </span><span class="icon-bar"> </span></button>
        </div><a href="<?= base_url()."videos"?>" class="navbar-brand">Feitube</a>
        <form role="search" action="#" method="get" accept-charset="utf-8" class="navbar-form navbar-left">
          <div class="form-group">
            <input type="text" name="q" placeholder="Buscar" class="form-control">
          </div>
          <button id="search" type="submit" class="btn btn-default">Buscar</button>
        </form>
        <!-- Collect the nav links, forms, and other content for toggling-->
        <div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li>
              <form role="upload" class="navbar-form navbar-left">
                <button type="button" class="btn btn-default"><span aria-hidden="true" class="glyphicon glyphicon-upload"></span></button>
              </form>
            </li>
            <li>
              <form class="navbar-form navbar-left" action="<?=base_url()?>videos/logout">
                <button id="login" type="submit" class="btn btn-default">
                  Salir
                </button>
              </form>
            </li>
          </ul>
        </div>
        <!-- /.navbar-collapse-->
      </nav>
      <!-- /.container-fluid-->
    </header>