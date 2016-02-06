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
		
		$( "#bank_mount_payment" ).spinner({
		  step: 0.01,
		  numberFormat: "n"
		});
	});
</script>

<div class="wrap" id="form_ml">
<h1><?= $data['form_ml']? 'Actualizar' : 'Agregar' ?> Formulario ML</h1>
<form id="add_form_ml" method="post">

<h2>Datos de Facturaci&oacute;n</h2>
<table class="form-table">
	<tbody>
		<tr class="">
			<th><label for="user_names">Nombre y Apellido</label></th>
			<td><input type="text" name="user_names" id="user_names" value="<?=$data['user_names']?>" class="regular-text"> <span class="description"></span></td>
		</tr>

		<tr class="">
			<th><label for="user_vat">C&eacute;dula o RIF</label></th>
			<td><input type="text" name="user_vat" id="user_vat" value="<?=$data['user_vat']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="user_phone">Tel&eacute;fono</label></th>
			<td><input type="text" name="user_phone" id="user_phone" value="<?=$data['user_phone']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="user_username">Pseud&oacute;nimo MercadoLibre</label></th>
			<td><input type="text" name="user_username" id="user_username" value="<?=$data['user_username']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="user_email">Correo Electr&oacute;nico</label></th>
			<td><input type="text" name="user_email" id="user_email" value="<?=$data['user_email']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="user_address">Direcci&oacute;n de Facturaci&oacute;n</label></th>
			<td><textarea name="user_address" id="user_address" rows="5" cols="46" ><?=$data['user_address']?></textarea>
			<p class=""></p></td>
		</tr>
		
		<tr class="">
			<th><label for="user_products">Equipos Adquiridos, Cantidad y Modelo</label></th>
			<td><textarea name="user_products" id="user_products" rows="5" cols="46" ><?=$data['user_products']?></textarea>
			<p class=""></p></td>
		</tr>
	</tbody>
</table>
</br>
<h2>Datos Bancarios</h2>
<table class="form-table">
	<tbody>
		<tr class="">
			<th><label for="bank_date">Fecha y Hora de Dep&oacute;sito</label></th>
			<td><input type="text" name="bank_date" id="bank_date" value="<?=$data['bank_date']?>" class="regular-text"> <span class="description"></span></td>
			<td><input type="text" name="bank_hour" id="bank_hour" value="<?=$data['bank_hour']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="bank_type_payment">Tipo de Pago</label></th>
			<td>
				<select name="bank_type_payment" id="bank_type_payment" style="width: 348px;">
					<?php foreach($config['TYPES_PAYMENTS'] as $type_pay) {?>
						<option value="<?= $type_pay['id'] ?>" <?=($data['bank_type_payment']==$type_pay['id']? 'selected' : '')?> > <?= $type_pay['name'] ?> </option>
					<?php }?>
				</select>
			</td>
		</tr>
		
		<tr class="">
			<th><label for="bank_name">Banco Receptor</label></th>
			<td><input type="text" name="bank_name" id="bank_name" value="<?=$data['bank_name']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="bank_reference">Referencia de la Transacci&oacute;n</label></th>
			<td><input type="text" name="bank_reference" id="bank_reference" value="<?=$data['bank_reference']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="bank_mount_payment">Monto de Transacci&oacute;n</label></th>
			<td><input type="text" name="bank_mount_payment" id="bank_mount_payment" style="width: 317px;" value="<?=$data['bank_mount_payment']?>" class="regular-text"></td>
		</tr>

	</tbody>
</table>
</br>
<h2>Datos de Env&iacute;o</h2>
<table class="form-table">
	<tbody>
		<tr class="">
			<th><label for="ship_names">Nombre del Receptor</label></th>
			<td><input type="text" name="ship_names" id="ship_names" value="<?=$data['ship_names']?>" class="regular-text"> <span class="description"></span></td>
		</tr>

		<tr class="">
			<th><label for="ship_vat">C&eacute;dula del Receptor</label></th>
			<td><input type="text" name="ship_vat" id="ship_vat" value="<?=$data['ship_vat']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="ship_type">Empresa de Env&iacute;o</label></th>
			<td>
				<select name="ship_type" id="ship_type" style="width: 348px;">
					<?php foreach($config['TYPES_SHIPS'] as $type_ship) {?>
						<option value="<?= $type_ship['id'] ?>" <?=($data['ship_type']==$type_ship['id']? 'selected' : '')?> > <?= $type_ship['name'] ?> </option>
					<?php }?>
				</select>
			</td>
		</tr>
		
		<tr class="">
			<th><label for="ship_address">Direcci&oacute;n de Env&iacute;o</label></th>
			<td><textarea name="ship_address" id="ship_address" rows="5" cols="46" ><?=$data['ship_address']?></textarea>
			<p class=""></p></td>
		</tr>
		
		<tr class="">
			<th><label for="ship_zp">C&oacute;digo Postal</label></th>
			<td><input type="text" name="ship_zp" id="ship_zp" value="<?=$data['ship_zp']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="ship_parish">Parroquia</label></th>
			<td><input type="text" name="ship_parish" id="ship_parish" value="<?=$data['ship_parish']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="ship_municipality">Municipio</label></th>
			<td><input type="text" name="ship_municipality" id="ship_municipality" value="<?=$data['ship_municipality']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="ship_city">Ciudad</label></th>
			<td><input type="text" name="ship_city" id="ship_city" value="<?=$data['ship_city']?>" class="regular-text"></td>
		</tr>
		
		<tr class="">
			<th><label for="ship_state">Estado</label></th>
			<td>
				<select name="ship_state" id="ship_state" style="width: 348px;">
					<?php foreach($config['STATES'] as $state_id => $state_val) {?>
						<option value="<?= $state_id ?>" <?=($data['ship_state']==$state_id? 'selected' : '')?> > <?= $state_val ?> </option>
					<?php }?>
				</select>
			</td>
		</tr>
		
		<tr class="">
			<th><label for="ship_comments">Comentarios Adicionales</label></th>
			<td><textarea name="ship_comments" id="ship_comments" rows="5" cols="46" ><?=$data['ship_comments']?></textarea>
			<p class=""></p></td>
		</tr>

	</tbody>
</table>

<input type="hidden" name="form_ml" id="form_ml" value="<?= $data['form_ml'] ?>">

<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?= $data['form_ml']? 'Actualizar' : 'Agregar' ?> Formulario"></p>
</form>
</div>