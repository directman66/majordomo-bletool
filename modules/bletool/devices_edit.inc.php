<?php
/*
* @version 0.1 (wizard)
*/
//echo $this->mode;
// echo $this->tab."     ".$id;
  if ($this->owner->name=='panel') {
   $out['CONTROLPANEL']=1;
  }

//echo $this->tab;
$table_name='ble_devices';

//echo $this->mode;
if ((($this->tab=='infoedit')||($this->tab=='services')||($this->tab=='data')||($this->tab=='handles'))&&($this->mode=='')) {


$sql="SELECT * FROM $table_name WHERE ID='$id'";
$rec=SQLSelectOne($sql);
$out['ID']=$id;
$out['VENDOR']=$rec['VENDOR'];
$out['TITLE']=$rec['TITLE'];
$out['MAC']=$rec['MAC'];
}

//echo "<br>";
//echo '$this->tab:'.  $this->tab;
//echo "<br>";
//echo '$this->mode:'.  $this->mode;

 
if (($this->tab=='infoedit')&&($this->mode=='update')) {

   $ok=1;
  // step: default
$sql="SELECT * FROM ble_devices WHERE ID='$id'";
$rec=SQLSelectOne($sql);

  //updating '<%LANG_TITLE%>' (varchar, required)
   global $title;
   $rec['TITLE']=$title;

   global $type;
   $rec['TYPE']=$type;
  
   global $polling;
   $rec['POLLING']=$polling;
  

    if ($ok=1)
    SQLUpdate('ble_devices', $rec);
}




//echo $this->tab.":".$this->mode;
if (($this->tab=='data')&&($this->mode=='update')) {


   $properties=SQLSelect("SELECT * FROM ble_commands WHERE DEVICE_ID='".$id."' ORDER BY ID");
//print_r($properties);

   $total=count($properties);
   for($i=0;$i<$total;$i++) {
    if ($properties[$i]['ID']==$new_id) continue;
    if ($this->mode=='update') {
        /*
      global ${'title'.$properties[$i]['ID']};
      $properties[$i]['TITLE']=trim(${'title'.$properties[$i]['ID']});
      global ${'value'.$properties[$i]['ID']};
      $properties[$i]['VALUE']=trim(${'value'.$properties[$i]['ID']});
        */
      global ${'linked_object'.$properties[$i]['ID']};
      $properties[$i]['LINKED_OBJECT']=trim(${'linked_object'.$properties[$i]['ID']});
      global ${'linked_property'.$properties[$i]['ID']};
      $properties[$i]['LINKED_PROPERTY']=trim(${'linked_property'.$properties[$i]['ID']});

      global ${'linked_method'.$properties[$i]['ID']};
      $properties[$i]['LINKED_METHOD'] = trim(${'linked_method'.$properties[$i]['ID']});


      SQLUpdate('ble_commands', $properties[$i]);
      $old_linked_object=$properties[$i]['LINKED_OBJECT'];
      $old_linked_property=$properties[$i]['LINKED_PROPERTY'];

      if ($old_linked_object && $old_linked_object!=$properties[$i]['LINKED_OBJECT'] && $old_linked_property && $old_linked_property!=$properties[$i]['LINKED_PROPERTY']) {
       removeLinkedProperty($old_linked_object, $old_linked_property, $this->name);
      }
     }///update

       if ($properties[$i]['LINKED_OBJECT'] && $properties[$i]['LINKED_PROPERTY']) {
           addLinkedProperty($properties[$i]['LINKED_OBJECT'], $properties[$i]['LINKED_PROPERTY'], $this->name);
       }
   }
  $this->redirect("?view_mode=edit_devices&tab=data&id=$id");
}
   






if (($this->tab=='handles')&&($this->mode=='')) {
   $properties=SQLSelect("SELECT * FROM ble_handles WHERE IDDEV='".$id."' ORDER BY ID");
   $total=count($properties);
   for($i=0;$i<$total;$i++) {
$out['PROPERTIES']=$properties;      


}}



if (($this->tab=='services')&&($this->mode=='')) {
   $properties=SQLSelect("SELECT * FROM ble_services WHERE IDDEV='".$id."' ORDER BY ID");
   $total=count($properties);
   for($i=0;$i<$total;$i++) {
$out['PROPERTIES']=$properties;      


}}


  //UPDATING RECORD
  // step: default
  if ($this->tab=='data') {
   //dataset2
   $new_id=0;


   global $delete_id;
   if ($delete_id) {
    SQLExec("DELETE FROM ble_commands WHERE ID='".(int)$delete_id."'");
   }





   $properties=SQLSelect("SELECT * FROM ble_commands WHERE DEVICE_ID='".$rec['ID']."' ORDER BY ID");
   $total=count($properties);
   for($i=0;$i<$total;$i++) {
    if ($properties[$i]['ID']==$new_id) continue;
    if ($this->mode=='update') {
        /*
      global ${'title'.$properties[$i]['ID']};
      $properties[$i]['TITLE']=trim(${'title'.$properties[$i]['ID']});
      global ${'value'.$properties[$i]['ID']};
      $properties[$i]['VALUE']=trim(${'value'.$properties[$i]['ID']});
        */
      global ${'linked_object'.$properties[$i]['ID']};
      $properties[$i]['LINKED_OBJECT']=trim(${'linked_object'.$properties[$i]['ID']});
      global ${'linked_property'.$properties[$i]['ID']};
      $properties[$i]['LINKED_PROPERTY']=trim(${'linked_property'.$properties[$i]['ID']});
      SQLUpdate('ble_commands', $properties[$i]);
      $old_linked_object=$properties[$i]['LINKED_OBJECT'];
      $old_linked_property=$properties[$i]['LINKED_PROPERTY'];
//тХи╨▒тХд╨гтХи╨░тХе╨бтХи╨░тФмтЦСтХи╨░тФмтХЧтХи╨░тФмтХбтХи╨░тХи╨ХтХи╨░тХд╨бтХи╨░тФмтХб linked
      if ($old_linked_object && $old_linked_object!=$properties[$i]['LINKED_OBJECT'] && $old_linked_property && $old_linked_property!=$properties[$i]['LINKED_PROPERTY']) {
       removeLinkedProperty($old_linked_object, $old_linked_property, $this->name);
      }
     }///update
//тХи╨░тХе╨бтХи╨░тХд╨етХи╨░тФмтЦТтХи╨░тФмтЦСтХи╨░тХи╨ЦтХи╨░тФмтХЧтХи╨░тФмтХбтХи╨░тХи╨ХтХи╨░тХд╨бтХи╨░тФмтХб linked
       if ($properties[$i]['LINKED_OBJECT'] && $properties[$i]['LINKED_PROPERTY']) {
           addLinkedProperty($properties[$i]['LINKED_OBJECT'], $properties[$i]['LINKED_PROPERTY'], $this->name);
       }
       
       
       
   }
   $out['PROPERTIES']=$properties;   
  }
  if (is_array($rec)) {
   foreach($rec as $k=>$v) {
    if (!is_array($v)) {
     $rec[$k]=htmlspecialchars($v);
    }
   }
  }
  outHash($rec, $out);
