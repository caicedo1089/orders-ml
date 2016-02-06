<?php
/*	Plugin Name: Form MercadoLibre
	Plugin URI: https://cuado.co/
	Description: Plugin que permite manejar las ventas realizadas por MercadoLibre con varios estatus
	Version: 1.0
	Author: Pedro Caicedo
	Author URI: http://pcaicedo.com/	
*/

//Cargamos las librerias
require_once "libs/class.iniparser.php";
require_once "libs/class.gump.php";

//Cargamos los archivos INI
$parser = new IniParser(dirname(__FILE__).'/conf/general.ini');
$parser->use_array_object = false;

//Variales globales
global $wpdb; //acceso a BD
$config = $parser->parse(); //Conf. Generales
$validator = new GUMP(); //Validador de Datos en el server

//Tablas BD
$config['tables']['forms'] = $wpdb->prefix . "forms_ml";
$config['tables']['forms_histories'] = $wpdb->prefix . "forms_ml_histories";

function install_form_ml()
{	
	global $config, $wpdb;
	
	$config['tables']['forms'] = $wpdb->prefix . "forms_ml";
	$config['tables']['forms_histories'] = $wpdb->prefix . "forms_ml_histories";
	
	$sql = "CREATE TABLE IF NOT EXISTS `{$config['tables']['forms']}` (
	`id_form_ml` int(10) NOT NULL AUTO_INCREMENT,
	`user_vat` varchar(255) COLLATE utf8_bin NOT NULL,
	`user_names` varchar(255) COLLATE utf8_bin NOT NULL,
	`user_username` varchar(50) COLLATE utf8_bin NOT NULL,
	`user_phone` varchar(32) COLLATE utf8_bin DEFAULT NULL,	
	`user_email` varchar(100) COLLATE utf8_bin NOT NULL,
	`user_address` text COLLATE utf8_bin NOT NULL,
	`user_products` text COLLATE utf8_bin NOT NULL,
	`bank_date` datetime NOT NULL,
	`bank_type_payment` enum('DEPOSITO','TRANSFERENCIA_BS','TRANSFERENCIA_USD','MERCADOPAGO') COLLATE utf8_bin NOT NULL DEFAULT 'DEPOSITO',
	`bank_name` varchar(50) COLLATE utf8_bin NOT NULL,
	`bank_reference` varchar(20) COLLATE utf8_bin NOT NULL,
	`bank_mount_payment` double(10,2) DEFAULT NULL,
	`ship_vat` varchar(255) COLLATE utf8_bin NOT NULL,
	`ship_names` varchar(255) COLLATE utf8_bin NOT NULL,
	`ship_type` enum('ZOOM','TEALCA','PERSONAL') COLLATE utf8_bin NOT NULL DEFAULT 'ZOOM',
	`ship_address` text COLLATE utf8_bin NOT NULL,
	`ship_zp` varchar(10) COLLATE utf8_bin NOT NULL,
	`ship_parish` varchar(50) COLLATE utf8_bin NOT NULL,
	`ship_municipality` varchar(50) COLLATE utf8_bin NOT NULL,
	`ship_city` varchar(50) COLLATE utf8_bin NOT NULL,
	`ship_state` varchar(50) COLLATE utf8_bin NOT NULL,
	`ship_comments` text COLLATE utf8_bin NOT NULL,
	`ship_status_now` enum('POR_VERIFICAR','VERIFICADO','NEGADO','ENVIADO','ENTREGADO') COLLATE utf8_bin NOT NULL DEFAULT 'POR_VERIFICAR',
	PRIMARY KEY ( `id_form_ml` )
	) ;";
	/* ,
	UNIQUE ( `bank_reference` ) */
	$wpdb->query($sql);
	
	/*echo $wpdb->last_query;
	echo $wpdb->print_error();
	echo $wpdb->show_errors();*/
	
	$sql = "CREATE TABLE IF NOT EXISTS `{$config['tables']['forms_histories']}` (
	  `id_form_ml_history` int(10) NOT NULL AUTO_INCREMENT,
	  `id_form_ml` int(10) DEFAULT NULL,
	  `ship_status` enum('POR_VERIFICAR','VERIFICADO','NEGADO','ENVIADO','ENTREGADO') COLLATE utf8_bin NOT NULL DEFAULT 'POR_VERIFICAR',
	  `comments` text COLLATE utf8_bin NOT NULL COMMENT 'Comentarios adicionales',
	  `time_modified` datetime DEFAULT NULL,
	  `id_user_modifier` int(10) DEFAULT NULL,
	  PRIMARY KEY (`id_form_ml_history`)
	) ;";
	$wpdb->query($sql);
	
	//Configuraciones propias
	//add_option( 'form_ml_mail', 'pcaicedo@cuado.co', '', 'yes' );
	add_option( 'form_ml_mail', 'diegomanuelsanchez@hotmail.com', '', 'yes' );
	update_option( 'form_ml_mail', 'diegomanuelsanchez@hotmail.com');
}

