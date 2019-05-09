<?php
require_once dirname(__FILE__) .'/../../ListRequire.php';

$Device_id = getParameter("deviceId");
$WidgetName = getParameter("deviceWidgetType");

echo '
<label class="btn btn-success">
    <input type="radio" name="commande" id="action-on" value="1" />On
</label>
<label class="btn btn-danger">
    <input type="radio" name="commande" id="action-off" value="0" />Off
</label>';

?>