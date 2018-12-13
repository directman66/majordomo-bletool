<?php
/**
* @package project
* @author Wizard <sergejey@gmail.com>
* @copyright http://majordomo.smartliving.ru/ (c)
* @version 0.1 (wizard, 13:03:10 [Mar 13, 2016])
*/
//
//
//ini_set ('display_errors', 'off');
//ob_implicit_flush(true);
set_time_limit(300);


class bletool extends module {
/**
* yandex_tts
*
* Module class constructor
*
* @access private
*/
function bletool() {
  $this->name="bletool";
  $this->title="BLEtool";
  $this->module_category="<#LANG_SECTION_DEVICES#>";
  $this->checkInstalled();
}
/**
* saveParams
*
* Saving module parameters
*
* @access public
*/
function saveParams($data=0) {
 $p=array();
 if (IsSet($this->id)) {
  $p["id"]=$this->id;
 }
 if (IsSet($this->view_mode)) {
  $p["view_mode"]=$this->view_mode;
 }
 if (IsSet($this->edit_mode)) {
  $p["edit_mode"]=$this->edit_mode;
 }
 if (IsSet($this->tab)) {
  $p["tab"]=$this->tab;
 }
 return parent::saveParams($p);
}
/**
* getParams
*
* Getting module parameters from query string
*
* @access public
*/
function getParams() {
  global $id;
  global $mac;
  global $mode;
  global $view_mode;
  global $edit_mode;
  global $tab;
  if (isset($id)) {
   $this->id=$id;
  }
  if (isset($mode)) {
   $this->mode=$mode;
  }
  if (isset($view_mode)) {
   $this->view_mode=$view_mode;
  }
  if (isset($edit_mode)) {
   $this->edit_mode=$edit_mode;
  }
  if (isset($tab)) {
   $this->tab=$tab;
  }



}
/**
* Run
*
* Description
*
* @access public
*/
function run() {
 global $session;
  $out=array();
  if ($this->action=='admin') {
   $this->admin($out);
  } else {
   $this->usual($out);
  }
  if (IsSet($this->owner->action)) {
   $out['PARENT_ACTION']=$this->owner->action;
  }
  if (IsSet($this->owner->name)) {
   $out['PARENT_NAME']=$this->owner->name;
  }
  $out['VIEW_MODE']=$this->view_mode;
  $out['EDIT_MODE']=$this->edit_mode;
  $out['MODE']=$this->mode;
  $out['ACTION']=$this->action;
  $out['TAB']=$this->tab;
  $this->data=$out;
  $p=new parser(DIR_TEMPLATES.$this->name."/".$this->name.".html", $this->data, $this);
  $this->result=$p->result;

//echo 'view_mode:'.$this->view_mode;




 if ($this->view_mode=='indata_del') {
   $this->delete($this->id);
   $this->redirect("?");
}	

 if ($this->view_mode=='getservices') {
    $this->getservices($this->id);
   $this->redirect("?&view_mode=edit_devices&id=".$this->id."&tab=services");
}	
//echo "vm".$this->view_mode;
//echo "mac".$this->id;
 if ($this->view_mode=='updatevendor') {
$mac=$this->id;
$vend= $this->getvendor($this->id);
//echo "mac".$this->id;
//echo $vend;


sqlexec ("update ble_devices set VENDOR='$vend' where MAC='$mac'");

  $this->redirect("?");
}	




 if ($this->view_mode=='gethandles') {
    $this->gethandles($this->id);

   $this->redirect("?&view_mode=edit_devices&id=".$this->id."&tab=handles");
}	



 if ($this->view_mode=='getvalues') {
    $this->getvalues($this->id);
   $this->redirect("?&view_mode=edit_devices&id=".$this->id."&tab=data");
}	





if ($this->view_mode=='restart') {
//  $this->pingall();

setGlobal('cycle_bletoolAutoRestart','1');	 	 
setGlobal('cycle_bletoolControl','restart'); 


   $this->redirect("?");
}




if ($this->view_mode=='resethci') {
  $this->resethci();
  $this->redirect("?");

}


if ($this->view_mode=='discover') {
  $this->discover();
  $this->redirect("?");

}



if ($this->view_mode=='clearall') {
  $this->clearall();
  $this->redirect("?");

}
//echo $this->view_mode;
      if ($this->view_mode == 'edit_devices') {
           $this->edit_devices($out, $this->id);

        }




}


