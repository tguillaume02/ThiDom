<?php

class Domogeek extends Device
{
     public function Install()
     { 
        $domogeekCmd = new CmdDevice();
        $domogeekCmd->set_Name('Conditions');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Conditions');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();

        $domogeekCmd->set_Name('Sunset');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Sunset');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('Sunrise');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Sunrise');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('Vigilancecolor');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Vigilancecolor');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('Vigilancerisk');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Vigilancerisk');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('SchoolHolidays');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=SchoolHolidays');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('Weekend');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Weekend');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('Holiday');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Holiday');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('EjpToday');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=EjpToday');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('EjpTomorrow');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=EjpTomorrow');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd->set_Name('Season');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('url', 'plugins/Domogeek/Desktop/Domogeek.php');
        $domogeekCmd->set_request('url_ajax', 'plugins/Domogeek/Desktop/Domogeek_ajax.php');
        $domogeekCmd->set_request('data', 'act=Season');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        return $domogeekCmd->save();
    }

}

class DomogeekCmd extends CmdDevice
{

}