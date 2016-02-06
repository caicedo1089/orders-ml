<?php

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary. In this tutorial, we are
 * going to use the WP_List_Table class directly from WordPress core.
 *
 * IMPORTANT:
 * Please note that the WP_List_Table class technically isn't an official API,
 * and it could change at some point in the distant future. Should that happen,
 * I will update this plugin with the most current techniques for your reference
 * immediately.
 *
 * If you are really worried about future compatibility, you can make a copy of
 * the WP_List_Table class (file path is shown just below) to use and distribute
 * with your plugins. If you do that, just remember to change the name of the
 * class to avoid conflicts with core.
 *
 * Since I will be keeping this tutorial up-to-date for the foreseeable future,
 * I am going to work with the copy of the class provided in WordPress core.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}




/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 * 
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 * 
 * Our theme for this list table is going to be movies.
 */
class Form_ML_List_Table extends WP_List_Table {

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
	 
	
    function __construct()
	{
        global $status, $page, $to_admin;
        
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'form_ml',     //singular name of the listed records
            'plural'    => 'forms_ml',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }

    function column_default($item, $column_name)
	{
        switch($column_name){
            case 'user_products':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_user_ML($item)
	{
        
        return sprintf('<strong>%1$s</strong>  (%2$s)</br> <span style="color:silver;"><strong>%3$s - Telf: %4$s</strong></span></br><span style="color:silver;">%5$s</span>',
            /*$1%s*/ $item['user_names'],
            /*$2%s*/ $item['user_vat'],
			/*$3%s*/ $item['user_username'],
			/*$4%s*/ $item['user_phone'],
			/*$5%s*/ $item['user_email']
        );
    }

	function column_user_bank($item)
	{
		global $config;
		
		$date = date_create($item['bank_date']);
		return sprintf('<strong>%1$s</strong>  (%2$s)</br> <span style="color:silver;"><strong>%3$s</strong></span></br><span style="color:silver;">%4$s</span>',
            /*$1%s*/ date_format($date, 'd/m/Y'),
            /*$2%s*/ date_format($date, 'g:i A'),
			/*$3%s*/ $config['TYPES_PAYMENTS'][$item['bank_type_payment']]['name'],
			/*$4%s*/ $item['bank_mount_payment']
        );
    }

	function column_ship($item)
	{
		global $config;
		
        return sprintf('<strong>%1$s</strong>  (%2$s)</br> <span style="color:silver;"><strong>%3$s - ZP: %4$s</strong></span></br><span style="color:silver;">%5$s - %6$s</span>',
            /*$1%s*/ $item['ship_names'],
            /*$2%s*/ $item['ship_vat'],
			/*$3%s*/ $item['ship_type'],
			/*$4%s*/ $item['ship_zp'],
			/*$5%s*/ $item['ship_city'],
			/*$6%s*/ $config['STATES'][$item['ship_state']]
        );
    }
	
	function column_status($item)
	{
		global $config;
		
        return sprintf('<span style="background-color: %1$s; color: #FFF; padding: 3px 5px; border-radius: 3px;">%2$s</span>',
            /*$1%s*/ $config['STATUS'][$item['ship_status_now']]['color'],
            /*$2%s*/ $config['STATUS'][$item['ship_status_now']]['name']
        );
    }
	
	function column_actions($item)
	{
		global $wpdb, $config;
		
        $actions = array(
			'change_status' => sprintf('<a class="change_status" href="#%s" >Cambiar Estatus</a>',$item['id_form_ml']),
            'view_history'  => sprintf('<a href="#%s">Historial</a>',$item['id_form_ml']),
			'view'          => sprintf('<a href="#%s">Ver</a>',$item['id_form_ml']),
			'edit'          => sprintf('<a href="?page=%s&action=%s&form_ml=%s">Actualizar</a>','nuevo-formularioml'/*$_REQUEST['page']*/,'edit',$item['id_form_ml']),
            'delete'        => sprintf('<a href="?page=%s&action=%s&form_ml=%s">Eliminar</a>',$_REQUEST['page'],'delete',$item['id_form_ml']),
        );
		
		//Ver el Formulario
		$date = date_create($item['bank_date']);
		echo sprintf(
			'<div id="view_%s" style="display:none">
				<h3>Datos de Facturaci&oacute;n</h3>
				<div class="left">
				<div><b>Nombre:</b> %s</div> <div class="separador"></div>
				<div><b>CI/RIF:</b> %s</div> <div class="separador"></div>
				<div><b>Telf:</b> %s</div> <div class="separador"></div>
				<div><b>Pseud&oacute;nimo ML:</b> %s</div> <div class="separador"></div>
				<div><b>Correo:</b> %s</div> <div class="separador"></div>
				<div><b>Direcci&oacute;n de Facturaci&oacute;n:</b>  %s</div> <div class="separador"></div>
				<div><b>Equipo Adquiridos, Cantidad y Modelo:</b> %s</div> <div class="separador"></div>
				</div>
				<div class="clear"></div>
				<h3>Datos Bancarios</h3>
				<div class="left">
				<div><b>Fecha:</b> %s</div> <div class="separador"></div>
				<div><b>Hora:</b> %s</div> <div class="separador"></div>
				<div><b>Tipo de Pago:</b> %s</div> <div class="separador"></div>
				<div><b>Banco Emisor:</b> %s</div> <div class="separador"></div>
				<div><b>Referencia de la Transacci&oacute;n:</b> %s</div> <div class="separador"></div>
				<div><b>Monto de Transacci&oacute;n:</b> %s</div> <div class="separador"></div>
				</div>
				<div class="clear"></div>
				<div>
					<h3>Datos de Env&iacute;o</h3>
					<div class="left">
						<div><b>Nombre del Receptor:</b> %s</div> <div class="separador"></div>
						<div><b>CI del Receptor:</b> %s</div> <div class="separador"></div>
						<div><b>Empresa de Env&iacute;o:</b> %s</div> <div class="separador"></div>
						<div><b>Direcci&oacute;n de Env&iacute;o:</b> %s</div> <div class="separador"></div>
						<div><b>C&oacute;digo Postal:</b> %s</div> <div class="separador"></div>
						<div><b>Parroquia:</b> %s</div> <div class="separador"></div>
						<div><b>Municipio:</b> %s</div> <div class="separador"></div>
						<div><b>Ciudad:</b> %s</div> <div class="separador"></div>
						<div><b>Estado:</b> %s</div> <div class="separador"></div>
						<div><b>Comentarios Adicionales:</b> %s</div> <div class="separador"></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>'
			,
			$item['id_form_ml'], $item['user_names'], $item['user_vat'], $item['user_phone'], 
			$item['user_username'], $item['user_email'], $item['user_address'] ,$item['user_products'], 
			date_format($date, 'd/m/Y'), date_format($date, 'g:i A'), $item['bank_type_payment'],$item['bank_name'], $item['bank_reference'], $item['bank_mount_payment'],
			$item['ship_names'], $item['ship_vat'], $item['ship_type'], $item['ship_address'], $item['ship_zp'],
			$item['ship_parish'], $item['ship_municipality'], $item['ship_city'], $config['STATES'][$item['ship_state']],$item['ship_comments']
			);
		
		//Imprimir Nota de entrega
		//echo 'Nota de Crédito';
		echo sprintf(
			'<div id="print_%s" style="display:none">
			<div class="print">
				<div>
					<table width="100%%">
						<tr>
							<td width="50%%" style="font-size: 24px; font-weight: bold;">Albar&aacute;n de Entrega</td>
							<td width="50%%">
								<div class="left">
									<div><b>SuperLink, C.A. RIF: J-29530620-2</b></div><div class="separador"></div>
									<div><b>Persona Contacto:</b> Ella Ferrer</div> <div class="separador"></div>
									<div><b>Tel&eacute;fono:</b> 0414-7355493</div> <div class="separador"></div>
								</div>
								<div class="clear"></div>
							</td>
						</tr>
					</table>
				</div>
				</br>
				<div>
					<h3>Datos Generales</h3>
					<div class="left">
						<div><b>Fecha de pago del Pedido:</b> %s</div> <div class="separador"></div>
						<div><b>Orden n&uacute;mero:</b> %s</div> <div class="separador"></div>
						<div><b>Valor del paquete:</b> %s</div> <div class="separador"></div>
						<div><b>Contenido del paquete:</b> %s</div> <div class="separador"></div>
						<div><b>Nombre del Cliente:</b> %s</div> <div class="separador"></div>
						<div><b>C&eacute;dula/RIF del Cliente:</b> %s</div> <div class="separador"></div>
						<div><b>Teléfono del Cliente:</b> %s</div> <div class="separador"></div>
					</div>
					<div class="clear"></div>
					<h3>Datos de Env&iacute;o</h3>
					<div class="left">
						<div><b>Nombre del Receptor:</b> %s</div> <div class="separador"></div>
						<div><b>CI del Receptor:</b> %s</div> <div class="separador"></div>
						<div><b>Empresa de Env&iacute;o:</b> %s</div> <div class="separador"></div>
						<div><b>Direcci&oacute;n de Env&iacute;o:</b> %s</div> <div class="separador"></div>
						<div><b>C&oacute;digo Postal:</b> %s</div> <div class="separador"></div>
						<div><b>Parroquia:</b> %s</div> <div class="separador"></div>
						<div><b>Municipio:</b> %s</div> <div class="separador"></div>
						<div><b>Ciudad:</b> %s</div> <div class="separador"></div>
						<div><b>Estado:</b> %s</div> <div class="separador"></div>
						<div><b>Comentarios Adicionales:</b> %s</div> <div class="separador"></div>
					</div>
					<div class="clear"></div>
				</div>
			</div>	
			</div>'
			,$item['id_form_ml'],date_format($date, 'd/m/Y'), $item['id_form_ml'],$item['bank_mount_payment'], $item['user_products'],
			$item['user_names'],$item['user_vat'], $item['user_phone'],
			$item['ship_names'], $item['ship_vat'], $item['ship_type'], $item['ship_address'], $item['ship_zp'], $item['ship_parish'], $item['ship_municipality'], $item['ship_city'], 
			$config['STATES'][$item['ship_state']], $item['ship_comments']);
		
		//Historial del Formulario
		$histories = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$config['tables']['forms_histories']} WHERE id_form_ml = {$item['id_form_ml']} ORDER BY time_modified DESC" ), ARRAY_A );
		
		echo '<div id="history_',$item['id_form_ml'],'" style="display:none"><table >
		<tr style="border-top:none;"><th>Fecha</th><th>Estatus</th><th>Comentario</th></tr>';
		
		foreach($histories as $history){
			$date = date_create($history['time_modified']);
			echo '<tr>';
			//echo print_r($history);
			echo '<td>',date_format($date, 'd/m/Y'),' ', date_format($date, 'g:i A'),'</td>';
			echo '<td>',sprintf('<span style="background-color: %1$s; color: #FFF; padding: 3px 5px; border-radius: 3px;">%2$s</span>', $config['STATUS'][$history['ship_status']]['color'], $config['STATUS'][$history['ship_status']]['name']),'</td>';
			echo '<td>',$history['comments'],'</td>';
			echo '</tr>';
		}
		echo '</table></div>';
	
		//Mejorar se ocultan las acciones
		return $this->row_actions($actions);
    }
	
    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['id_form_ml']                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'user_ML'     => 'Usuario ML',
			'user_products'    => 'Productos',
            'user_bank'    => 'Deposito',
            'ship'  => 'Envio',
			'status'  => 'Estatus',
			'actions'  => 'Acciones',
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() { //Ojo actualizar
        $sortable_columns = array(
            'user_ML'     => array('user_ML',false),     //true means it's already sorted
            'user_names'    => array('user_names',false),
            'user_username'  => array('user_username',false)
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
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
	
	function _send_mail($to, $subject, $message, $message_html = true)
	{
		if($message_html)
			add_filter( 'wp_mail_content_type', '_send_set_html_content_type' );
	 
		add_filter('wp_mail_from','_send_email_from');
		add_filter('wp_mail_from_name','_send_email_from_name');
		
		$result = wp_mail( $to, $subject, $message );
		
		if (!$result){
			echo 'Error';
			global $phpmailer;
			if (isset($phpmailer)) {
				echo print_r ($phpmailer->ErrorInfo);
			}
		}
		
		
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
    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action()
	{
        global $wpdb, $config; //This is used only if making any database queries
		
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            //wp_die('Items deleted (or they would be if we had items to delete)!');
			$where['id_form_ml'] = $_GET['form_ml'];
			$wpdb->delete( $config['tables']['forms'], $where );
			$wpdb->delete( $config['tables']['forms_histories'], $where );
			return;
        }
        
		if( 'change_status'===$this->current_action() ) {

			$data['ship_status_now'] = $_GET['status'];
			$where['id_form_ml'] = $_GET['form_ml'];
			
			if ($wpdb->update($config['tables']['forms'], $data, $where)) {
				$dataHistories['id_form_ml'] = $_GET['form_ml'];
				$dataHistories['ship_status'] = $_GET['status'];
				$dataHistories['comments'] = $_GET['comments'];
				$dataHistories['time_modified'] = current_time( 'mysql' );
				$dataHistories['id_user_modifier'] = get_current_user_id();
				$date = date_create($dataHistories['time_modified']);
				
				
				$wpdb->insert($config['tables']['forms_histories'], $dataHistories);
				
				$data = $wpdb->get_row( "SELECT * FROM {$config['tables']['forms']} WHERE id_form_ml = {$dataHistories['id_form_ml']}", ARRAY_A);
				
				$dataHistories['subject'] = 'Ha cambiando el Estatus del Pedido #'.$dataHistories['id_form_ml'];
				$dataHistories['user_names'] = $data['user_names'];
				$dataHistories['user_username'] = $data['user_username'];
				$dataHistories['user_email'] = $data['user_email'];
				$dataHistories['ship_date'] = date_format($date, 'd/m/Y');
				$dataHistories['ship_hour'] = date_format($date, 'g:i A');
				//$dataHistories['user'] = $data;
				foreach($data as $k => $v )
					$dataHistories[$k] = $v;
				
				$status_mail = '';
				$status_subject_client = $dataHistories['subject'];
				
				switch($dataHistories['ship_status']){
					case 'VERIFICADO':
					$status_subject_client = sprintf('[.::%s::.] El pago de tu orden %s ya fue confirmado...', $config['EMAIL']['sender']['name'], $dataHistories['id_form_ml']);
					$status_mail = $dataHistories['ship_status'];
					break;
					case 'ENVIADO':
					$status_subject_client = sprintf('[.::%s::.] Tu pedido %s ya fue enviado...', $config['EMAIL']['sender']['name'], $dataHistories['id_form_ml']);
					$status_mail = $dataHistories['ship_status'];
					break;
					case 'ENTREGADO':
					$status_subject_client = sprintf('Tu pedido en Superlinkca.com pagado el %s se ha completado...', $dataHistories['ship_date']);
					$status_mail = $dataHistories['ship_status'];
					break;
					default:
					$status_mail = 'DEFAULT';
				}
				
				$mail_client = '/../template/mail/client_change_status_'.$status_mail. '.html.php';
				
				//echo $mail_client;
				//echo $status_subject_client;
				//echo $dataHistories['user_email'];
				//echo print_r($dataHistories);
				 
				$dataHistories['ship_status'] = $config['STATUS'][$dataHistories['ship_status']]['name'];
				$dataHistories['ship_state'] = $config['STATES'][$dataHistories['ship_state']];
				$dataHistories['ship_type'] = $config['TYPES_SHIPS'][$dataHistories['ship_type']]['name'];
				
				
				//Client
				$msj = $this->get_include_contents($mail_client, $dataHistories);
				$this->_send_mail($dataHistories['user_email'], $status_subject_client, $msj);
				
				//Admin
				$msj = $this->get_include_contents('/../template/mail/admin_change_status.html.php', $dataHistories);
				//$this->_send_mail($config['EMAIL']['reply']['from'], $status_subject_client, $msj);
				
				return;
			}
        }
		
		/*if('view'===$this->current_action()){
			
		}*/
    }


    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        global $wpdb, $config; //This is used only if making any database queries
		
        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        //$data = $this->example_data;
		$_GET['tab'] = $_GET['tab']? $_GET['tab'] : $config['STATUS']['POR_VERIFICAR']['id'];
		$data = $wpdb->get_results( "SELECT * FROM {$config['tables']['forms']} WHERE ship_status_now = '{$_GET['tab']}'", ARRAY_A);
		//echo $wpdb->last_query;
		//echo print_r($_GET);
		
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}