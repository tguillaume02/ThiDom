<?php

$vua = $Cmd->get_Request("vua", "");
$nb = $Cmd->get_Request("nb", "");
$ref = $Cmd->get_Request("ref", "");
$tplWidgetConfig .= '
<div class="row">
	<div class="form-group">
		<div class="col-lg-12">		
			<label for="min" class="col-lg-2  control-label">Reference</label>	
			<div class="col-lg-2">
				<input class="form-control" id="reference'.$Cmd->get_Id().'" name="ref" cmdid ="'.$Cmd->get_Id().'" value="'.$ref.'" placeholder="Reference" request=1>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="form-group">
			<label for="min" class="col-lg-2  control-label">Valeur unitaire d\'achat</label>	
			<div class="col-lg-2">
            	<input type="number" step="0.01" class="form-control" id="vua'.$Cmd->get_Id().'"  name="vua" placeholder="Valeur unitaire achat" cmdid ="'.$Cmd->get_Id().'" value="'.$vua.'" request=1>
			</div>
			<label for="max" class="col-lg-2 control-label">Nombre</label>	
			<div class="col-lg-2">		
            	<input class="form-control" id="Nombre'.$Cmd->get_Id().'" name="nb" cmdid ="'.$Cmd->get_Id().'" value="'.$nb .'" placeholder="Nombre" request=1>
			</div>
		</div>
	</div>
</div>';
?>
