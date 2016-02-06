


<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.ui.timepicker.addon/1.4.5/jquery-ui-timepicker-addon.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/i18n/jquery-ui-timepicker-es.js"></script>
<script src="//cdn.jsdelivr.net/jquery.ui.timepicker.addon/1.4.5/jquery-ui-sliderAccess.js"></script>
<script>
	$(function() {
		$('#bank_date').datetimepicker({
			altField: "#bank_hour",
			timeFormat: 'hh:mm tt',
			dateFormat: 'dd/mm/yy'
		});
		
		/*$( "#bank_mount_payment" ).spinner({
		  step: 0.01,
		  numberFormat: "n"
		});*/
		var errors = '<?= $errors ?>';
		var arrerror = errors.split(',');
		
		for(var i=0; i < arrerror.length; ++i){
			$( "#" + arrerror[i] ).parent().addClass( "has-error" );
//console.log( '---' + arrerror[i] );
		}
		//alert(arrerror);
		
	});
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>-->

<style>
.rowseparador{
	padding-bottom: 20px;
}
.row input{
	width: 100%;
}
#add_form_ml h2{
	border-bottom: 1px solid #ddd;
	color: #888;
}
span.error-message{
	display: none;
	color: #F00;
}
dd.ui_tpicker_second{
	display: none !important;
}
dd.ui_tpicker_millisec{
	display: none !important;
}
dd.ui_tpicker_microsec{
	display: none !important;
}
</style>

<h1 style="color:#000;">Rellene el formulario s&oacute;lo si compr&oacute; por MercadoLibre</h1>
</br>
<form id="add_form_ml" method="post">

<h2>Datos de Facturaci&oacute;n</h2>
</br>
<div class="container-fluid">
	<div class="row">
	  <div class="col-xs-6"><input type="text" class="form-control" name="user_names" id="user_names" placeholder="Nombre y Apellido" value="<?= $data['user_names'] ?>"></div>
	  <div class="col-xs-6"><input type="text" class="form-control" name="user_vat" id="user_vat" placeholder="C&eacute;dula o RIF" value="<?= $data['user_vat'] ?>"></div>
	</div>
	
	<div class="row">
	  <div class="col-xs-6"><input type="text" class="form-control" name="user_phone" id="user_phone" placeholder="Tel&eacute;fono" value="<?= $data['user_phone'] ?>"></div>
	  <div class="col-xs-6"><input type="text" class="form-control" name="user_username" id="user_username" placeholder="Pseud&oacute;nimo MercadoLibre" value="<?= $data['user_username'] ?>"></div>
	</div>
	
	<div class="row">
	  <div class="col-xs-12"><input type="text" class="form-control" name="user_email" id="user_email" placeholder="Correo Electr&oacute;nico" value="<?= $data['user_email'] ?>"></div>
	</div>
	
	<div class="row rowseparador">
	  <div class="col-xs-12"><textarea class="form-control" rows="3" name="user_address" id="user_address" placeholder="Direcci&oacute;n de Facturaci&oacute;n"><?= $data['user_address'] ?></textarea></div>
	</div>
	
	<div class="row rowseparador">
	  <div class="col-xs-12"><textarea class="form-control" rows="3" name="user_products" id="user_products" placeholder="Equipos Adquiridos, Cantidad y Modelo"><?= $data['user_products'] ?></textarea></div>
	</div>
</div>

