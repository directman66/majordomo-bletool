<?php

//https://habr.com/post/276343/
//http://allmydroids.blogspot.com/2014/12/xiaomi-mi-band-ble-protocol-reverse.html
//firmware version + battery level
$answ=$this->gethandlevalue($id,'0x002c');

$bytes=explode(" ",$answ);

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='battery'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='battery';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=hexdec($bytes[1]);
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}


switch (hexdec($bytes[9])) 
{
case "1": 
$battery_status="Battery low"; break;
case "2": 
$battery_status="Battery charging";break;
case "3": 
$battery_status="Battery full (charging)";break;
case "4":
 $battery_status="Not charging";break;
}
	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='battery_status'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='battery_status';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$battery_status;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

$answ=$this->gethandlevalue($id,'0x001D');

$bytes=explode(" ",$answ);

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='steps'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='steps';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=hexdec($bytes[4]).hexdec($bytes[3]).hexdec($bytes[2]).hexdec($bytes[1]);
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}




