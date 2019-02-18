<?php
//https://github.com/Heckie75/eQ-3-radiator-thermostat/blob/master/eq-3-radiator-thermostat-api.md



$answ=$this->getraweq3($mac, "0x0411", "03");

//$answ=$this->gethandlevalue($id,'0x0411', '03');

$bytes=explode(" ",$answ);


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





	$sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='target_t'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']='target_t';
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





