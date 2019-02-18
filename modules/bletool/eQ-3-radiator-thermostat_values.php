<?php
//https://github.com/Heckie75/eQ-3-radiator-thermostat/blob/master/eq-3-radiator-thermostat-api.md



$answ=$this->getraweq3($mac, "0x0411", "03");

//$answ=$this->gethandlevalue($id,'0x0411', '03');

$this->extractanswereq3($answ, $id);

