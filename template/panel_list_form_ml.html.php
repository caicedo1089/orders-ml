<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
<script src="http://superlinkca.com/wp-content/plugins/Form_ML/assets/js/jquery.printarea.js"></script>

<script>
$(function() {
	var dialogChangeEstatus, dialogView, dialogHistory, form, id_forms_ml, forms_action, tab_status = '<?=$_GET['tab']?>';
	
	dialogChangeEstatus = $( "#dialog-change-status" ).dialog({
      autoOpen: false,
      height: 400,
      width: 450,
      modal: true,
      buttons: {
        "Actualizar Estatus": function(){
			var pag = "?";
			pag += "page=" + $('input[name=page]').val() +"&";
			pag += "action=" + forms_action +"&";
			pag += "form_ml=" + id_forms_ml +"&";
			pag += "status=" + $('#ship_status').val() +"&";
			pag += "comments=" + $('#comments').val() + "&";
			pag += "tab=" + tab_status +"&";
			
			//console.log(pag);
			window.location = pag;
		},
        Cancelar: function() {
          dialogChangeEstatus.dialog( "close" );
		  form[ 0 ].reset();
        }
      },
      close: function() {
        form[ 0 ].reset();
      }
    });
 
	dialogView = $( "#dialog-view" ).dialog({
      autoOpen: false,
      height: 600,
      width: 800,
      modal: true,
      buttons: {
        "Actualizar": function(){
			var pag = "?";
			pag += "page=" + 'nuevo-formularioml' +"&";
			pag += "action=" + 'edit' +"&";
			pag += "form_ml=" + id_forms_ml +"&";
			pag += "tab=" + tab_status +"&";
			//console.log(pag);
			window.location = pag;
		},
		"Imprimir":function(){
			//$('#dialog-view #PrinShip_'+id_forms_ml).printArea( );	
			$('#print_'+id_forms_ml+' .print').printArea( );	
			//$('.UnoUno').printArea( );	
		},
        Cancelar: function() {
          dialogView.dialog( "close" );
		  //form[ 0 ].reset();
        }
      },
      close: function() {
        //form[ 0 ].reset();
      }
    });
	
	dialogHistory = $( "#dialog-history" ).dialog({
      autoOpen: false,
      height: 400,
      width: 800,
      modal: true,
      buttons: {
        /*"Actualizar": function(){
			var pag = "?";
			pag += "page=" + 'nuevo-formularioml' +"&";
			pag += "action=" + 'edit' +"&";
			pag += "form_ml=" + id_forms_ml +"&";
			//console.log(pag);
			window.location = pag;
		},*/
        Cancelar: function() {
          dialogHistory.dialog( "close" );
		  //form[ 0 ].reset();
        }
      },
      close: function() {
        //form[ 0 ].reset();
      }
    });
	
    form = dialogChangeEstatus.find( "form" )/*.on( "submit", function( event ) { event.preventDefault(); })*/;
	
	$('a.change_status').click(function(){
		id_forms_ml = ($(this).attr('href') + "").replace("#","");
		forms_action = $(this).attr('class');
		dialogChangeEstatus.dialog( "open" );
	});
	
	$('span.view').click(function(){
		id_forms_ml = ($(this).find("a").attr('href') + "").replace("#","");
		forms_action = $(this).attr('class');
		$('#dialog-view').html($('#view_'+id_forms_ml).html());
		//$('#dialog-view').html($('#print_'+id_forms_ml).html());
		dialogView.dialog( "open" );
	});
	
	$('span.view_history').click(function(){
		id_forms_ml = ($(this).find("a").attr('href') + "").replace("#","");
		forms_action = $(this).attr('class');
		$('#dialog-history').html($('#history_'+id_forms_ml).html());
		dialogHistory.dialog( "open" );
	});
	
	$( "#formML-list" ).tabs({
		  collapsible: true,
		  active: <?php 
		  $i = 0;
		  foreach($config['STATUS'] as $status){
			if($status['id']==$_GET['tab']){
				echo $i;
				break;
			}
			$i++;
		}?>
	});
	
	$( "#formML-list ul a" ).click(function(){
		tab_status = $(this).attr("href");
		
		var pag = "?";
		pag += "page=" + 'FomulariosML' +"&";
		pag += "tab=" + tab_status +"&";
		
		window.location = pag;
	});
	
});
</script>

<style>
#dialog-view  h3 {border-bottom: 1px solid #000;}
#dialog-view .left div{min-height: 28px; padding-top:3px;}
#dialog-view .left div.separador{display:none;}
#dialog-view .clear{clear: both;}

#dialog-history table{width: 100%; border: 1px solid #000;}
#dialog-history td{height: 32px; border-top: 1px solid #000;}
</style>

<div id="dialog-change-status" title="Cambiando Estatus a Formulario MercadoLibre" style="display:none;">
<p class="validateTips">Todos los campos son requeridos.</p>
  <form>
    <fieldset>
		<p>
			<label for="ship_status">Estatus:</label>
			<select id="ship_status" name="ship_status">'
				<?php foreach($config['STATUS'] as $status) {?>
					<option value="<?= $status['id'] ?>"><?= $status['name'] ?></option>
				<?php }?>
			</select>
		</p>
		<p>
			<label for="comments">Comentario:</label>
			<textarea name="comments" id="comments" rows="7" cols="46" ></textarea>
		</p>
 
		<!-- Allow form submission with keyboard without duplicating the dialog button -->
		<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>

<div id="dialog-view" title="Formulario MercadoLibre" style="display:none;">
</div>

<div id="dialog-history" title="Historia del Formulario" style="display:none;">
</div>

<div id="icon-users" class="icon32"><br/></div>
	<h2>Lista de Formularios MercadoLibre</h2>
	</br>
<div class="wrap" id="formML-list">

	<ul>
		<?php foreach($config['STATUS'] as $status) {?>
			<li><a href="<?= $status['id'] ?>"><?= $status['name'] ?></a></li>
		<?php }?>
	</ul>
	
	<form id="movies-filter" method="get">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php $formMLListTable->display() ?>
	</form>
	
</div>