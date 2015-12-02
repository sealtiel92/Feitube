<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Videos
 * @uses ion-auth
 * @framework CodeIgniter 
 * @version 2.X
 * @author Sealtiel Huerta
 *
 * Description: Controlador de Feitube
 */

class Videos extends CI_Controller {

/**
 * Constructor
 **/
	private $servidor_sec;
	function __construct()
	{
		parent::__construct();
		$this->servidor_sec = "192.168.0.16";
		$this->load->database();
		$this->load->library(array('ion_auth','form_validation'));
		$this->load->helper(array('url','language'));
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
		$this->lang->load('auth');
	}

	/**
	* Index Muestra todos lo videos existentes
	**/
	function index()
	{
		$data['title'] = "Inicio";
		if ($this->ion_auth->logged_in())
		{
			if (isset($_POST['busqueda']))
			{
				$videos = $this->ion_auth->get_videos_busqueda($_POST['busqueda'],null);
			}
			else
			{
				$videos = $this->ion_auth->get_videos_busqueda(null, 40);
			}

			$data['videos'] = $videos;	
			$data['ruta'] = "http://".$this->servidor_sec;
			$this->load->view('videos/header_logout');
			$this->load->view('videos/index',$data);
		}
		else
		{
			if (isset($_POST['busqueda']))
			{
				$videos = $this->ion_auth->get_videos_busqueda($_POST['busqueda']);
			}
			else
			{
				$videos = $this->ion_auth->get_videos_busqueda();
			}

			$data['videos'] = $videos;	
			$data['ruta'] = "http://".$this->servidor_sec;
			$this->load->view('videos/header');
			$this->load->view('videos/index',$data);
		}
	}

	/**
	* Muestra los video del usuario
	**/
	function mis_videos()
	{
		$data['title'] = "Inicio";
		if ($this->ion_auth->logged_in())
		{
			$videos = $this->ion_auth->get_videos_user($this->ion_auth->get_user_id());
			$data['videos'] = $videos;	
			$data['ruta'] = "http://".$this->servidor_sec;
			$this->load->view('videos/header_logout');
			$this->load->view('videos/index',$data);
		}
		else
		{
			redirect('videos/login','refresh');
		}
	}

	/**
	* Resproductor Carga video selecionado por id
	**/
	function player()
	{
		$limit = 10;
		if ($this->ion_auth->logged_in())
		{
			$id_video = $this->uri->segment(3);
			if($id_video != null)
			{
				$data['video'] = $this->ion_auth->get_video($id_video);
				$data['user'] = $this->ion_auth->get_user_id();
				$data['videos'] = $this->ion_auth->get_videos_not_id($id_video,$limit);
				$ruta = $this->ion_auth->get_video_ruta($id_video);
				$data['ruta'] = "http://".$this->servidor_sec.$ruta;
				$data['ruta_img'] = "http://".$this->servidor_sec;
				$data['id_video'] = $id_video;
				$this->load->view('videos/header_logout');
				$this->load->view('videos/reproductor',$data);
			}
			else
			{
				redirect('videos/index','refresh');
			}
		}
		else
		{
			$id_video = $this->uri->segment(3);
			if($id_video != null)
			{
				$data['video'] = $this->ion_auth->get_video($id_video);
				$data['videos'] = $this->ion_auth->get_videos_not_id($id_video,$limit);
				$ruta = $this->ion_auth->get_video_ruta($id_video);
				$data['ruta'] = "http://".$this->servidor_sec.$ruta;
				$data['ruta_img'] = "http://".$this->servidor_sec;
				$data['id_video'] = $id_video;
				$this->load->view('videos/header');
				$this->load->view('videos/reproductor',$data);
			}
			else
			{
				redirect('videos/index','refresh');
			}
		}
	}

	/**
	* Carga vista del streaming
	**/
	function  stream()
	{
		$data['title'] = "Stream";
		if ($this->ion_auth->logged_in())
		{
			$this->load->view('videos/header_logout');
			$this->load->view('videos/stream.html',$data);
		}
		else
		{
			$this->load->view('videos/header');
			$this->load->view('videos/stream.html',$data);
		}
	}