 function processCycle() {
   
debmes('start processCycle', 'bletool');	

  $res=SQLSelect("SELECT ble_devices.* FROM ble_devices where POLLING is not null and POLLING<>0  ");
			$total = count($res);
			for($i=0; $i<$total; $i++) {
//$polling=$res['POLLING'];


   $every=$res[$i]['POLLING'];
   if ($res[$i]['UPDATEDTS']) {$tdev = time()-$res[$i]['UPDATEDTS'];}
else {$tdev = time()-1000;}
   $has = $tdev>$every*60;



   if ($tdev < 0) {$has=true;}

debmes('tdev: '.$tdev.", every:".$every." UPDATEDTS:".$res[$i]['UPDATEDTS']." has:".$has." id:".$res[$i]['ID']." title:".$res[$i]['TITLE'], 'bletool');


if ($has){
debmes('starting getvalues for ',$res[$i]['ID']. "from cycle", 'bletool');
$this->getvalues($res[$i]['ID']);
$res[$i]['UPDATED']=date('Y-m-d H:i:s');
$res[$i]['UPDATEDTS']=time();
SQLUpdate('ble_devices', $res[$i]);

  } 
  }
  }

 function discover() {

$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
//echo php_uname();
//echo PHP_OS;
$debug = file_get_contents($file);
$debug .= "Scanning run at ".gg('sysdate').' '.gg('timenow')."<br>\n";
file_put_contents($file, $debug);



if (substr(php_uname(),0,5)=='Linux')  {
//echo "это линус";
//$cmd='nmap -sn 192.168.1.0/24';
//$cmd='echo 192.168.1.{1..254}|xargs -n1 -P0 ping -c1|grep "bytes from"';
$data = array();

$this->resethci();

//$cmd='sudo timeout -s INT 30s hcitool lescan | grep ":"';
//$answ=shell_exec($cmd,$data);


exec('sudo timeout -s INT 30s hcitool lescan | grep ":"',$data);
$debug .= file_get_contents($file);
$debug.= $cmd.":".$answ."<br>\n";
file_put_contents($file, $debug);




//$data2 =preg_split('/\\r\\n?|\\n/',$out);

			$total = count($data);
			for($i=0; $i<$total; $i++) {
				$data[$i] = trim($data[$i]);
				$mac = trim(strtolower(substr($data[$i], 0, 17)));
				$name = trim(substr($data[$i], 17));
 
  			 	$vendor=$this->getvendor($mac);

$debug = file_get_contents($file);
$debug.="find ". $mac.":".$name.":".$vendor."<br>\n";
file_put_contents($file, $debug);


 		if(!empty($mac)) {
		$cmd_rec = SQLSelectOne("SELECT * FROM ble_devices where MAC='$mac'");
		$cmd_rec['MAC']=$mac;
		$cmd_rec['IPADDR']=$ipadr;
		$cmd_rec['TITLE']=$name;
		$cmd_rec['VENDOR']=$vendor;
$macc=strtoupper($mac);
               if (substr($macc,0,8) == 'C4:7C:8D') {$cmd_rec['TYPE']='mi-flora-plant';}
               if (substr($macc,0,8) == '00:1A:22') {$cmd_rec['TYPE']='eQ-3-radiator-thermostat';}
               if (substr($macc,0,8) == 'F8:AF:0F ') {$cmd_rec['TYPE']='mi-band-2';}
               if (substr($macc,0,8) == '58:80:3С ') {$cmd_rec['TYPE']='amazfit-stratos';}




		$cmd_rec['ADDED']=date('Y-m-d H:i:s');

if (!$cmd_rec['ID']) 
{
//$cmd_rec['ONLINE']=$onlinest;
if (strlen($mac)>4) SQLInsert('ble_devices', $cmd_rec);
} else {
SQLUpdate('ble_devices', $cmd_rec);
}}}} 
else 
 {
//echo "это виндовс";
//$cmd='nmap -sn 192.168.1.0/24';
echo "linux system only";
}

}


