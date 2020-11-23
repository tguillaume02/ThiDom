<?php
header('Content-Type: application/json');

include_once('../../../Security.php'); 
require_once ('../../../ListRequire.php');

$Name_Script = "Bourse";
$Device_id = getParameter("Device_id");
$act = getParameter('act');

if ($act == "updateQuotation")
{
    $row_array=[];
    $ListCmdDeviceByDeviceId = CmdDevice::byDevice_Id_WithCmd($Device_id);
    foreach($ListCmdDeviceByDeviceId as $donneesDevice)
    {
        $ref = getJsonAttr($donneesDevice["Request"],"ref","");
        $cmd_name = $donneesDevice["Cmd_nom"];
        $Quotation = "";
        $urlBoursorama = "https://www.boursorama.com/bourse/action/graph/ws/UpdateCharts?symbol=".$ref."&period=0";
        $QuotationJson = @file_get_contents($urlBoursorama);
        
        if($QuotationJson !== FALSE)
        { 
            if (strlen($QuotationJson) > 2 && getJsonAttr($QuotationJson,"d", "")[0] != null)
            {
                $Open =  (getJsonAttr($QuotationJson,"d", "")[0]['o']);
                $Hight =  (getJsonAttr($QuotationJson,"d", "")[0]['h']);
                $Low =  (getJsonAttr($QuotationJson,"d", "")[0]['l']);
                $Quotation =  (getJsonAttr($QuotationJson,"d", "")[0]['c']);
                $Variation = (getJsonAttr($QuotationJson,"d", "")[0]['v']);
                $Close = $Quotation - $Variation;
                CmdDevice::Update_Device_Value($Device_id, $Quotation, '', $cmd_name );
                $row_array["Quotation"] = $Quotation;
            }
            else
            {
                $Open =  getJsonAttr($QuotationJson,"o", "");
                $Hight =  getJsonAttr($QuotationJson,"h", "");
                $Low =  getJsonAttr($QuotationJson,"l", "");
                $Quotation =  getJsonAttr($QuotationJson,"c", "");
                $Variation = getJsonAttr($QuotationJson,"v", "");
                $Close = $Quotation - $Variation;
                CmdDevice::Update_Device_Value($Device_id, $Quotation, '', $cmd_name );
                $row_array["Quotation"] = $Quotation;
            }
        }
        else
        {
            $row_array["Quotation"] = '-';
        }
    }
    
    $JSON = array();
    array_push($JSON,$row_array);
    echo  json_encode($JSON);
}
?>
