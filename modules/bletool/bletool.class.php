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
}	

 if ($this->view_mode=='getservices') {
    $this->getservices($this->id);
}	


 if ($this->view_mode=='getvalues') {
    $this->getvalues($this->id);
}	





if ($this->view_mode=='ping') {
//  $this->pingall();
}

if ($this->view_mode=='discover') {
  $this->discover();

}



if ($this->view_mode=='clearall') {
  $this->clearall();

}

      if ($this->view_mode == 'edit_devices') {
           $this->edit_devices($out, $this->id);
        }




}

 function discover() {

$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
//echo php_uname();
//echo PHP_OS;
$debug = file_get_contents($file);
$debug .= "Snanning run at ".gg('sysdate').' '.gg('timenow')."<br>\n";
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
		$cmd_rec['ADDED']=gg('sysdate').' '.gg('timenow')

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
$cmd_rec = SQLSelect("delete  FROM ble_devices  ");
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
ble_devices: ADDED varchar(100) NOT NULL DEFAULT ''
ble_devices: ENABLE int(1) 

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


$cmd="sudo hcitool leinfo $mac";
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





 function getvalues($id) {

$cmd_rec = SQLSelectOne("SELECT * FROM ble_devices where ID='$id'");
$id=$cmd_rec['ID'];
$mac=$cmd_rec['MAC'];
$type=$cmd_rec['MAC'];





$file = ROOT.'cms/cached/bletools'; // полный путь к нужному файлу
//echo php_uname();
//echo PHP_OS;
$debug = file_get_contents($file);
$debug .= "get info about $mac run at ".gg('sysdate').' '.gg('timenow')."<br>\n";
file_put_contents($file, $debug);


//echo "это линус";
//$cmd='nmap -sn 192.168.1.0/24';
//$cmd='echo 192.168.1.{1..254}|xargs -n1 -P0 ping -c1|grep "bytes from"';
$data = array();

$this->resethci();

switch ($type) {
   case "eQ-3-radiator-thermostat":


	$cmd="sudo hcitool leinfo $mac";
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

	$sql="SELECT * FROM ble_commands where IDDEV='$id' and parametr='".trim($par)."'";
	//echo $sql."<br>";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['debug']=$answ;

	//if (array_key_exists($key,$cmd_rec2)) $cmd_rec2[$key]=$val;
	$cmd_rec2['IDDEV']=$id;
	$cmd_rec2['value']=trim($val);
	$cmd_rec2['parametr']=trim($par);

	if (($cmd_rec2['value'])&&($cmd_rec2['parametr']))
	{
	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}
	}}
break;
   case "mi-flora-plant":


	$cmd="sudo hcitool leinfo $mac";
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

	$sql="SELECT * FROM ble_commands where IDDEV='$id' and parametr='".trim($par)."'";
	//echo $sql."<br>";
	$cmd_rec2 = SQLSelectOne($sql);
	$cmd_rec2['debug']=$answ;

	//if (array_key_exists($key,$cmd_rec2)) $cmd_rec2[$key]=$val;
	$cmd_rec2['IDDEV']=$id;
	$cmd_rec2['value']=trim($val);
	$cmd_rec2['parametr']=trim($par);

	if (($cmd_rec2['value'])&&($cmd_rec2['parametr']))
	{
	if (!$cmd_rec2['ID']) 
	{
	//$cmd_rec['ONLINE']=$onlinest;
	SQLInsert('ble_commands', $cmd_rec2);
	} else {
	SQLUpdate('ble_commands', $cmd_rec2);
	}
	}}
break;

}





//return print_r($data);





}






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

 function getvendor($mac) {

$url="https://macvendors.co/api/$mac/json";
$file = file_get_contents($url);
$data=json_decode($file,true);
//echo $file;
//echo "<br>";
$vendor=$data['result']['company'];
return $vendor;


}
 
// --------------------------------------------------------------------
}
/*
*
* TW9kdWxlIGNyZWF0ZWQgTWFyIDEzLCAyMDE2IHVzaW5nIFNlcmdlIEouIHdpemFyZCAoQWN0aXZlVW5pdCBJbmMgd3d3LmFjdGl2ZXVuaXQuY29tKQ==
*
*/