	/**
	* pagina para iniciar session
	**/
	function login()
	{
		$this->data['title'] = "Login";
		if($this->ion_auth->logged_in())
		{
			redirect('videos/', 'refresh');
		}

		//validate form input
		$this->form_validation->set_rules('identity', 'Identity', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{
			// check to see if the user is logging in
			// check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
			{
				//if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				$_SESSION["email"] = $this->input->post('identity');
				redirect('videos/', 'refresh');
			}
			else
			{
				// if the login was un-successful
				// redirect them back to the login page
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				$this->load->view('videos/header');
				redirect('videos/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
			}
		}
		else
		{
			// the user is not logging in so display the login page
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

			$this->data['identity'] = array('name' => 'identity',
				'id'    => 'identity',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('identity'),
			);
			$this->data['password'] = array('name' => 'password',
				'id'   => 'password',
				'type' => 'password',
			);
			$this->load->view('videos/header');
			$this->_render_page('videos/login.html', $this->data);
		}
	}

	/**
	* Funcion salida de usuario
	**/
	function logout()
	{
		$this->data['title'] = "Logout";
		// log the user out
		$logout = $this->ion_auth->logout();
		// redirect them to the login page
		$this->session->set_flashdata('message', $this->ion_auth->messages());
		redirect('videos/', 'refresh');
	}

	/**
	* pagina de carga de videos para el usuario
	**/
	function upload()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->load->view('videos/header_logout');
			$this->load->view('videos/upload');
		}
		else
		{
			redirect('videos/login', 'refresh');
		}
	}

	/**
	* Cargar el video al servidor secundario 
	**/
	function cargar_video()
	{
		if($this->ion_auth->logged_in())
		{
			$email = $this->ion_auth->get_user_email($this->ion_auth->get_user_id());
			$dir_upload = "/opt/lampp/htdocs/videos/".$email."/";
			$size = $_FILES["archivo"]['size'];
			$type = $_FILES["archivo"]['type'];
			$name = $_FILES["archivo"]['name'];
			$find = array(" ","(",")");
			$name = str_replace($find, '_', $name);
			$dir_archivo = $dir_upload.$name;
			
			$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : null;
			$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;
			$categoria = isset($_POST['categoria']) ? $_POST['categoria'] : null;

			if(!empty($nombre)||!empty($categoria)||!empty($descripcion)||
					strlen($descripcion)<150||strlen($nombre)<30||strlen($categoria)<30||
				$nombre!=null||$descripcion!=null||$categoria!=null)
			{
				$datos = array(
					'name_video' => $email." - ".$_POST['nombre'],
					'date_up' => date('Y-m-d H:i:s'),
					'desc' => $_POST['descripcion'],
					'ruta' => '/videos/'.$email.'/'.$name,
					'ruta_img' => '/videos/'.$email.'/'.$name.'.png',
					'categoria' => $_POST['categoria'],
					'users_id' => $this->ion_auth->get_user_id()
				);

				if(($_FILES["archivo"]["type"] == "video/flv")||($_FILES["archivo"]["type"] == "video/mp4")||
					($_FILES["archivo"]["type"] == "video/avi")||($_FILES["archivo"]["type"] == "video/mpeg")||
					($_FILES["archivo"]["type"] == "video/mov")||($_FILES["archivo"]["type"] == "video/wmv")||
					($_FILES["archivo"]["type"] == "video/webm")||($_FILES["archivo"]["type"] == "video/ogg"))
				{
					if($_FILES['archivo']['size'] < 1000000000)
					{
						if(move_uploaded_file($_FILES['archivo']['tmp_name'], $dir_archivo))
						{ 
							chmod($dir_archivo, 0777);
							set_include_path(get_include_path().PATH_SEPARATOR.'/opt/lampp/htdocs/feitube/application/libraries/phpseclib');
							include('Net/SSH2.php');
							include('Net/SCP.php');
							$ssh = new Net_SSH2($this->servidor_sec,22,500);
							if (!$ssh->login('seat', 's'))
							{
							    exit('Login Failed');
							}
							$scp = new Net_SCP($ssh);
						    if (!$scp->put($dir_archivo, $dir_archivo, NET_SCP_LOCAL_FILE))
						   	{
						       throw new Exception("Failed to send file");
						    }
							$ssh->exec("ffmpeg -i ".$dir_archivo." -ss 00:00:01 -vframes 1 ".$dir_archivo.".png");
							$ssh->exec("ffmpeg -i ".$dir_archivo." -strict -2 -vcodec libx264 -crf 100 ".$dir_archivo.".mp4");	
							$ssh->exec("ffmpeg -i ".$dir_archivo." -acodec libvorbis -vcodec libtheora -ac 2 -ab 96k -ar 44100 -b:v 819200 -crf 200 ".$dir_archivo.".ogv");
							$ssh->exec("ffmpeg -i ".$dir_archivo." -c:v libvpx -crf 20 -b:v 1M -c:a libvorbis ".$dir_archivo.".webm");
							$this->ion_auth->insert_video($datos);
							redirect('videos/index','refresh');
						}
						else
						{
							redirect('videos/upload','refresh');
						}	
					}else
					{
						redirect('videos/upload','refresh');
					}
				}else
				{
					redirect('videos/upload','refresh');
				}
			}else
			{
				redirect('videos/upload','refresh');
			}
		}else
		{
			redirect('videos/login','refresh');
		}
	}
	
