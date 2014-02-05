<?php
require_once 'SystemComponent.php';
require_once 'functions.php';
class DbConnector extends SystemComponent {
var $theQuery;
var $link;

function DbConnector(){

        $settings = SystemComponent::getSettings();

        $dns = $settings['systemdns'];
        $user = $settings['dbusername'];
        $pass = $settings['dbpassword'];

        $this->link = odbc_connect($dns, $user, $pass);
}

function query($query) {
        $this->theQuery = $query;
        return odbc_exec($this->link, $query);
}

function getQuery() {
        return $this->theQuery;
}

function getNumRows($count){
        $result = odbc_exec($this->link,$this->theQuery);
        $count=0;
        while($temp = odbc_fetch_into($result, &$counter)){
        $count++;
        }
        return $count;
}

function fetchArray($result) {
        return odbc_fetch_array($result);
}

function close() {
       odbc_close($this->link);
}
}
?>