function desinstall_form_ml()
{
	global $config, $wpdb;
	
	$sql = "DROP TABLE {$config['tables']['forms'] }";
	$wpdb->query($sql);
	$sql = "DROP TABLE {$config['tables']['forms_histories']}";
	$wpdb->query($sql);
}

function panel_add_form_ml()
{
	global $config, $validator, $wpdb;
	
	$data_edit = array ();
	
	//echo '<pre>', print_r($_GET), '</pre>';
	
	if($_GET['form_ml'] && empty($_POST)){
		
		$data_edit = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$config['tables']['forms']} WHERE id_form_ml = {$_GET['form_ml']}" ), ARRAY_A );
		$date = date_create($data_edit['bank_date']);
		$data_edit['bank_hour'] = date_format($date, 'h:i A');
		$data_edit['bank_date'] = date_format($date, 'd/m/Y');
		
		$data_edit['bank_hour'] = str_replace('AM', 'a.m.', $data_edit['bank_hour']);
		$data_edit['bank_hour'] = str_replace('PM', 'p.m.', $data_edit['bank_hour']);
		$data_edit['form_ml'] = $_GET['form_ml'];
	}
	
	if(!empty($_POST)){

		$rules = array(
		  'user_names' => "required",
		  //'user_names' => "required|valid_name",
		  'user_vat' => "required",
		  'user_phone' => "required",
		  'user_username' => "required",
		  'user_email' => "required",
		  'user_address' => "required",
		  'user_products' => "required",
		  'bank_date' => "required",
		  'bank_hour' => "required",
		  'bank_type_payment' => "required",
		  'bank_name' => "required",
		  'bank_reference' => "required",
		  'bank_mount_payment' => "required",
		  'ship_names' => "required",
		  'ship_type' => "required",
		  'ship_address' => "required",
		  'ship_zp' => "required",
		  'ship_parish' => "required",
		  'ship_municipality' => "required",
		  'ship_city' => "required",
		  'ship_state' => "required",
		  //'ship_comments' => "required",
		);

		$valid = $validator->validate( $_POST, $rules );

		if($valid !== true) {
		  echo $validator->get_readable_errors(true); 
		} else {
			echo bd_set_form_ml( $_POST );
			return;
		}
		
		
	}
	
    $data['user_names'] = $_POST['user_names'];
	$data['user_vat'] = $_POST['user_vat'];
	$data['user_phone'] = $_POST['user_phone'];
	$data['user_username'] = $_POST['user_username'];
	$data['user_email'] = $_POST['user_email'];
	$data['user_address'] = $_POST['user_address'];
	$data['user_products'] = $_POST['user_products'];
	$data['bank_date'] = $_POST['bank_date'];
	$data['bank_hour'] = $_POST['bank_hour'];
	$data['bank_type_payment'] = $_POST['bank_type_payment'];
	$data['bank_name'] = $_POST['bank_name'];
	$data['bank_reference'] = $_POST['bank_reference'];
	$data['bank_mount_payment'] = $_POST['bank_mount_payment'];
	$data['ship_names'] = $_POST['ship_names'];
	$data['ship_vat'] = $_POST['ship_vat'];
	$data['ship_type'] = $_POST['ship_type'];
	$data['ship_address'] = $_POST['ship_address'];
	$data['ship_zp'] = $_POST['ship_zp'];
	$data['ship_parish'] = $_POST['ship_parish'];
	$data['ship_municipality'] = $_POST['ship_municipality'];
	$data['ship_city'] = $_POST['ship_city'];
	$data['ship_state'] = $_POST['ship_state'];
	$data['ship_comments'] = $_POST['ship_comments'];
	
	$data = count($data_edit)>0? $data_edit : $data;
	
	//Formulario ML
	include('template/panel_add_form_ml.html.php');	
}

