<?php
//https://github.com/vitamin-caig/mitemp/blob/master/mitemp-poll.py
//https://community.home-assistant.io/t/xiaomi-mijia-bluetooth-temperature-humidity-sensor-compatibility/43568/7

/*
//firmware version + battery level
$answ=$this->getrawmithermostat($id,'0x18');
$answ=$this->getrawmithermostat($mac);


$bytes=explode(" ",$answ);

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='battery'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='battery';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=hexdec($bytes[1]);
	$cmd_rec2['VALUE']=$newvalue;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!=''&&($newvalue)) {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}
*/

$answ=$this->getrawmithermostat($mac);


$bytes=explode(" ",$answ);

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='raw'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='raw';
	$cmd_rec2['DEVICE_ID']=$id;
//	$newvalue=hex2bin($answ);
	$newvalue=($answ);
	$cmd_rec2['VALUE']=$newvalue;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!=''&&($newvalue)) {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='temperature'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='temperature';
	$cmd_rec2['DEVICE_ID']=$id;
//	$newvalue=hex2bin(str_replace(' ','',$answ));
//	$newvalue=(str_replace(' ','',$answ));
        $newvalue=hex2bin($bytes[1]).hex2bin($bytes[2]).hex2bin($bytes[3]).hex2bin($bytes[4]).hex2bin($bytes[5]).hex2bin($bytes[6]).hex2bin($bytes[7]).hex2bin($bytes[8]).hex2bin($bytes[9]).hex2bin($bytes[10]).hex2bin($bytes[11]).hex2bin($bytes[12]).hex2bin($bytes[13]).hex2bin($bytes[14]);
	$cmd_rec2['VALUE']=explode("=",explode(" ",$newvalue)[0])[1];
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!=''&&($newvalue)) {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='hudimity'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='hudimity';
	$cmd_rec2['DEVICE_ID']=$id;
//	$newvalue=hex2bin(str_replace(' ','',$answ));
//	$newvalue=(str_replace(' ','',$answ));
        $newvalue=hex2bin($bytes[1]).hex2bin($bytes[2]).hex2bin($bytes[3]).hex2bin($bytes[4]).hex2bin($bytes[5]).hex2bin($bytes[6]).hex2bin($bytes[7]).hex2bin($bytes[8]).hex2bin($bytes[9]).hex2bin($bytes[10]).hex2bin($bytes[11]).hex2bin($bytes[12]).hex2bin($bytes[13]).hex2bin($bytes[14]);
	$cmd_rec2['VALUE']=explode("=",explode(" ",$newvalue)[1])[1];
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!=''&&($newvalue)) {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}





