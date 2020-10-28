<?php

class Bourse 
{
     public function Install($DeviceId = "")
     { 
        $DeviceId = empty($DeviceId) ? $this->DeviceNewId()->get_Id() : $DeviceId;        
        $BourseIntall =new CmdDevice();
        $BourseIntall->set_Name('Name');
        $BourseIntall->set_device_Id($DeviceId);
        $BourseIntall->set_request('url', 'plugins/Bourse/Desktop/Bourse.php');
        $BourseIntall->set_request('url_ajax', 'plugins/Bourse/Desktop/Bourse_ajax.php');
        $BourseIntall->set_request('data', 'act=updateQuotation');
        $BourseIntall->set_request('ref', '');
        $BourseIntall->set_request('vua', '');
        $BourseIntall->set_request('nb', '');
        $BourseIntall->set_unite('');
        $BourseIntall->set_raz('60');
        $BourseIntall->set_visible(1);
        $BourseIntall->set_type('info');
        return $BourseIntall->save();
    }
}

class BourseCmd extends CmdDevice
{
}