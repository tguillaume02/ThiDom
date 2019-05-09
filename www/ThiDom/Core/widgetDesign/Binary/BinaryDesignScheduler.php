<?php
require_once dirname(__FILE__) .'/../../ListRequire.php';

$Device_id = getParameter("deviceId");
$WidgetName = getParameter("deviceWidgetType");
$status = getParameter("status");

echo '
<td class="Binary Relay widgetType">
    <label class="btn btn-success">
        <input type="radio" name="commande" id="action-on" value="1" '.($status == 1 ? 'checked' : '').'  />On
    </label>
    <label class="btn btn-danger">
        <input type="radio" name="commande" id="action-off" value="0" '.($status == 0 ? 'checked' : '').' />Off
    </label>
</td>';

?>