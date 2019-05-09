<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
$ModuleName = "NRF24";
$configuration = json_decode(getParameter("ModuleConfiguration"), true);
$selected = "";
$modelidSelected = "";
$vendoridSeletect = "";
$baudrateSelected = "";
?>
<script>
    $("#com").change(function()
    {
        var modelId = $("option:selected", this).attr("modelid") != undefined ? $("option:selected", this).attr("modelid") : '';
        var vendrorId = $("option:selected", this).attr("vendorid") != undefined ? $("option:selected", this).attr("vendorid") : '';
        var baudrate = $("option:selected", this).attr("baudrate") != undefined ? $("option:selected", this).attr("baudrate") : '';
        $("#modelid").val(modelId).attr("value",modelId);
        $("#vendorid").val(vendrorId).attr("value",vendrorId);
        $("#baudrate").val(baudrate).attr("value",baudrate);
    })
</script>
<div class="col-lg-8 col-md-6 col-sm-6 col-xs-6">
    <div class="form-group">
        <label for="com" class="col-lg-2 col-md-5 col-sm-5 col-xs-5 control-label">Port du NRF24 :</label>
        <div class="col-lg-8 col-md-4 col-sm-4 col-xs-4">            
            <select id="com" name="com" class="form-control">
                <option value="">Aucun</option>
                <?php
                foreach (Module::List_usb("") as $module)
                {
                    if ($configuration["com"] == $module["link"])
                    {
                        $selected = "selected";
                        $modelidSelected = $module["modelid"];
                        $vendoridSeletect = $module["vendorid"];
                        $baudrateSelected = $module["baudrate"];
                    }
                    echo '<option value="' .  $module["link"] . '" modelid='.$module["modelid"].' vendorid='.$module["vendorid"].' baudrate='.$module["baudrate"].' '.$selected.'>' . $module["vendor"]. ' '.$module["model"] . ' (' . $module["link"] . ')</option>';
                }
                ?>
            </select>
            <input type="text" id="modelid" name="modelid"  class="form-control" value="<?php echo $modelidSelected?>" style="display:none"/>
            <input type="text" id="vendorid" name="vendorid"  class="form-control" value="<?php echo $vendoridSeletect?>" style="display:none"/>
            <input type="text" id="baudrate" name="baudrate"  class="form-control" value="<?php echo $baudrateSelected?>" style="display:none"/>
        </div>
    </div>
</div>

<div class="btn btn-primary pull-center" onclick="ShowTreeNetwork()">Show tree network</div>

<div id="TreeNetwork" style="display:none"></div>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.21.0/vis.min.css"/>

<script>
	function ShowTreeNetwork()
	{
		var request = $.ajax({
			type: 'POST',
			dataType: "json",
			url: 'Core/plugins/NRF24/Desktop/NRF24_ajax.php',		
			data: "act=GetTree"
		});

		request.done(function (data) {
            $("#TreeNetwork").show();
            $("#TreeNetwork").height($(document).height()-350);
            // create an array with nodes
            var nodes = new vis.DataSet(data[0]);

            // create an array with edges
            var edges = new vis.DataSet(data[1]);

            // create a network
            var container = document.getElementById('TreeNetwork');
            var data = {
                nodes: nodes,
                edges: edges
            };
            var options = {};
            var network = new vis.Network(container, data, options);

		});
        
        request.fail(function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
        });
	}
</script>