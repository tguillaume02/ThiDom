<?php
$cmd = 'sudo /home/ThiDom/Script\ crontab/update_thidom.sh 2>&1';
$proc = popen($cmd, 'r');
while (!feof($proc))
{
    echo "[".date("H:i:s")."] => ".fread($proc, 4096);
}


# $output = shell_exec('/home/ThiDom/Script\ crontab/update_thidom.sh');
# echo "<pre>$output</pre>";
?>