 function clearall() {
$cmd_rec = SQLSelect("delete  FROM ble_devices");
$cmd_rec = SQLSelect("delete  FROM ble_services");
$cmd_rec = SQLSelect("delete  FROM ble_commands");
$cmd_rec = SQLSelect("delete  FROM ble_handles");
}

 function pingall() {
$cmd_rec = SQLSelect("SELECT * FROM ble_devices  ");
foreach ($cmd_rec as $rc) {
//echo $rc['IPADDR'];
$online=ping(processTitle($rc['IPADDR']));
if ($online) {$onlinest="1";} else {$onlinest="0";} 

$cmd_rec['ONLINE']=$onlinest;
SQLUpdate('ble_devices', $cmd_rec);
}
}

 function delete($id) {
  $rec=SQLSelectOne("SELECT * FROM ble_devices WHERE ID='$id'");
  // some action for related tables
  SQLExec("DELETE FROM ble_devices WHERE ID='".$rec['ID']."'");
 }


 function searchdevices(&$out) {
  require(DIR_MODULES.$this->name.'/search.inc.php');
//print_r( $out);
 }

   function edit_devices(&$out, $id)
   {
      require(DIR_MODULES . $this->name . '/devices_edit.inc.php');
//      require(DIR_MODULES . $this->name . '/deviceedit.php');



//print_r( $out);
   }




/**
* BackEnd
*
* Module backend
*
* @access public
*/
function admin(&$out) {

        if ((time() - gg('cycle_bletoolRun')) < 360*2 ) {
			$out['CYCLERUN'] = 1;
		} else {
			$out['CYCLERUN'] = 0;
		}
	



 if ($this->view_mode=='edit_devices') {
$this->edit_devices($out, $this->id);
//$this->edit_devices($out);

//$res=$this->wake($mac);
}

//echo $this->view_mode;
 if ($this->view_mode=='') {
$this->searchdevices($out);

$filename = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу

$a=shell_exec("tail -n 100 $filename");
///$a =  str_replace( array("\r\n","\r","\n") , '<br>' , $a);
$out['DEBUG']=$a;

}


 if ($this->tab=='info') {
//$this->searchdevices($out);

$filename = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу

//$a=shell_exec("sudo bluetoothctl");
$a=shell_exec("hciconfig");


$a =  str_replace( array("\r\n","\r","\n") , '<br>' , $a);
$out['bluetoothctl']=$a;
//////////
$filename = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
$a=shell_exec("hcitool dev");
$a =  str_replace( array("\r\n","\r","\n") , '<br>' , $a);
$out['hcitooldev']=$a;


//////////
$filename = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
$a=shell_exec("sudo hcitool con");
$a =  str_replace( array("\r\n","\r","\n") , '<br>' , $a);
$out['con']=$a;


	if(exec('sudo echo test') == 'test') {
	$out['SUDO_TEST'] = 1; 			
	} else {
	$out['SUDO_TEST'] = 0;}
			









}

}



/**
* FrontEnd
*
* Module frontend
*
* @access public
*/
function usual(&$out) {
 $this->admin($out);
}
/**
* Install
*
* Module installation routine
*
* @access private
*/
 function install($data='') {
  parent::install();


 }
 
