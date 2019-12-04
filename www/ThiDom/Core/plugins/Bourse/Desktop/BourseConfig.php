<?php

$vua = $Cmd->get_Request("vua", "");
$nb = $Cmd->get_Request("nb", "");
$tplWidgetConfig .= '
<div class="row">
	<div class="col-lg-12">
		<div class="form-group">
			<label for="min" class="col-lg-2  control-label">Valeur unitaire d\'achat</label>	
			<div class="col-lg-2">
            <input type="number" step="0.01" class="form-control" id="vua"  name="vua" placeholder="Valeur unitaire achat" cmdid ="'.$Cmd->get_Id().'" value="'.$vua.'" request=1>
			</div>
			<label for="max" class="col-lg-2 control-label">Nombre</label>	
			<div class="col-lg-2">		
            <input class="form-control" id="Nombre" name="nb" cmdid ="'.$Cmd->get_Id().'" value="'.$nb .'" placeholder="Nombre" request=1>
			</div>
		</div>
	</div>
</div>';
?>
