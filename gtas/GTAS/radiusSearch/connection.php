<?php 
// Database information
	$CFG_DB= array();
	$CFG_DB['db_host']= 'localhost';
	$CFG_DB['db_user']= 'test';
	$CFG_DB['db_pass']= 'test';
	$CFG_DB['db_base']= 'testdb';
	
	/* ----------------------------- */
	/* Connecting to MySQL server: */
	/* ----------------------------- */
	@mysql_connect($CFG_DB['db_host'], $CFG_DB['db_user'], $CFG_DB['db_pass'])
		or die("Error: mysql_connect() failed");
	
	/* ----------------------------- */
	/* Selecting client character set: */
	/* ----------------------------- */
	mysql_set_charset('utf8');
	
	/* ----------------------------- */
	/* Selecting database: */
	/* ----------------------------- */
	@mysql_select_db($CFG_DB['db_base'])
		or die("Error: mysql_select_db() failed");
	
?>
