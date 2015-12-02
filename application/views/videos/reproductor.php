<div class="contenedor-global">
  <div class="contenido">
    <div class="principal-video">
      <video id="video" controls preload="auto" poster="<?= base_url()?>assets/images/tumblr.gif" data-setup="{customControlsOnMobile: true}" class="video-js vjs-default-skin">
        <source src=<?=$ruta.".ogv"?> type="video/ogg"/>
        <source src=<?=$ruta.".webm"?> type="video/webm"/>
        <source src=<?=$ruta.".mp4"?> type="video/mp4"/>
        <p class="vjs-no-js">
          <To>view this video please enable JavaScript, and consider upgrading to a web browser that </To>
        </p><a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
      </video>

     <!--<div align="center"><iframe src="http://192.168.0.14/feitube/videos/incrusta/8" width=50% height=400 frameborder=1 scrolling=auto></iframe></div>

      <a class="twitter-timeline" href="https://twitter.com/hashtag/DevAplicacionesEnRed" data-widget-id="671523931228999680">
        Tweets sobre #DevAplicacionesEnRed</a>
        <script>
        !function(d,s,id){
          var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
            if(!d.getElementById(id))
            {
              js=d.createElement(s);
              js.id=id;
              js.src=p+"://platform.twitter.com/widgets.js";
              fjs.parentNode.insertBefore(js,fjs);
            }
          }
          (document,"script","twitter-wjs");
        </script>-->

  </div>

    <div ng-app="myApp" class="comentarios-video">
      <div id="info-video">
        <img src="<?= base_url()?>assets/images/user.png" alt=""/>
        <h3><?=$video[0]->name_video?></h3>
        <h4><b>Descripcion: </b><?=$video[0]->desc?></h4>
        <h4><b>Categoria: </b><?=$video[0]->categoria?></h4>
        <h4><b>Subido </b><?=$video[0]->date_up?></h4>
      </div>


      <div class="count" ng-controller="postLike">
          <p ng-controller="getVisitas">Visto {{visitas}}</p>

       <?php if(isset($user)){?>
           <input type="radio" name="valoracion" class="btn btn-default" ng-click="likeUp()">
              <span class="glyphicon glyphicon-thumbs-up"></span>
        <?php }?>
            </input> <p ng-controller="getLikes">{{"Me gusta "+likes}}</p>
        
       <?php if(isset($user)){?>
            <input type="radio" name="valoracion" class="btn btn-default" ng-click="likeDown()">
              <span class="glyphicon glyphicon-thumbs-down"></span>
       <?php }?>

            </input> <p ng-controller="getNotLikes">{{"No me gusta "+not_like}}</p>

        </div>

      <!--Envia comentarios-->
       <?php if(isset($user)){?>
      <form role="form" method="post" ng-controller="postComentarios">
        <label>Comentario</label><br>
        <textarea class="form-control" maxlength="150" cols="90" ng-model="comentario" required></textarea>
        <br>
        <button class="btn btn-primary" type="submit" ng-click="insertData()">publicar</button>
      </form>
      <br>
      <?php }?>
      <!--comentarios tiempo real-->
      <div ng-controller="getComentarios">
        <ul ng-repeat="n in list_data">
          <li><pre><b>{{" Fecha: "}}</b>{{n.fecha}}
          <b>{{"Comentario: "}}</b>{{n.comentario}}</pre></li>
        </ul>
      </div>

      <script type="text/javascript">
        var path = window.location.pathname.split("/");
        path = path[path.length-1];
        <?php if(isset($user))
        echo "var id_user = ".$user.";\n"?>
        var base_url = "<?=base_url()?>";
        var video = "<?=$id_video?>";
      </script> 
      <script type="text/javascript" src="<?=base_url()?>assets/scripts/js/app.js?v1"></script> 
      <script type="text/javascript" src="<?=base_url()?>assets/scripts/js/visitas.js"></script>     

    </div>
    <div class="mas-videos">
      <h3>Videos Relacionados</h3>
      <?php
        if(isset($videos))
        {
          foreach ($videos as $row) {
          echo '<div class="videos">';
          echo '<a href="'.base_url().'videos/player/'.$row->id_video.'">'.'<img src="'.$ruta_img.$row->ruta_img.'" >'.'</a>';
          echo '<br><a href="'.base_url().'videos/player/'.$row->id_video.'">'.$row->name_video.'</a>';
          echo '<br><p><b> '." visitas: </b>".$row->visitas."</b><b> Me gusta: </b>".$row->likes.'</p>';
          echo '</div>';
        }
      }
      ?>
    </div>
  </div>
</div>
</html>