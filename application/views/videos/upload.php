<body>
	<div class="contenido" id="contenido-upload">
		<div class="comentarios-video" id="upload">
			<h3>Selecciona un Video</h3>
			<form action="<?=base_url()?>index.php/videos/cargar_video" method="post" enctype="multipart/form-data">
				</br>
				<input class="btn btn-info" name="archivo" type="file" size="35" /></br>
				<label>nombre: </label></br>
				<input name="nombre" maxlength="30" type="text" required/></br>
				<label>descripcion: </label></br>
				<textarea maxlength="150" name="descripcion" required></textarea></br>
				<label>categoria: </label></br>
				<input name="categoria" maxlength="30" type="text" required/></br>
				<input class="btn btn-primary" name="enviar" type="submit" value="Cargar video"/><br>
				<input name="action" type="hidden" value="upload"/>
			</form>
		</div>
		<div class="mas-videos" id="stream">
			<h3>streaming</h3>
			<form class="navbar-form navbar-left" action="<?=base_url()?>index.php/videos/stream">
				<button class="btn btn-success" id="login" type="submit" class="btn btn-default">
					streaming
				</button>
			</form>
		</div>
	</div>
</body>
</html>

