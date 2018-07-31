<?php

class Telegram extends Device
{	
	private $Device_id = "";
    private $ChannelId; 
    private $BotToken;
    
	public function __construct()
	{
		$this->Device_id = filter_input(INPUT_POST, 'Device_id');
		if (!empty($this->Device_id))
		{
            $this->ChannelId =  Device::byId($this->Device_id)->get_Configuration("ChannelId","null");
            $this->BotToken =  Device::byId($this->Device_id)->get_Configuration("BotToken","null");        
		}
    }
        
	function __destruct() {
	}

    public function Install()
    { 
       $telegramCmd = new TelegramCmd();
       $telegramCmd->set_Name('SendMessage');
       $telegramCmd->set_device_Id($this->DeviceNewId()->get_Id());
       $telegramCmd->set_request('url', 'plugins/Telegram/Desktop/Telegram.php');
       $telegramCmd->set_request('url_ajax', 'plugins/Telegram/Desktop/Telegram_ajax.php');
       $telegramCmd->set_request('data', 'act=SendMessage');
       $telegramCmd->set_visible(1);
       $telegramCmd->set_type('action');
       $telegramCmd->save();
   } 

   public function SendMessage($msg)
   {       
       if ($this->BotToken != null && $this->ChannelId != null)
       {
            $data = [
                'chat_id' => $this->ChannelId,
                'text' => $msg,
                'parse_mode' => 'HTML'
            ];

            $response = file_get_contents("https://api.telegram.org/bot".$this->BotToken."/sendMessage?" . http_build_query($data) );
        }
    }
}

class TelegramCmd extends CmdDevice
{ 
}
?>