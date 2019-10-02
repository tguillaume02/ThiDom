<?php

class Telegram extends Device
{	
	private $Device_id = "";
    //private $ChannelId; 
    private $BotToken;
    
    public function __construct()
	{
        if (getParameter('cmdDeviceId'))
        {
            $this->Device_id = CmdDevice::byId(getParameter('cmdDeviceId'))->get_Device_Id();
        }
        elseif (getParameter('Device_id'))
        {
            $this->Device_id = getParameter('Device_id');
        }
        
		if (!empty($this->Device_id))
		{
            /*$this->ChannelId =  Device::byId($this->Device_id)->get_Configuration("ChannelId","null");*/
            $this->BotToken =  Device::byId($this->Device_id)->get_Configuration("BotToken","null");
        }        
    }
        
	function __destruct() {
	}

    public function Install($DeviceId="")
    { 
       $DeviceId = empty($DeviceId) ? $this->DeviceNewId()->get_Id() : $DeviceId;
       $telegramCmd = new TelegramCmd();
       $telegramCmd->set_Name('SendMessage');
       $telegramCmd->set_device_Id($DeviceId);
       $telegramCmd->set_request('url', 'plugins/Telegram/Desktop/Telegram.php');
       $telegramCmd->set_request('url_ajax', 'plugins/Telegram/Desktop/Telegram_ajax.php');
       $telegramCmd->set_request('data', 'act=SendMessage');
       $telegramCmd->set_request('ChannelId', '');
       $telegramCmd->set_visible(1);
       $telegramCmd->set_type('Action');
       $telegramCmd->save();
   } 

   public function SendMessage($msg="", $channel_id="", $type="", $typeData="")
   {       
       if ($this->BotToken != null /*&& $this->ChannelId != null*/)
       {
            $url = "https://api.telegram.org/bot".$this->BotToken;
            $ch = "";

            if ($channel_id != "")
            {            
                $msg = str_replace("<br>", "", $msg);
                    
                //$response = file_get_contents("https://api.telegram.org/bot".$this->BotToken."/sendMessage?" . http_build_query($data) ); 
                switch ($type)
                {
                    case "picture":
                        $data = [
                            'chat_id' => $channel_id,
                            'photo' => new CURLFile(realpath($typeData)),
                            'caption' => $msg,
                            'parse_mode' => 'HTML'
                        ];
                        
                        $ch = curl_init($url . '/sendPhoto');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "Content-Type:multipart/form-data"
                        ));
                        break;
                    case "video":                        
                        $data = [
                            'chat_id' => $channel_id,
                            'video' => $typeData,
                            'caption' => $msg,
                            'parse_mode' => 'HTML'
                        ];
                        
                        $ch = curl_init($url . '/sendVideo');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            "Content-Type:multipart/form-data"
                        ));
                        break; 
                    default:
                        $params = [
                            'chat_id' => $channel_id,
                            'text' => $msg,
                            'parse_mode' => 'HTML'
                        ];

                        $ch = curl_init($url . '/sendMessage');
                        curl_setopt($ch, CURLOPT_HEADER, false);
                }
            }
            
            if ($ch != "")
            {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    public function postSave()
    {
        $url = "https://api.telegram.org/bot".$this->BotToken."/setWebhook";
        $post = array(
			'url' => $_SERVER['SERVER_NAME']."/ThiDom/Core/plugins/Telegram/Core/TelegramBot.php?apiKey=".Module::byName("Telegram")->get_apiKey()."&Device_id=".$this->Device_id,
        );
        
        $ch = curl_init();

        // configuration des options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);                
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // exécution de la session
        $resultPostSave = curl_exec($ch);
        
        // fermeture des ressources
        curl_close($ch);
    }
}

class TelegramCmd extends CmdDevice
{ 
}
?>