 function dbInstall($data) {

 $data = <<<EOD
ble_devices: ID int(10) unsigned NOT NULL auto_increment
ble_devices: TITLE varchar(100) NOT NULL DEFAULT ''
ble_devices: MAC varchar(100) NOT NULL DEFAULT ''
ble_devices: IPADDR varchar(100) NOT NULL DEFAULT ''
ble_devices: NAME varchar(100) NOT NULL DEFAULT ''
ble_devices: LASTPING varchar(100) NOT NULL DEFAULT ''
ble_devices: ONLINE varchar(100) NOT NULL DEFAULT ''
ble_devices: VENDOR varchar(100) NOT NULL DEFAULT ''
ble_devices: TYPE varchar(100) NOT NULL DEFAULT ''
ble_devices: SERVICES varchar(100) NOT NULL DEFAULT ''
ble_devices: ADDED datetime
ble_devices: ENABLE int(1) 
ble_devices: POLLING int(10) 
ble_devices: UPDATED datetime
ble_devices: UPDATEDTS varchar(100) NOT NULL DEFAULT ''

ble_services: ID int(10) unsigned NOT NULL auto_increment
ble_services: IDDEV int(10) 
ble_services: manufacturer varchar(100) NOT NULL DEFAULT ''
ble_services: lpmvers varchar(100) NOT NULL DEFAULT ''
ble_services: lpmsubvers varchar(100) NOT NULL DEFAULT ''
ble_services: handledec varchar(100) NOT NULL DEFAULT ''
ble_services: handlehex varchar(100) NOT NULL DEFAULT ''
ble_services: features varchar(100) NOT NULL DEFAULT ''
ble_services: featurestext varchar(100) NOT NULL DEFAULT ''
ble_services: debug varchar(100) NOT NULL DEFAULT ''
ble_services: parametr varchar(100) NOT NULL DEFAULT ''
ble_services: value varchar(100) NOT NULL DEFAULT ''
ble_services: updated datetime

ble_handles: ID int(10) unsigned NOT NULL auto_increment
ble_handles: IDDEV int(10) 
ble_handles: handle varchar(100) NOT NULL DEFAULT ''
ble_handles: char_prop varchar(100) NOT NULL DEFAULT ''
ble_handles: char_val varchar(100) NOT NULL DEFAULT ''
ble_handles: uuid varchar(100) NOT NULL DEFAULT ''
ble_handles: updated datetime



ble_commands: ID int(10) unsigned NOT NULL auto_increment

ble_commands: TITLE varchar(100) NOT NULL DEFAULT ''
ble_commands: VALUE varchar(255) NOT NULL DEFAULT ''
ble_commands: DEVICE_ID int(10) NOT NULL DEFAULT '0'
ble_commands: LINKED_OBJECT varchar(100) NOT NULL DEFAULT ''
ble_commands: LINKED_PROPERTY varchar(100) NOT NULL DEFAULT ''
ble_commands: LINKED_METHOD varchar(100) NOT NULL DEFAULT '' 
ble_commands: UPDATED datetime


EOD;


  parent::dbInstall($data);
 }
 
 function uninstall() {
SQLExec('DROP TABLE IF EXISTS ble_devices');
SQLExec('DROP TABLE IF EXISTS ble_services');
SQLExec('DROP TABLE IF EXISTS ble_commands');
unlink(ROOT."cms/cached/bleutils");

  parent::uninstall();
 }