function front_add_form_ml()
{
	global $validator, $config;
	
	if(!empty($_POST)){
		$rules = array(
		  'user_names' => "required",
		  //'user_names' => "required|valid_name",
		  'user_vat' => "required",
		  'user_phone' => "required",
		  'user_username' => "required",
		  'user_email' => "required",
		  'user_address' => "required",
		  'user_products' => "required",
		  'bank_date' => "required",
		  'bank_hour' => "required",
		  'bank_type_payment' => "required",
		  'bank_name' => "required",
		  'bank_reference' => "required",
		  'bank_mount_payment' => "required",
		  'ship_names' => "required",
		  'ship_vat' => "required",
		  'ship_type' => "required",
		  'ship_address' => "required",
		  'ship_zp' => "required",
		  'ship_parish' => "required",
		  'ship_municipality' => "required",
		  'ship_city' => "required",
		  'ship_state' => "required",
		  //'ship_comments' => "required",
		);

		$valid = $validator->validate( $_POST, $rules );

		if($valid !== true) {
			$errors = '';
			foreach($valid as $v) {
				$errors .= $v['field'].',';
			}
			$errors = substr($errors, 0, -1); 
			//echo '<pre>';
		  //echo print_r($validator->get_readable_errors()); 
		  //echo print_r($validator->get_errors_array());
		  //echo print_r($valid);
		  //echo $errors;
		  //echo '</pre>';
		} else {
			echo bd_set_form_ml( $_POST );
			return;
		}
		
		
	}

	$data['user_names'] = $_POST['user_names'];
	$data['user_vat'] = $_POST['user_vat'];
	$data['user_phone'] = $_POST['user_phone'];
	$data['user_username'] = $_POST['user_username'];
	$data['user_email'] = $_POST['user_email'];
	$data['user_address'] = $_POST['user_address'];
	$data['user_products'] = $_POST['user_products'];
	$data['bank_date'] = $_POST['bank_date'];
	$data['bank_hour'] = $_POST['bank_hour'];
	$data['bank_type_payment'] = $_POST['bank_type_payment'];
	$data['bank_name'] = $_POST['bank_name'];
	$data['bank_reference'] = $_POST['bank_reference'];
	$data['bank_mount_payment'] = $_POST['bank_mount_payment'];
	$data['ship_names'] = $_POST['ship_names'];
	$data['ship_vat'] = $_POST['ship_vat'];
	$data['ship_type'] = $_POST['ship_type'];
	$data['ship_address'] = $_POST['ship_address'];
	$data['ship_zp'] = $_POST['ship_zp'];
	$data['ship_parish'] = $_POST['ship_parish'];
	$data['ship_municipality'] = $_POST['ship_municipality'];
	$data['ship_city'] = $_POST['ship_city'];
	$data['ship_state'] = $_POST['ship_state'];
	$data['ship_comments'] = $_POST['ship_comments'];
	
	//Formulario ML
	include('template/front2_add_form_ml.html.php');
}

