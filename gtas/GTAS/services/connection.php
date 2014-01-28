<?php 
// Database information
	$CFG_DB= array();
	$CFG_DB['db_host']= 'db482651200.db.1and1.com';
	$CFG_DB['db_user']= 'dbo482651200';
	$CFG_DB['db_pass']= 'zDF274*jk9';
	$CFG_DB['db_base']= 'db482651200';
	
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