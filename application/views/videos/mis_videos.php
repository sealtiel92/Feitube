<body>	
	<div class="contenedor">
	
		<div class="cuerpo">
			
			<div class="principal">

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
					?>
				</div>

			</div>
		
		</div>

	</div>

</body>	