<h2>Datos Bancarios</h2>
</br>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6"><input type="text" class="form-control" name="bank_date" id="bank_date" placeholder="Fecha" value="<?= $data['bank_date'] ?>"></div>
		<div class="col-xs-6"><input type="text" class="form-control" name="bank_hour" id="bank_hour" placeholder="Hora" value="<?= $data['bank_hour'] ?>"></div>
	</div>
	
	<div class="row rowseparador">
		<div class="col-xs-12">
			<select name="bank_type_payment" id="bank_type_payment" class="form-control">
				<option value="null" >Seleccione el tipo de pago</option>
				<?php foreach($config['TYPES_PAYMENTS'] as $type_pay) {?>
					<option value="<?= $type_pay['id'] ?>" <?=($data['bank_type_payment']==$type_pay['id']? 'selected' : '')?> > <?= $type_pay['name'] ?> </option>
				<?php }?>
			</select>
		</div>
	</div>
	
	<div class="row">
	  <div class="col-xs-6"><input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Banco Receptor" value="<?= $data['bank_name'] ?>"></div>
	  <div class="col-xs-6"><input type="text" class="form-control" name="bank_reference" id="bank_reference" placeholder="Referencia de la Transacci&oacute;n" value="<?= $data['bank_reference'] ?>"></div>
	</div>
	
	<div class="row">
	  <div class="col-xs-12"><input type="text" class="form-control" name="bank_mount_payment" id="bank_mount_payment" placeholder="Monto de Transacci&oacute;n" value="<?= $data['bank_mount_payment'] ?>"></div>
	</div>
</div>

<h2>Datos de Env&iacute;o</h2>
</br>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6"><input type="text" class="form-control" name="ship_names" id="ship_names" placeholder="Nombre del Receptor" value="<?= $data['ship_names'] ?>"></div>
		<div class="col-xs-6"><input type="text" class="form-control" name="ship_vat" id="ship_vat" placeholder="C&eacute;dula del Receptor" value="<?= $data['ship_vat'] ?>"></div>
	</div>
	
	<div class="row rowseparador">
		<div class="col-xs-12">
			<select name="ship_type" id="ship_type" class="form-control" >
				<option value="null" >Seleccione una empresa de env&iacute;o</option>
				<?php foreach($config['TYPES_SHIPS'] as $type_ship) {?>
					<option value="<?= $type_ship['id'] ?>" <?=($data['ship_type']==$type_ship['id']? 'selected' : '')?> > <?= $type_ship['name'] ?> </option>
				<?php }?>
				</select>
		</div>
	</div>
	
	<div class="row rowseparador">
	  <div class="col-xs-12"><textarea class="form-control" rows="3" name="ship_address" id="ship_address" placeholder="Direcci&oacute;n de Env&iacute;o"><?= $data['ship_address'] ?></textarea></div>
	</div>
	
	<div class="row">
		<div class="col-xs-6"><input type="text" class="form-control" name="ship_zp" id="ship_zp" placeholder="C&oacute;digo Postal" value="<?= $data['ship_zp'] ?>"></div>
		<div class="col-xs-6"><input type="text" class="form-control" name="ship_parish" id="ship_parish" placeholder="Parroquia" value="<?= $data['ship_parish'] ?>"></div>
	</div>
	
	<div class="row">
		<div class="col-xs-6"><input type="text" class="form-control" name="ship_municipality" id="ship_municipality" placeholder="Municipio" value="<?= $data['ship_municipality'] ?>"></div>
		<div class="col-xs-6"><input type="text" class="form-control" name="ship_city" id="ship_city" placeholder="Ciudad" value="<?= $data['ship_city'] ?>"></div>
	</div>
	
	<div class="row rowseparador">
		<div class="col-xs-12">
			<select name="ship_state" id="ship_state" class="form-control" >
				<option value="null">Seleccione el Estado</option>
				<?php foreach($config['STATES'] as $state_id => $state_val) {?>
					<option value="<?= $state_id ?>" <?=($data['ship_state']==$state_id? 'selected' : '')?> > <?= $state_val ?> </option>
				<?php }?>
			</select>
		</div>
	</div>
	
	<div class="row rowseparador">
	  <div class="col-xs-12"><textarea class="form-control" rows="3" name="ship_comments" id="ship_comments" placeholder="Comentarios Adicionales"><?= $data['ship_comments'] ?></textarea></div>
	</div>
</div>

<div class="container-fluid">
	<div class="row rowseparador">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Enviar Formulario">
	</div>
</div>

</form>