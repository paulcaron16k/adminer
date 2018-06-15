<?php

openlog("ADMINER", LOG_PID | LOG_PERROR, LOG_LOCAL0);

function checkSecure()
{
    $isSecure = false;
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        $isSecure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        strncmp(trim($_SERVER['HTTP_X_FORWARDED_PROTO']), 'https', 5) == 0)
    {
        /* May be a CSV of forwarded protocols.
         * If using AWS App Load-Balancer and HAProxy, this header is:
         * X-Forwarded-Proto: https, http
         */
        $isSecure = true;
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) &&
        $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
    {
        $isSecure = true;
    }
    
    if (!$isSecure) {
        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirect);
        exit();
    }
}
checkSecure();


function adminer_object() {
    /* Load $ADMINER_SERVERS config data */
    include "./serversConfig.php";

    // required to run any plugin
    include_once "./plugins/plugin.php";

    // autoloader
    foreach (glob("plugins/*.php") as $filename) {
        include_once "./$filename";
    }

    $plugins = array(
        new PasswordProtection(),
        new AdminerServersConfig($ADMINER_SERVERS),

		// Include plugins from adminer/plugin.php
		new AdminerDatabaseHide(array('information_schema')),
		new AdminerDumpJson,
		new AdminerDumpBz2,
		new AdminerDumpZip,
		new AdminerDumpXml,
		new AdminerDumpAlter,
		new AdminerFileUpload(""),
		new AdminerJsonColumn,
		new AdminerSlugify,
		new AdminerTranslation,
		new AdminerForeignSystem,
		new AdminerEnumOption,
		new AdminerTablesFilter,
		new AdminerEditForeign,
	);
	
	/* It is possible to combine customization and plugins:
	class AdminerCustomization extends AdminerPlugin {
	}
	return new AdminerCustomization($plugins);
	*/

    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./adminer.php";
