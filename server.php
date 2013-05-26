<?php
include('../../../wp-load.php');
//define( 'ABSPATH', dirname(__FILE__) . '/');
define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
define( 'WP_SOAP_SERVICES_DIR', WP_PLUGIN_DIR .  '/wp-soap-services' );
define( 'NUSOAP_DIR', WP_SOAP_SERVICES_DIR . '/lib' );

if (!function_exists('timestamp_to_iso8601')) {
	include(NUSOAP_DIR . '/nusoap.php');
}
include(WP_SOAP_SERVICES_DIR . '/soap-functions.php');
include(WP_SOAP_SERVICES_DIR . '/soap-registrations.php');


$namespace = "http://" . $_SERVER['SERVER_NAME'] . "/wsdl";

$server = new soap_server();
$server->debug_flag = false;
$server->configureWSDL("WPSoapServices",$namespace);
$server->wsdl->schemaTargetNamespace = $namespace;

foreach($soap_registrations as $registration) {
	$server->register($registration['method_name'],
					  $registration['input'],
					  $registration['output'],
					  $registration['namespace'],
					  $registration['soapaction'],
					  $registration['style'],
					  $registration['use'],
					  $registration['documentation']
	);
}

$HTTP_RAW_POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
$server->service($HTTP_RAW_POST_DATA);

exit();

?>
