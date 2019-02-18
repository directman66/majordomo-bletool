<?php
//https://github.com/Heckie75/eQ-3-radiator-thermostat/blob/master/eq-3-radiator-thermostat-api.md

///get status 
//     expect /var/www/modules/bletool/eQ-3-radiator-thermostat-master/eq3.exp 00:1a:22:06:a2:d3 status




//Handle 0x0321 - The product name of the thermostat
//
//Encoded in ASCII, you must transform hex to ascii
//Default value: вЂћCC-RT-BLEвЂњ
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



//Handle 0x311 вЂ“ The vendor of the thermostat

//Encoded in ASCII, you must transform hex to ascii
//Default value: вЂћeq-3вЂњ
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
//Byte:                		  127 117
				  
//                                 |  |  |  |  |  |  |  |  |  |
//Serial from badge:               O  E  Q  0  6  1  6  4  1  4
  
//  ascii = char(hex - 0x30)


//$answ=$this->gethandlevalue($id,'0x0411');

$answ=$this->getraweq3($mac, "0x0411", "00");

//$answ=$this->gethandlevalue($id,'0x0411', '03');

$bytes=explode(" ",$answ);

/*
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
*/



	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='sn'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='sn';
	$cmd_rec2['DEVICE_ID']=$id;
//	$cmd_rec2['VALUE']=hex2bin($bytes[4]).hex2bin($bytes[5]).hex2bin($bytes[6]).hex2bin($bytes[7]).hex2bin($bytes[8]).hex2bin($bytes[9]).hex2bin($bytes[10]).hex2bin($bytes[11]).hex2bin($bytes[12]).hex2bin($bytes[13]);
//        $newvalue=hex2bin($bytes[4]-0x30).$bytes[5].$bytes[6].$bytes[7].$bytes[8].$bytes[9].$bytes[10].$bytes[11].$bytes[12].$bytes[13]);
//        $newvalue=hex2bin(bin2hex($bytes[4])-0x30).hex2bin(bin2hex($bytes[5])-0x30).hex2bin(bin2hex($bytes[6])-0x30).hex2bin(bin2hex($bytes[7])-0x30).hex2bin(bin2hex($bytes[8])-0x30).hex2bin(bin2hex($bytes[9])-0x30).hex2bin(bin2hex($bytes[10])-0x30).hex2bin(bin2hex($bytes[11])-0x30).hex2bin(bin2hex($bytes[12])-0x30).hex2bin(bin2hex($bytes[13])-0x30);
       $newvalue=chr(hexdec($bytes[5])-48).chr(hexdec($bytes[6])-48).chr(hexdec($bytes[7])-48).chr(hexdec($bytes[8])-48).chr(hexdec($bytes[9])-48).chr(hexdec($bytes[10])-48).chr(hexdec($bytes[11])-48).chr(hexdec($bytes[12])-48).chr(hexdec($bytes[13])-48).chr(hexdec($bytes[14])-48);
//01 6e 00 00 7d 75 81 60 68 64 68 63 61 61 9b 
//					     11
//MEQ084 83 11

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

/*
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

*/





//$bytes=str_replace(" ","",$answ);



//if (hexdec($bytes[3])=='00') 

      $data = str_pad(base_convert($bytes[3], 16, 2),8,"0",STR_PAD_LEFT);
debmes($bytes, 'bletool');
debmes('data:'.$bytes[3].':'.$data, 'bletool');

//09:52:20 0.07985800 data:09:00001001  - manual
//                         08:00001000  - auto
//                    data:0c:00001100  - auto boost
//                            87654321
//			      01234567	
// 1:7 - manual/auto
// 2:6 - vacation
// 3:5 boost
// 4:4 dst
// 5:3 open window
// 6:2 locked
// 7:1 unknown
// 8:0 low battery



switch ($data[7]) {

	case "0":
	$mode='auto';
	break;

	case "1":
	$mode='manual';
	break;
}

switch ($data[6]) {

	case "1":
	$vacation='1';
	break;

	case "0":
	$vacation='0';
	break;
}

switch ($data[5]) {

	case "1":
	$boost='1';
	break;

	case "0":
	$boost='0';
	break;
}

switch ($data[4]) {

	case "1":
	$dst='1';
	break;

	case "0":
	$dst='0';
	break;
}

switch ($data[3]) {

	case "1":
	$ow='1';
	break;

	case "0":
	$ow='1';
	break;
}

switch ($data[0]) {

	case "1":
	$bat='1';
	break;

	case "0":
	$bat='0';
	break;
}

switch ($data[2]) {

	case "1":
	$locked='1';
	break;

	case "0":
	$locked='0';
	break;
}







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


if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$ow ,array($this->name => '0'));
}




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

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$dst ,array($this->name => '0'));
}


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

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$locked ,array($this->name => '0'));
}


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

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$boost ,array($this->name => '0'));
}

	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='lowbattery'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='lowbattery';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$bat;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$bat,array($this->name => '0'));
}




	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='vacation'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='vacation';
	$cmd_rec2['DEVICE_ID']=$id;
	$cmd_rec2['VALUE']=$vacation;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}


if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$vacation ,array($this->name => '0'));
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