 function getservices($id) {

$cmd_rec = SQLSelectOne("SELECT * FROM ble_devices where ID='$id'");
$id=$cmd_rec['ID'];
$mac=$cmd_rec['MAC'];


$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
//echo php_uname();
//echo PHP_OS;
$debug = file_get_contents($file);
$debug .= "get info about $mac run at ".gg('sysdate').' '.gg('timenow')."<br>\n";
file_put_contents($file, $debug);


//echo "это линус";
//$cmd='nmap -sn 192.168.1.0/24';
//$cmd='echo 192.168.1.{1..254}|xargs -n1 -P0 ping -c1|grep "bytes from"';

$this->resethci();

$data = array();


//$cmd='sudo timeout -s INT 30s hcitool lescan | grep ":"';
//$answ=shell_exec($cmd,$data);



$cmd="sudo timeout -s INT 15s   hcitool leinfo $mac";
//$cmd="sudo hcitool leinfo $mac";
//echo $cmd;

$answ=shell_exec($cmd);
$debug = file_get_contents($file);
$debug.= $cmd.":".$answ."<br>\n";
file_put_contents($file, $debug);



$data2 =preg_split('/\\r\\n?|\\n/',$answ);
//print_r($data2);
foreach ($data2 as $key){
$par=explode(":",$key)[0];
$val=explode(":",$key)[1];

$sql="SELECT * FROM ble_services where IDDEV='$id' and parametr='".trim($par)."'";
//echo $sql."<br>";
$cmd_rec2 = SQLSelectOne($sql);
$cmd_rec2['debug']=$answ;

//if (array_key_exists($key,$cmd_rec2)) $cmd_rec2[$key]=$val;
$cmd_rec2['IDDEV']=$id;
$cmd_rec2['value']=trim($val);
$cmd_rec2['parametr']=trim($par);

$cmd_rec2['updated']=date('Y-m-d H:i:s');


if (($cmd_rec2['value'])&&($cmd_rec2['parametr']))
{
if (!$cmd_rec2['ID']) 
{
//$cmd_rec['ONLINE']=$onlinest;
SQLInsert('ble_services', $cmd_rec2);
} else {
SQLUpdate('ble_services', $cmd_rec2);
}

}





}






//return print_r($data);





}

///////////////////////////////
///////////////////////


 function gethandles($id) {

$cmd_rec = SQLSelectOne("SELECT * FROM ble_devices where ID='$id'");
$id=$cmd_rec['ID'];
$mac=$cmd_rec['MAC'];


$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
//echo php_uname();
//echo PHP_OS;
$debug = file_get_contents($file);
$debug .= "get handles from  $mac run at ".gg('sysdate').' '.gg('timenow')."<br>\n";

file_put_contents($file, $debug);


//echo "это линус";
//$cmd='nmap -sn 192.168.1.0/24';
//$cmd='echo 192.168.1.{1..254}|xargs -n1 -P0 ping -c1|grep "bytes from"';

$this->resethci();

$data = array();


//$cmd='sudo timeout -s INT 30s hcitool lescan | grep ":"';
//$answ=shell_exec($cmd,$data);


$cmd="gatttool --device=$mac --characteristics";

//$cmd="sudo timeout -s INT 15s   hcitool leinfo $mac";
//$cmd="sudo hcitool leinfo $mac";
//echo $cmd;

$answ=shell_exec($cmd);
$debug = file_get_contents($file);
$debug.= $cmd.":".$answ."<br>\n";
file_put_contents($file, $debug);



$data2 =preg_split('/\\r\\n?|\\n/',$answ);
//print_r($data2);
foreach ($data2 as $key){

$handle=trim(explode("=",explode(",",$key)[0])[1]);
$char_prop=trim(explode("=",explode(",",$key)[1])[1]);
$char_val=trim(explode("=",explode(",",$key)[2])[1]);
$uuid=trim(explode("=",explode(",",$key)[3])[1]);


//echo trim(explode("=",explode(",",$key)[0])[1])."<br>";
//echo trim(explode(explode(",",$key)[1]."<br>";


$sql="SELECT * FROM ble_handles where IDDEV='$id' and handle='".trim($handle)."'";
//echo $sql."<br>";
$cmd_rec2 = SQLSelectOne($sql);

$cmd_rec2['IDDEV']=$id;
$cmd_rec2['handle']=$handle;
$cmd_rec2['char_prop']=$char_prop;
$cmd_rec2['char_val']=$char_val;
$cmd_rec2['uuid']=$uuid;
$cmd_rec2['updated']=date('Y-m-d H:i:s');


if (!$cmd_rec2['ID']) 
{
//$cmd_rec['ONLINE']=$onlinest;
SQLInsert('ble_handles', $cmd_rec2);
} else {
SQLUpdate('ble_handles', $cmd_rec2);
}






}






//return print_r($data);





}



////////////////////////
////////////////////////
////////////////////////
////////////////////////
////////////////////////
////////////////////////




