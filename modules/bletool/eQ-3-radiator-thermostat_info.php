<?php
//https://github.com/Heckie75/eQ-3-radiator-thermostat/blob/master/eq-3-radiator-thermostat-api.md

///get status 
//     expect /var/www/modules/bletool/eQ-3-radiator-thermostat-master/eq3.exp 00:1a:22:06:a2:d3 status




//Handle 0x0321 - The product name of the thermostat
//
//Encoded in ASCII, you must transform hex to ascii
//Default value: „CC-RT-BLE“
//Get: char-read-hnd 321
//Characteristic value/descriptor: 43 43 2d 52 54 2d 42 4c 45
//Set: n/a
$answ=$this->gethandlevalue($id,'0x0321');

$bytes=explode(" ",$answ);

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='productname'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='productname';
	$cmd_rec2['DEVICE_ID']=$id;
        $newvalue=hex2bin($bytes[1]).hex2bin($bytes[2]).hex2bin($bytes[3]).hex2bin($bytes[4]).hex2bin($bytes[5]).hex2bin($bytes[6]).hex2bin($bytes[7]).hex2bin($bytes[8]).hex2bin($bytes[9]);
	$cmd_rec2['VALUE']=$newvalue;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'], $newvalue,array($this->name => '0'));
}



//Handle 0x311 – The vendor of the thermostat

//Encoded in ASCII, you must transform hex to ascii
//Default value: „eq-3“
//Get: char-read-hnd 311
//Characteristic value/descriptor: 65 71 2d 33
//Set: n/a




$answ=$this->getraweq3($mac, "0x0411", "03");

$this->extractanswereq3($answ, $id);
