<div class="contenedor-global">
  <div class="contenido">
    <div class="principal-video">
      <h1>Registro</h1>
    </div>
    <div class="campos-registro">
      <div class="contenedor-publicidad"><img src="<?= base_url()?>assets/images/GlobalVideo.jpg" alt=""/></div>
      <div class="contenedor-datos">
        <h3>datos registro</h3>
        <form action="<?= base_url()."videos/registro"?>" method="POST" accept-charset="utf-8">
          <input type="text" name="first_name" value="" placeholder="Nombre" maxlength="30" class="medio form-control"/>
          <input type="text" name="email" value="" placeholder="Correo" maxlength="30" class="complete form-control"/>
          <input type="text" name="email_confirm" value="" placeholder="Confirmacion de correo" maxlength="30" class="complete form-control"/>
          <input type="text" name="phone" value="" placeholder="Telefono" maxlength="15" class="complete form-control"/>
          <input type="password" name="password" value="" placeholder="Contraseña" maxlength="30"  class="complete form-control"/>
          <input type="password" name="password_confirm" value="" placeholder="Confirmacion Contraseña" maxlength="30" class="complete form-control"/>
          <label for="name" class="medio">fecha de nacimiento</label>
          <input type="date" name="birthday" value="" placeholder=""  class="form-control medio"/>
          <label>Hombre</label>
          <input type="radio" name="sex" value="M" />
          <label>Mujer</label>
          <input type="radio" name="sex" value="F" />
          <input type="submit" value="Registrar" class="btn btn-success"/>
        </form>
        
<div id="infoMessage"><?php echo $message;?></div>
      </div>
    </div>
  </div>
  <div id="pie">
  </div>
</div>
</body>