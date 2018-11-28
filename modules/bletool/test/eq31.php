<?php

//set_time_limit(10);
//ob_implicit_flush(true);

$cmd='sudo hciconfig hci0 reset';
$answ=shell_exec($cmd);

$cmd='sudo hciconfig hci0 down';
$answ=shell_exec($cmd);

$cmd='sudo hciconfig hci0 up';
$answ=shell_exec($cmd);



$state=0;

$exe_command = 'gatttool -t random  -I';

$descriptorspec = array(
    0 => array("pipe", "r"),  // stdin
    1 => array("pipe", "w"),  // stdout -> we use this
    2 => array("pipe", "w")   // stderr 
);
$i=0;
$s=0;
$process = proc_open($exe_command, $descriptorspec, $pipes);

if (is_resource($process))
{



    while( ! feof($pipes[1]))
  {

if ($state==0) { echo $i.' send conect:';

fputs($pipes[0], 'connect 00:1a:22:06:a2:d3'."\n");
sleep(2);
}

if ($state==1&&$s==0) { 
echo $i.' send 0x0411 00:';
fputs($pipes[0], 'char-write-req 0x0411 00'."\n");
sleep(2);
$s=$s+1;
//$state=2;
}

if ($state==2) {
//echo $i.' read  0x35:';
//fputs($pipes[0], 'char-read-hnd 0x35'."\n");
//sleep(1);
}


if ($state==3) { echo $i.' send exit:';
  fputs($pipes[0], 'exit'."\n");
}

//fputs($pipes[0], 'help'."\n");
//}


//        fputs($pipes[0], 'char-read-hnd 0x35'."\n");

        $return_message = fgets($pipes[1], 1024);
  //      $return_message2 = fgets($pipes[2], 1024);
        if (strlen($return_message) == 0) break;
      if ($i>100) break;

echo $i." state:".$state." ".$return_message.'<br />';
if (strpos($return_message, 'Connection successful')>0) $state=1;
if (strpos($return_message, 'Characteristic value was written successfully')>0) $state=2;


if (strpos($return_message, 'Notification handle')>0)
{ $state=3; 
$value=explode(":",substr($return_message,strpos($return_message, 'Notification handle')))[1];
echo "value: ".$value."<br>";
 }

if (strpos($return_message, 'Disconnected')>0) $state=3;






        ob_flush();
        flush();
$i=$i+1;

    }
//sleep(1);
}
echo "<br>";
echo  $value;