	/**
	* Registro crea un nuevo usuario
	**/
	function registro()
	{
		$this->data['title'] = "Registro";

		if ($this->ion_auth->logged_in())
		{
			redirect('videos/index', 'refresh');
		}

		$tables = $this->config->item('tables','ion_auth');

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique['.$tables['users'].'.email]|matches[email_confirm]');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('sex', $this->lang->line('create_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('birthday', $this->lang->line('create_user_validation_company_label'), 'required');
		$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

		if ($this->form_validation->run() == true)
		{
			$username = strtolower($this->input->post('first_name'));
			$email    = strtolower($this->input->post('email'));
			$password = $this->input->post('password');

			$additional_data = array(
				'first_name' => $this->input->post('first_name'),
				'birthday'    => $this->input->post('birthday'),
				'phone'      => $this->input->post('phone'),
				'sex'      => $this->input->post('sex'),
				
			);
		}
		if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
		{
			// check to see if we are creating the user
			// redirect them back to the admin page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
			//crear carpeta de usuario
			// si todo es valido acepta al usuario crea carpeta local y remota para videos
			set_include_path(get_include_path().PATH_SEPARATOR.'/opt/lampp/htdocs/feitube/application/libraries/phpseclib');
			include('Net/SSH2.php');
			include('Net/SCP.php');
			$ssh = new Net_SSH2($this->servidor_sec);
			if (!$ssh->login('seat', 's'))
			{
			    exit('Login Failed');
			}
			$ssh->exec("mkdir /opt/lampp/htdocs/videos/".$email."/");

			mkdir("/opt/lampp/htdocs/videos/".$email."/",0777,true); 
			chmod("/opt/lampp/htdocs/videos/".$email."/", 0777);
			redirect("videos/index", 'refresh');
		}
		else
		{
			// display the create user form
			// set the flash data error message if there is one
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['first_name'] = array(
				'name'  => 'first_name',
				'id'    => 'first_name',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('first_name'),
			);
			$this->data['email'] = array(
				'name'  => 'email',
				'id'    => 'email',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['birthday'] = array(
				'name'  => 'birthday',
				'id'    => 'birthday',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('birthday'),
			);
			$this->data['phone'] = array(
				'name'  => 'phone',
				'id'    => 'phone',
				'type'  => 'text',
				'value' => $this->form_validation->set_value('phone'),
			);
			$this->data['password'] = array(
				'name'  => 'password',
				'id'    => 'password',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password'),
			);
			$this->data['password_confirm'] = array(
				'name'  => 'password_confirm',
				'id'    => 'password_confirm',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('password_confirm'),
			);
			$this->data['sex'] = array(
				'name'  => 'sex',
				'id'    => 'sex',
				'type'  => 'password',
				'value' => $this->form_validation->set_value('sex'),
			);
			
			$this->load->view('videos/header');
			$this->_render_page('videos/registro', $this->data);
		}
	}

	/**
	* Edita la informacion personal de usuario
	**/
	function edit_user($id)
	{
		$this->data['title'] = "Edit User";

		if (!$this->ion_auth->logged_in() || (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id)))
		{
			redirect('auth', 'refresh');
		}

		$user = $this->ion_auth->user($id)->row();
		$groups=$this->ion_auth->groups()->result_array();
		$currentGroups = $this->ion_auth->get_users_groups($id)->result();

		// validate form input
		$this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required');
		$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'required');
		$this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required');

