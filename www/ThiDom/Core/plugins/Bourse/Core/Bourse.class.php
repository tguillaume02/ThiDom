<?php

class Bourse extends Device
{
     public function Install()
     { 
        $bourseCmd = new BourseCmd();
        $bourseCmd->set_Name('Name');
        $bourseCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $bourseCmd->set_request('url', 'plugins/Bourse/Desktop/Bourse.php');
        $bourseCmd->set_request('url_ajax', 'plugins/Bourse/Desktop/Bourse_ajax.php');
        $bourseCmd->set_request('data', 'act=updateQuotation');
        $bourseCmd->set_unite('');
        $bourseCmd->set_raz('15');
        $bourseCmd->set_visible(1);
        $bourseCmd->set_type('info');
        $bourseCmd->save();
    }

}

class BourseCmd extends CmdDevice
{

}