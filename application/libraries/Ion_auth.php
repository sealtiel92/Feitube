<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth
*
* Version: 2.5.2
*
* Author: Ben Edmunds
*		  ben.edmunds@gmail.com
*         @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Ion_auth
{
	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = array();

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = array();

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group;

	/**
	* Constructor de la libreria 
	**/
	public function __construct()
	{
		$this->load->config('ion_auth', TRUE);
		$this->load->library(array('email'));
		$this->lang->load('ion_auth');
		$this->load->helper(array('cookie', 'language','url'));

		$this->load->library('session');

		$this->load->model('ion_auth_model');

		$this->_cache_user_in_group =& $this->ion_auth_model->_cache_user_in_group;

		//auto-login the user if they are remembered
		if (!$this->logged_in() && get_cookie($this->config->item('identity_cookie_name', 'ion_auth')) && get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
		{
			$this->ion_auth_model->login_remembered_user();
		}

		$email_config = $this->config->item('email_config', 'ion_auth');

		if ($this->config->item('use_ci_email', 'ion_auth') && isset($email_config) && is_array($email_config))
		{
			$this->email->initialize($email_config);
		}

		$this->ion_auth_model->trigger_events('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		if($method == 'create_user')
		{
			return call_user_func_array(array($this, 'register'), $arguments);
		}
		if($method=='update_user')
		{
			return call_user_func_array(array($this, 'update'), $arguments);
		}
		return call_user_func_array( array($this->ion_auth_model, $method), $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @access	public
	 * @param	$var
	 * @return	mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}

	/**
	 * forgotten password feature
	 *
	 * @return mixed  boolian / array
	 * @author Mathew
	 **/
	public function forgotten_password($identity)    //changed $email to $identity
	{
		if ( $this->ion_auth_model->forgotten_password($identity) )   //changed
		{
			// Get user information
      $identifier = $this->ion_auth_model->identity_column; // use model identity column, so it can be overridden in a controller
      $user = $this->where($identifier, $identity)->where('active', 1)->users()->row();  // changed to get_user_by_identity from email

			if ($user)
			{
				$data = array(
					'identity'		=> $user->{$this->config->item('identity', 'ion_auth')},
					'forgotten_password_code' => $user->forgotten_password_code
				);

				if(!$this->config->item('use_ci_email', 'ion_auth'))
				{
					$this->set_message('forgot_password_successful');
					return $data;
				}
				else
				{
					$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);
					$this->email->clear();
					$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
					$this->email->to($user->email);
					$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
					$this->email->message($message);

					if ($this->email->send())
					{
						$this->set_message('forgot_password_successful');
						return TRUE;
					}
					else
					{
						$this->set_error('forgot_password_unsuccessful');
						return FALSE;
					}
				}
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * forgotten_password_complete
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
		$this->ion_auth_model->trigger_events('pre_password_change');

		$identity = $this->config->item('identity', 'ion_auth');
		$profile  = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!$profile)
		{
			$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}

		$new_password = $this->ion_auth_model->forgotten_password_complete($code, $profile->salt);

		if ($new_password)
		{
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);
			if(!$this->config->item('use_ci_email', 'ion_auth'))
			{
				$this->set_message('password_change_successful');
				$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

				$this->email->clear();
				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
				$this->email->to($profile->email);
				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_new_password_subject'));
				$this->email->message($message);

				if ($this->email->send())
				{
					$this->set_message('password_change_successful');
					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_successful'));
					return TRUE;
				}
				else
				{
					$this->set_error('password_change_unsuccessful');
					$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
					return FALSE;
				}

			}
		}

		$this->ion_auth_model->trigger_events(array('post_password_change', 'password_change_unsuccessful'));
		return FALSE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @return void
	 * @author Michael
	 **/
	public function forgotten_password_check($code)
	{
		$profile = $this->where('forgotten_password_code', $code)->users()->row(); //pass the code to profile

		if (!is_object($profile))
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
		else
		{
			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0) {
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
				if (time() - $profile->forgotten_password_time > $expiration) {
					//it has expired
					$this->clear_forgotten_password_code($code);
					$this->set_error('password_change_unsuccessful');
					return FALSE;
				}
			}
			return $profile;
		}
	}

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function register($identity, $password, $email, $additional_data = array(), $group_ids = array()) //need to test email activation
	{
		$this->ion_auth_model->trigger_events('pre_account_creation');

		$email_activation = $this->config->item('email_activation', 'ion_auth');
		
		$id = $this->ion_auth_model->register($identity, $password, $email, $additional_data, $group_ids);

		if (!$email_activation)
		{	
			if ($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful'));
				return $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}
		}
		else
		{
			if (!$id)
			{
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}

			// deactivate so the user much follow the activation flow
			$deactivate = $this->ion_auth_model->deactivate($id);

			// the deactivate method call adds a message, here we need to clear that
			$this->ion_auth_model->clear_messages();


			if (!$deactivate)
			{
				$this->set_error('deactivate_unsuccessful');
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful'));
				return FALSE;
			}

			$activation_code = $this->ion_auth_model->activation_code;
			$identity        = $this->config->item('identity', 'ion_auth');
			$user            = $this->ion_auth_model->user($id)->row();

			$data = array(
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activation_code,
			);
			if(!$this->config->item('use_ci_email', 'ion_auth'))
			{
				$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
				$this->set_message('activation_email_successful');
				return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);

				$this->email->clear();
				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
				$this->email->to($email);
				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject'));
				$this->email->message($message);

				if ($this->email->send() == TRUE)
				{
					$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_successful', 'activation_email_successful'));
					$this->set_message('activation_email_successful');
					return $id;
				}

			}

			$this->ion_auth_model->trigger_events(array('post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful'));
			$this->set_error('activation_email_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->ion_auth_model->trigger_events('logout');

		$identity = $this->config->item('identity', 'ion_auth');

                if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->unset_userdata( array($identity => '', 'id' => '', 'user_id' => '') );
                }
                else
                {
                	$this->session->unset_userdata( array($identity, 'id', 'user_id') );
                }

		// delete the remember me cookies if they exist
		if (get_cookie($this->config->item('identity_cookie_name', 'ion_auth')))
		{
			delete_cookie($this->config->item('identity_cookie_name', 'ion_auth'));
		}
		if (get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
		{
			delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));
		}

		// Destroy the session
		$this->session->sess_destroy();

		//Recreate the session
		if (substr(CI_VERSION, 0, 1) == '2')
		{
			$this->session->sess_create();
		}
		else
		{
			$this->session->sess_regenerate(TRUE);
		}

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * logged_in
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->ion_auth_model->trigger_events('logged_in');

		return (bool) $this->session->userdata('identity');
	}

	/**
	 * logged_in
	 *
	 * @return integer
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{
		$user_id = $this->session->userdata('user_id');
		if (!empty($user_id))
		{
			return $user_id;
		}
		return null;
	}

	/**
	* Obtiene del correo del usuario
	* params ID
	**/
	public function get_user_email($id)
	{
		$email = $this->ion_auth_model->get_email($id);
		foreach($email as $row)
		{
			$email = $row->email;
		}
		return $email;
	}

	/**
	* Obtiene la ruta del video fisito para mostrar
	**/
	public function get_video_ruta($id)
	{
		$video = $this->ion_auth_model->get_ruta_video($id);
		foreach($video as $row)
		{
			$ruta = $row->ruta;
		}
		if(isset($ruta))
		{
			return $ruta;
		}else
		return null;
	}

	/**
	* Obtiene toda la informacion de un video
	* params ID
	**/
	public function get_video($id)
	{
		$video = $this->ion_auth_model->get_ruta_video($id);
		if(isset($video))
		{
			return $video;
		}else
		return null;
	}

	/**
	* obtiene los video para mostrar excepto el que se reproduce
	* params limite y id video
	**/
	public function get_videos_not_id($id = null, $limit = null)
	{
		if($id != null)
		{
			$videos = $this->ion_auth_model->get_videos_not_id($id, null);
		}
		if(isset($videos))
		{
			return $videos;
		}else
		return null;
	}

	/**
	* obtiene videos con criterios de busqueda
	* Params nombre, limite
	**/
	public function get_videos_busqueda($name = null, $limit = null)
	{
		if($name != null)
		{
			$videos = $this->ion_auth_model->get_videos_busqueda($name, null);
		}
		else
		{
			$videos = $this->ion_auth_model->get_videos_busqueda(null,$limit);
		}

		if(isset($videos))
		{
			return $videos;
		}else
		return null;
	}

	/**
	* Obtiene los videos del usuario
	* Params ID_user
	**/
	function get_videos_user($id_user)
	{
		$videos = $this->ion_auth_model->get_videos_user($id_user);
		if(isset($videos))
		{
			return $videos;
		}else
		return null;
	}

	/**
	* Obtiene los comentarios de los videos
	* 
	**/
	public function get_comentarios()
	{
		$comentarios = $this->ion_auth_model->get_comentarios_model();
		if(isset($comentarios))
		{
			return $comentarios;
		}else
		return null;
	}

	/**
	* Obtiene comentarios por id de video
	* 
	**/
	public function get_comentarios_id($id = null)
	{
		$comentarios = $this->ion_auth_model->get_comentarios_id($id);
		if(isset($comentarios))
		{
			return $comentarios;
		}else
		return null;
	}
	
	/**
	* Inserta un video nuevo
	* Params datos del video
	**/
	public function insert_video($data)
	{
		$this->ion_auth_model->guarda_video($data);
	}

	/**
	* inserta un comentario en un video
	* 
	**/
	public function insert_comentario($data)
	{
		$this->ion_auth_model->guarda_comentario($data);
	}

	/**
	* incrementa la visita de un video
	* Params ID video
	**/
	public function incrementa_visita($id)
	{
		$this->ion_auth_model->incrementa_visita($id);	
	}

	/**
	* Agrega un like al video si es que no existe uno
	* Params id_video id_user
	**/
	public function insert_like($id_video,$id_user)
	{
		if($this->get_like_exists($id_video,$id_user)== null)
		{
			$this->ion_auth_model->insert_like_m($id_video);
			$this->insert_like_user_video($id_video,$id_user);	
		}
	}

	/**
	* Agrega no me gusta si es que no existe uno o este un like selecionado
	* Params Id_user Id_video
	**/
	public function insert_not_like($id_video,$id_user)
	{
		if($this->get_like_exists($id_video,$id_user)== null)
		{
			$this->ion_auth_model->insert_not_like_m($id_video);
			$this->insert_like_user_video($id_video,$id_user);
		}
	}

	/**
	* Obtiene los likes de un video
	* Params Id_video y Bool
	**/
	public function get_likes_id($id_video,$bool)
	{
		$likes = $this->ion_auth_model->get_like_m($id_video,$bool);
		if(isset($likes))
		{
			return $likes;
		}else
		return null;	
	}

	/**
	* Comprueba si existe un like o not like entre usuario y video
	* Params Video User
	**/
	public function get_like_exists($video,$user)
	{
		$like = $this->ion_auth_model->like_exists($video,$user);	
		if(isset($like))
		{
			return $like;
		}else
		return null;	
	}

	/**
	* Agrega relacion like entre usuario y Video
	* Params id_user id_video
	**/
	public function insert_like_user_video($id_video,$id_user)
	{
		$this->ion_auth_model->insert_like_user_video($id_video,$id_user);
	}

	/**
	* obtiene visitas de un video
	* Params id_video
	**/
	public function get_visitas($id_video)
	{
		$visitas = $this->ion_auth_model->get_visitas_m($id_video);
		if(isset($visitas))
		{
			return $visitas;
		}else
		return null;
	}

	/**
	 * is_admin
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function is_admin($id=false)
	{
		$this->ion_auth_model->trigger_events('is_admin');

		$admin_group = $this->config->item('admin_group', 'ion_auth');

		return $this->in_group($admin_group, $id);
	}

	/**
	 * in_group
	 *
	 * @param mixed group(s) to check
	 * @param bool user id
	 * @param bool check if all groups is present, or any of the groups
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function in_group($check_group, $id=false, $check_all = false)
	{
		$this->ion_auth_model->trigger_events('in_group');

		$id || $id = $this->session->userdata('user_id');

		if (!is_array($check_group))
		{
			$check_group = array($check_group);
		}

		if (isset($this->_cache_user_in_group[$id]))
		{
			$groups_array = $this->_cache_user_in_group[$id];
		}
		else
		{
			$users_groups = $this->ion_auth_model->get_users_groups($id)->result();
			$groups_array = array();
			foreach ($users_groups as $group)
			{
				$groups_array[$group->id] = $group->name;
			}
			$this->_cache_user_in_group[$id] = $groups_array;
		}
		foreach ($check_group as $key => $value)
		{
			$groups = (is_string($value)) ? $groups_array : array_keys($groups_array);

			/**
			 * if !all (default), in_array
			 * if all, !in_array
			 */
			if (in_array($value, $groups) xor $check_all)
			{
				/**
				 * if !all (default), true
				 * if all, false
				 */
				return !$check_all;
			}
		}

		/**
		 * if !all (default), false
		 * if all, true
		 */
		return $check_all;
	}

}