		if (isset($_POST) && !empty($_POST))
		{
			// do we have a valid request?
			if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
			{
				show_error($this->lang->line('error_csrf'));
			}

			// update the password if it was posted
			if ($this->input->post('password'))
			{
				$this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
				$this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
			}

			if ($this->form_validation->run() === TRUE)
			{
				$data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name'  => $this->input->post('last_name'),
					'company'    => $this->input->post('company'),
					'phone'      => $this->input->post('phone'),
				);

				// update the password if it was posted
				if ($this->input->post('password'))
				{
					$data['password'] = $this->input->post('password');
				}



				// Only allow updating groups if user is admin
				if ($this->ion_auth->is_admin())
				{
					//Update the groups user belongs to
					$groupData = $this->input->post('groups');

					if (isset($groupData) && !empty($groupData)) {

						$this->ion_auth->remove_from_group('', $id);

						foreach ($groupData as $grp) {
							$this->ion_auth->add_to_group($grp, $id);
						}

					}
				}

			// check to see if we are updating the user
			   if($this->ion_auth->update($user->id, $data))
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->messages() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }
			    else
			    {
			    	// redirect them back to the admin page if admin, or to the base url if non admin
				    $this->session->set_flashdata('message', $this->ion_auth->errors() );
				    if ($this->ion_auth->is_admin())
					{
						redirect('auth', 'refresh');
					}
					else
					{
						redirect('/', 'refresh');
					}

			    }

			}
		}

		// display the edit user form
		$this->data['csrf'] = $this->_get_csrf_nonce();

		// set the flash data error message if there is one
		$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

		// pass the user to the view
		$this->data['user'] = $user;
		$this->data['groups'] = $groups;
		$this->data['currentGroups'] = $currentGroups;

		$this->data['first_name'] = array(
			'name'  => 'first_name',
			'id'    => 'first_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('first_name', $user->first_name),
		);
		$this->data['last_name'] = array(
			'name'  => 'last_name',
			'id'    => 'last_name',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('last_name', $user->last_name),
		);
		$this->data['company'] = array(
			'name'  => 'company',
			'id'    => 'company',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('company', $user->company),
		);
		$this->data['phone'] = array(
			'name'  => 'phone',
			'id'    => 'phone',
			'type'  => 'text',
			'value' => $this->form_validation->set_value('phone', $user->phone),
		);
		$this->data['password'] = array(
			'name' => 'password',
			'id'   => 'password',
			'type' => 'password'
		);
		$this->data['password_confirm'] = array(
			'name' => 'password_confirm',
			'id'   => 'password_confirm',
			'type' => 'password'
		);

		$this->_render_page('auth/edit_user', $this->data);
	}

	/**
	* Guarda comentario de un video 
	* parametro id de video
	**/
	function post_comentario()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if($request->comentario!=null&&strlen($request->comentario)<150)
		{
			$data = array
			(
				'comentario' => $request->comentario,
				'fecha' => date('Y-m-d H:i:s'),
				'video_id_video' => $request->video_id_video
			);
			$id_c = $this->ion_auth->insert_comentario($data);
			if($id_c)
			{
				echo $result='{"status":success}';
			}
			else
			{
				echo $result='{"status":failure}';
			}
		}
	}

	/**
	* agrega like realizado por el usuario a un video
	**/
	function post_like()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if($request->video_id_video!=null&&$request->id_user!=null)
		{
			$this->ion_auth->insert_like($request->video_id_video,$request->id_user);
		}
	}
	
	/**
	* agrega no me gusta a un video
	**/
	function post_not_like()
	{
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		if($request->video_id_video!=null&&$request->id_user!=null)
		{
			$this->ion_auth->insert_not_like($request->video_id_video,$request->id_user);
		}
	}

	/**
	* aumenta la visita de video
	**/
	function post_visita()
	{	
		$id = $this->uri->segment(3);
		$this->ion_auth->incrementa_visita($id);
	}

	/**
	* manda comentarios por get
	**/
	function comentarios()
	{
		$id_video = $this->uri->segment(3);	
		echo json_encode($this->ion_auth->get_comentarios_id($id_video));
	}

	/**
	* Manda like de un video
	**/
	function get_like()
	{
		$id_video = $this->uri->segment(3);	
		echo json_encode($this->ion_auth->get_likes_id($id_video,true));
	}

	/**
	* Manda not like de un video
	**/
	function get_not_like()
	{
		$id_video = $this->uri->segment(3);	
		echo json_encode($this->ion_auth->get_likes_id($id_video,false));
	}

	/**
	* Manda la visitas hechas a un video
	**/
	function get_visitas()
	{
		$id_video = $this->uri->segment(3);	
		echo json_encode($this->ion_auth->get_visitas($id_video));
	}

	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	function _render_page($view, $data=null, $returnhtml=false)
	{

		$this->viewdata = (empty($data)) ? $this->data: $data;

		$view_html = $this->load->view($view, $this->viewdata, $returnhtml);

		if ($returnhtml) return $view_html;//This will return html on 3rd argument being true
	}

}
