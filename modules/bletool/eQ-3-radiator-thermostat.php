<?php
//https://github.com/Heckie75/eQ-3-radiator-thermostat/blob/master/eq-3-radiator-thermostat-api.md



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


$answ=$this->gethandlevalue($id,'0x0311');

$bytes=explode(" ",$answ);

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='vendor'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='vendor';
	$cmd_rec2['DEVICE_ID']=$id;
        $newvalue=hex2bin($bytes[1]).hex2bin($bytes[2]).hex2bin($bytes[3]).hex2bin($bytes[4]);
	$cmd_rec2['VALUE']=  $newvalue;
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


//Read serial number from device
//
//The serial number that is printed on the little badge between the two batteries can be quired as follows:

//char-write-req 0411 00
//                    01 6e 00 00 7f 75 81 60 66 61 66 64 61 64 9b
//                                 |  |  |  |  |  |  |  |  |  |
//Byte:                0  1  2  3  4  5  6  7  8  9 10 11 12 13 14
//                                 |  |  |  |  |  |  |  |  |  |
//Serial from badge:               O  E  Q  0  6  1  6  4  1  4
  
//  ascii = char(hex - 0x30)


//$answ=$this->gethandlevalue($id,'0x0411');

$answ=$this->getraweq3($mac, "0x0411", "00");

//$answ=$this->gethandlevalue($id,'0x0411', '03');

$bytes=explode(" ",$answ);


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='snraw'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='snraw';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$answ;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'], $answ,array($this->name => '0'));
}



	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='sn'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='sn';
	$cmd_rec2['DEVICE_ID']=$id;
//	$cmd_rec2['VALUE']=hex2bin($bytes[4]).hex2bin($bytes[5]).hex2bin($bytes[6]).hex2bin($bytes[7]).hex2bin($bytes[8]).hex2bin($bytes[9]).hex2bin($bytes[10]).hex2bin($bytes[11]).hex2bin($bytes[12]).hex2bin($bytes[13]);
        $newvalue=hex2bin($bytes[4].$bytes[5].$bytes[6].$bytes[7].$bytes[8].$bytes[9].$bytes[10].$bytes[11].$bytes[12].$bytes[13]);
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
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}



$answ=$this->getraweq3($mac, "0x0411", "03");

//$answ=$this->gethandlevalue($id,'0x0411', '03');

$bytes=explode(" ",$answ);


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='raw'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='raw';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$answ;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}
if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$answ ,array($this->name => '0'));
}







//$bytes=str_replace(" ","",$answ);



if (hexdec($bytes[3])=='00')  {$mode="auto";} else {$mode="manual";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='mode'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='mode';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$mode;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$mode ,array($this->name => '0'));
}


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='current_t'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='current_t';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=hexdec($bytes[6])/2;
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
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='percentage'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='percentage';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=hexdec($bytes[4])/2;
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
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}




/*
if (hexdec($bytes[2])=='0')  {$vacation="false";} else {$vacation="true";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='vacation'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='vacation';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$vacation;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');


if (hexdec($bytes[3])=='0')  {$boost="false";} else {$boost="true";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='boost'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='boost';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$boost;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if (hexdec($bytes[4])=='0')  {$dst="false";} else {$dst="true";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='dst'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='dst';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$dst;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if (hexdec($bytes[5])=='0')  {$ow="false";} else {$ow="true";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='open_window'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='open_window';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$ow;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}


if (hexdec($bytes[6])=='0')  {$locked="false";} else {$locked="true";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='locked'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='locked';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$locked;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if (hexdec($bytes[8])=='0')  {$lb="false";} else {$lb="true";}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='low_battery'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='low_battery';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$lb;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}







	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='mode'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='mode';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=hex2bin($bytes[1]).hex2bin($bytes[2]).hex2bin($bytes[3]).hex2bin($bytes[4]);
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}


$answ=$this->gethandlevalue($id,'0x0411', '04');
*/

