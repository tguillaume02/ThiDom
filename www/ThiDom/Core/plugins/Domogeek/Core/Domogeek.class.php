<?php

class Domogeek extends Device
{
     public function Install()
     { 
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('Conditions');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'Conditions');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();

        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('Sunset');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'Sunset');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('Sunrise');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'Sunrise');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('vigilancecolor');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'vigilancecolor');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('vigilancerisk');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'vigilancerisk');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('SchoolHolidays');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'SchoolHolidays');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('weekend');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'weekend');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('holiday');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'holiday');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('EjpToday');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'EjpToday');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('EjpTomorrow');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'EjpTomorrow');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
            	
        $domogeekCmd = new DomogeekCmd();
        $domogeekCmd->set_Name('Season');
        $domogeekCmd->set_device_Id($this->DeviceNewId()->get_Id());
        $domogeekCmd->set_request('data', 'Season');
        $domogeekCmd->set_unite('');
        $domogeekCmd->set_raz('3600');
        $domogeekCmd->set_visible(1);
        $domogeekCmd->set_type('info');
        $domogeekCmd->save();
    }

}

class DomogeekCmd extends CmdDevice
{

}