<?php

class SystemComponent {

    var $settings;

    function getSettings() {

        $settings['systemdns'] = '';        // Host Name

        $settings['systemdns'] = '';        // Data Source Name
        $settings['dbusername'] = '';       // Login Name (if any)
        $settings['dbpassword'] = '';       // Password (if any)

        return $settings;

    }

}
?>
