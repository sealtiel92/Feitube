	<div class="contenedor-global">

		<div class="contenido">

			<div id="videos">
				<?php
					if(isset($videos))
					{
						foreach ($videos as $row)
						{
							echo '<div class="mini-video">';
								echo '<div class="videos">';
								echo '<a href="'.base_url().'videos/player/'.$row->id_video.'">'.'<img src="'.$ruta.$row->ruta_img.'" height="100" width="220">'.'</a>';
								echo '<br><a href="'.base_url().'videos/player/'.$row->id_video.'">'.$row->name_video.'</a>';
								echo '</div>';
							echo '</div>';
						}
					}
					else
					{
						echo "<h3>No Existe</h3>";
					}
				?>
			</div>

		</div>
	</div>

</body>	