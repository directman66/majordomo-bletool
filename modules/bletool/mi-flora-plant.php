<?php
//https://wiki.hackerspace.pl/projects:xiaomi-flora
//http://www.n8chteule.de/zentris-blog/2017/07/16/nit-xiaomi-plant-sensor-2-python-programming/

//firmware version + battery level
$answ=$this->gethandlevalue($id,'0x038');

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

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}



	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='firmware'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='firmware';
	$cmd_rec2['DEVICE_ID']=$id;
$newvalue=hex2bin($bytes[3].$bytes[4].$bytes[5].$bytes[6].$bytes[7]);
	$cmd_rec2['VALUE']=$newvalue;

	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']&&($cmd_rec2['VALUE'])) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}



//Feuchte, Temperatur, Licht, Leitf?higkeit)
sleep(1);
//Альтернативный консольный вариант
//gatttool -b c4:7c:8d:63:71:c8 --char-read -a 0x0038; sleep 1; gatttool -b c4:7c:8d:63:71:c8 --char-write -a 0x0033 -n 0xA01F; sleep 1; gatttool -b c4:7c:8d:63:71:c8 --char-read -a 0x0035; gatttool -b c4:7c:8d:63:71:c8 --char-read -a 0x0038; sleep 1; gatttool -b c4:7c:8d:63:71:c8 --char-write -a 0x0033 -n 0xA01F; sleep 1; timeout -s INT 10s gatttool -b c4:7c:8d:63:71:c8 --char-read -a 0x0035

$answ=$this->getrawmiflora($mac);
//sg('test.miflorastart',"mac:".$mac.":".$answ);


$bytes=explode(" ",$answ);


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='raw'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='raw';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=$answ;
	$cmd_rec2['VALUE']=$answ;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if ($cmd_rec2['VALUE']) {
	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}
 	}


if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}


	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='temperature'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='temperature';
	$cmd_rec2['DEVICE_ID']=$id;
//	$cmd_rec2['VALUE']=(hexdec($bytes[0])+hexdec($bytes[1])*256)/10;
//	$cmd_rec2['VALUE']=(hexdec($bytes[0].$bytes[1]))/10;
	$newvalue=(hexdec($bytes[2].$bytes[1]))/10;
	$cmd_rec2['VALUE']=$newvalue;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if ($cmd_rec2['VALUE']) {
	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}
 	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}



	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='lux'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='lux';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=hexdec($bytes[5].$bytes[4]);
	$cmd_rec2['VALUE']=$newvalue;
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');
	
	if ($cmd_rec2['VALUE']) {
	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}
 	}

if ($cmd_rec2['LINKED_OBJECT']!='' && $cmd_rec2['LINKED_PROPERTY']!='') {
setGlobal($cmd_rec2['LINKED_OBJECT'].'.'.$cmd_rec2['LINKED_PROPERTY'],$newvalue ,array($this->name => '0'));
}



	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='moisture'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='moisture';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=hexdec($bytes[8]);
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

	$cmd_rec2="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='fertility'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='fertility';
	$cmd_rec2['DEVICE_ID']=$id;
	$newvalue=hexdec($bytes[10].$bytes[9]);
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


