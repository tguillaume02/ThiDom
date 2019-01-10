<?php
include_once dirname(__FILE__) .'/../../../../Core/Security.php'; 
include_once dirname(__FILE__) .'/../../../../Core/ListRequire.php';
$ModuleName = "NRF24";
$data =getParameter("data");
$configuration = json_decode($data["ModuleConfiguration"], true);
?>

<div class="col-lg-8 col-md-6 col-sm-6 col-xs-6">
    <div class="form-group">
        <label for="Com" class="col-lg-2 col-md-5 col-sm-5 col-xs-5 control-label">Port du NRF24 :</label>
        <div class="col-lg-8 col-md-4 col-sm-4 col-xs-4">            
            <select id="Com" name="Com" class="form-control">
                <option value="">Aucun</option>
                <?php
                foreach (Module::List_usb("") as $name => $value)
                {
                    $selected = ($configuration["com"] == $name) ? "selected" : "";
                    echo '<option value="' . $name . '" '.$selected.'>' . $name . ' (' . $value . ')</option>';
                }
                ?>
            </select>
        </div>
    </div>
</div>