 function getvalues($id) {

$cmd_rec = SQLSelectOne("SELECT * FROM ble_devices where ID='$id'");
$id=$cmd_rec['ID'];
$mac=$cmd_rec['MAC'];
$type=$cmd_rec['TYPE'];




$sql='update ble_devices set UPDATED=\''.date('Y-m-d H:i:s')."',  UPDATEDTS='".time()."' where ID=".$id;
sqlexec($sql);
debmes($sql, 'bletool');




$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
//echo php_uname();
//echo PHP_OS;
$debug = file_get_contents($file);
$debug .= "get values from $mac $type run at ".gg('sysdate').' '.gg('timenow')."<br>\n";
file_put_contents($file, $debug);


//echo "это линус";
//$cmd='nmap -sn 192.168.1.0/24';
//$cmd='echo 192.168.1.{1..254}|xargs -n1 -P0 ping -c1|grep "bytes from"';
$data = array();

$this->resethci();

switch ($type) {

   case "eQ-3-radiator-thermostat":
  require(DIR_MODULES.$this->name.'/eQ-3-radiator-thermostat.php');
break;
   case "mi-band-1s":
  require(DIR_MODULES.$this->name.'/eQ-3-radiator-thermostat.php');
break;

   case "mi-band-2":
  require(DIR_MODULES.$this->name.'/mi-band-2.php');
break;
   case "mi-flora-plant":
  require(DIR_MODULES.$this->name.'/mi-flora-plant.php');

//	}}
break;

}





//return print_r($data);





}




/////////////////
////получение handle для всех устройств
/////////////////
 function gethandlevalue($id,$handle,$a="00" ) {
$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
$this->resethci();

	$mac = SQLSelectOne("SELECT * FROM ble_devices where ID='$id'")['MAC'];
	$cmd="sudo gatttool -i hci0 -b $mac -a $handle -n ".$val." --char-write-req --char-read";

	$answ=shell_exec($cmd);
	$debug = file_get_contents($file);
	$debug.= $cmd.":".$answ."<br>\n";
	file_put_contents($file, $debug);
	$val=explode(':',$answ);
	$bytes=explode(" ",trim($val[1]));
	$hval=$handle.'_'.$a;
        $sql="SELECT * FROM ble_commands where DEVICE_ID='$id' and TITLE='$hval'";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['TITLE']=$hval;
	$cmd_rec2['DEVICE_ID']=$id;
        $cmd_rec2['VALUE']=$val[1];
	$cmd_rec2['UPDATED']=date('Y-m-d H:i:s');

	if (!$cmd_rec2['ID']) 
	{SQLInsert('ble_commands', $cmd_rec2);} else {SQLUpdate('ble_commands', $cmd_rec2);}
return $val[1];

}

//////////////
///сброс для всех
//////////////

