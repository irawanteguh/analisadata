<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	$active_group  = 'production';
	$query_builder = TRUE;

	$db['development'] = array(
		'dsn'	=> '',
		'hostname' => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.200.76)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED) (SERVICE_NAME=devrspm)))',	
		'username' => 'SIMRS_MANAGER',
		'password' => 'SIMRS_SYSTEM',
		'database' => '',
		'dbdriver' => 'oci8',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'development'),
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);

	$db['production'] = array(
		'dsn'	=> '',
		'hostname' => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=rac-scan.rsudpm.local)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED) (SERVICE_NAME=PRODPM)))',
		'username' => 'SIMRS_MANAGER',
		'password' => 'SIMRS_SYSTEM',
		'database' => '',
		'dbdriver' => 'oci8',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'production'),
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);
?>