function bd_set_form_ml($data)
{
    global $config, $wpdb;
	
	$update_form_ml = $data['form_ml'];
	$form_ml_mail = get_option( 'form_ml_mail', 'error' );
	$bank_hour = $data['bank_hour'];
	$bank_date = $data['bank_date'];
	
	$data['bank_hour'] = str_replace('.', '', $data['bank_hour']);
	$data['bank_date'] = str_replace('/', '-', $data['bank_date']);
	$data['bank_date'] .= ' '.$data['bank_hour'];
	$data['bank_date'] = date("Y-m-d H:i:s", strtotime($data['bank_date']));
	
	unset($data['bank_hour']);
	unset($data['submit']);
	unset($data['form_ml']);

	if($update_form_ml > 0)
		$update_form_ml = $wpdb->update($config['tables']['forms'], $data, array( 'id_form_ml' => $update_form_ml ));
	else {
		$data['ship_status_now'] = $config['STATUS']['POR_VERIFICAR']['id'];
		$wpdb->insert($config['tables']['forms'], $data);
	}
	/*echo $wpdb->last_query;
	echo '\nValor Form ML 222: ', $update_form_ml;
	echo $wpdb->print_error();
	echo $wpdb->show_errors();*/
	//exit( var_dump( $wpdb->last_query ) );
	
	//$msj = get_include_contents('/template/mail/client_register.html.php');
	//var_dump($msj);
	
	$data['bank_hour'] = $bank_hour;
	$data['bank_date'] = $bank_date;
	$data['ship_state'] = $config['STATES'][$data['ship_state']];
	
    if ($wpdb->insert_id) {
		$dataHistories['id_form_ml'] = $wpdb->insert_id;
		$dataHistories['ship_status'] = $data['ship_status_now'];
		$dataHistories['comments'] = $data['ship_comments'];
		$dataHistories['time_modified'] = current_time( 'mysql' );
		$dataHistories['id_user_modifier'] = get_current_user_id();
		
		$wpdb->insert($config['tables']['forms_histories'], $dataHistories);
		//echo $wpdb->last_query;
		$data['id_form_ml'] = $wpdb->insert_id;
		
		$data['ship_status_now'] = $config['STATUS'][$data['ship_status_now']]['name'];
		
		//Client
		$data['subject'] = /*'Se ha creado un nuevo Formulario'*/$config['EMAIL']['new']['client']['subject'];
		$msj = get_include_contents('/template/mail/client_register.html.php', $data);
		//var_dump($msj);
		_send_mail($data['user_email'], $data['subject'], $msj);
		
		//Admin
		$to_admin = /*get_option( 'form_ml_mail', 'diegomanuelsanchez@hotmail.com')*/$config['EMAIL']['reply']['from'];
		$data['subject'] = /*'Se ha creado un nuevo Formulario'*/sprintf($config['EMAIL']['new']['reply']['subject'], $data['bank_name'], $data['user_names']);
		$msj = get_include_contents('/template/mail/admin_register.html.php', $data);
		_send_mail($to_admin, $data['subject'], $msj);
		
		return '<p>Formulario Insertado con &Eacute;XITO</p>'; 
    }else{ 
		if($update_form_ml === false || $update_form_ml == 0 )
			return '<p>Error al insertar el Formulario.</p>';
		else{
			return '<p>Formulario actualizado con &Eacute;XITO</p>'; 		
		}
    }
}

function get_include_contents($filename, $data)
{
	$filename = dirname(__FILE__) . $filename;
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}

function panel_list_form_ml()
{
	global $config;
	
	if(!class_exists('Form_ML_List_Table'))
		require_once('include/class-form-ml-list-table.php' );
	
	$formMLListTable = new Form_ML_List_Table();
    $formMLListTable->prepare_items();
	
	//Lista 
	include('template/panel_list_form_ml.html.php');	
} 

function _send_mail($to, $subject, $message, $message_html = true)
{
	if($message_html)
		add_filter( 'wp_mail_content_type', '_send_set_html_content_type' );
 
 
	add_filter('wp_mail_from','_send_email_from');
	add_filter('wp_mail_from_name','_send_email_from_name');
 
	wp_mail( $to, $subject, $message );
	
	if($message_html)
		remove_filter( 'wp_mail_content_type', '_send_set_html_content_type' );
}

function _send_set_html_content_type()
{
	return 'text/html';
}

function _send_email_from($old)
{
	global $config;
	
	return $config['EMAIL']['sender']['from'];
}

function _send_email_from_name($old)
{
	global $config;
	
	return $config['EMAIL']['sender']['name'];
}

function menu_form_ml()
{ 
    global $_registered_pages;
	
    add_menu_page('Lista de Formularios ML', 'Listar Formularios ML', 'level_7', 'FomulariosML', 'panel_list_form_ml', '', 1);
    add_submenu_page('FomulariosML', 'Formulario ML', 'Formulario ML', 'level_7', 'nuevo-formularioml', 'panel_add_form_ml'); 
} 

add_action("admin_menu", "menu_form_ml");  
add_shortcode( 'front_forms_ml', 'front_add_form_ml' ); 

add_action('activate_Form_ML/form_ml.php','install_form_ml');
add_action('deactivate_Form_ML/form_ml.php', 'desinstall_form_ml');
?>