 function resethci() {

$cmd='sudo hciconfig hci0 reset';
$answ=shell_exec($cmd);

$debug = file_get_contents($file);
$debug.= $cmd.":".$answ."<br>\n";

sleep(1);
$cmd='sudo hciconfig hci0 down';
$answ=shell_exec($cmd);
$debug = file_get_contents($file);
$debug.= $cmd.":".$answ."<br>\n";


sleep(1);
$cmd='sudo hciconfig hci0 up';
$answ=shell_exec($cmd);
$debug = file_get_contents($file);
$debug.= $cmd.":".$answ."<br>\n";


sleep(1);


}


//////////////
///вендор
//////////////
 function getvendor($mac) {

//	$mac = SQLSelectOne("SELECT * FROM ble_devices where ID='$id'")['MAC'];
$url="http://macvendors.co/api/".$mac."/json";
//echo $url;
//$url="https://macvendors.co/api/".urlencode($mac)."/json";
//$url = "https://api.macvendors.com/" . urlencode($mac_address);
$file = file_get_contents($url);
$data=json_decode($file,true);
//echo $file;
//echo "<br>";
$vendor=$data['result']['company'];
return $vendor;


}



//////////////
///получение raw для mi plant
//////////////
function getrawmiflora($mac) {
//set_time_limit(10);
//ob_implicit_flush(true);


$this->resethci();



$state=0;

//$exe_command = 'ping google.com';
//$exe_command = 'gatttool --device=c4:7c:8d:63:71:c8  -I';
//$exe_command = 'gatttool -t random -b --device=c4:7c:8d:63:71:c8  -I';
$exe_command = 'gatttool -t random  -I';

//sg('test.mif',$exe_command);
//$exe_command = 'gatttool -I';
//$exe_command = 'gatttool ';
//$exe_command = 'sudo timeout -s INT 30s hcitool lescan';


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

fputs($pipes[0], "connect $mac"."\n");
sleep(2);
}

if ($state==1&&$s==0) { 
//echo $i.' send 0x33 A01F:';
fputs($pipes[0], 'char-write-req 0x33 A01F'."\n");
sleep(2);
$s=$s+1;
//$state=2;
}

if ($state==2) { echo $i.' read  0x35:';
fputs($pipes[0], 'char-read-hnd 0x35'."\n");
sleep(1);
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

//echo $i." state:".$state." ".$return_message.'<br />';
if (strpos($return_message, 'Connection successful')>0) $state=1;
if (strpos($return_message, 'Characteristic value was written successfully')>0) $state=2;

if (strpos($return_message, 'Characteristic value/descriptor')>0)
{ $state=3; 

$value=explode(":",

substr($return_message,strpos($return_message, 'Characteristic value/descriptor'))
)[1];
//echo "value: ".$value."<br>";
 }

if (strpos($return_message, 'Disconnected')>0) $state=3;
//        ob_flush();
//        flush();
$i=$i+1;

    }
//sleep(1);
}
//echo "<br>";
return  $value;
}




//////////////
///получение raw для eq3
//////////////
function getraweq3($mac, $handle, $a) {
//set_time_limit(10);
//ob_implicit_flush(true);


$this->resethci();



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

if ($state==0) {
// echo $i.' send conect:';

fputs($pipes[0], "connect $mac"."\n");
sleep(2);
}

if ($state==1&&$s==0) { 
echo $i.' send 0x0411 00:';
fputs($pipes[0], "char-write-req $handle $a"."\n");
sleep(2);
$s=$s+1;
//$state=2;
}

if ($state==2) {
//echo $i.' read  0x35:';
//fputs($pipes[0], 'char-read-hnd 0x35'."\n");
//sleep(1);
}


if ($state==3) {
// echo $i.' send exit:';
  fputs($pipes[0], 'exit'."\n");
}

//fputs($pipes[0], 'help'."\n");
//}


//        fputs($pipes[0], 'char-read-hnd 0x35'."\n");

        $return_message = fgets($pipes[1], 1024);
  //      $return_message2 = fgets($pipes[2], 1024);
        if (strlen($return_message) == 0) break;
      if ($i>100) break;

//echo $i." state:".$state." ".$return_message.'<br />';
if (strpos($return_message, 'Connection successful')>0) $state=1;
if (strpos($return_message, 'Characteristic value was written successfully')>0) $state=2;


if (strpos($return_message, 'Notification handle')>0)
{ $state=3; 
$value=explode(":",substr($return_message,strpos($return_message, 'Notification handle')))[1];
echo "value: ".$value."<br>";
 }

if (strpos($return_message, 'Disconnected')>0) $state=3;



$i=$i+1;

    }
//sleep(1);
}
echo "<br>";
//echo  $value;
return  $value;
}



 
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgTWFyIDEzLCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/





