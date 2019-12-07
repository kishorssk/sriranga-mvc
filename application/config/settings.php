<?php

// db table names
define('DEFAULT_LETTER', 'A');
define('METADATA_TABLE', 'encyclopaedia');
define('WORKSHOP_TABLE', 'registration');
define('ORG_NAME', 'Sriranga Digital Software Technologies Private Limited');

// search settings
define('SEARCH_OPERAND', 'AND');

// user settings (login and registration)
define('REQUIRE_EMAIL_VALIDATION', True);//Set these values to True only
define('REQUIRE_RESET_PASSWORD', True);//if outbound mails can be sent from the server

// mailer settings
define('SERVICE_EMAIL', 'sdst.sriranga.digital@gmail.com');
define('SERVICE_EMAIL_PASSWORD', 'sriranga@646203');
define('SERVICE_NAME', 'Sriranga Team ' . ORG_NAME);

define('unitPrice', 1000);
define('keyId', '');
define('keySecret', '');

define('dbServer','localhost');
define('dbName', 'srirangadb');
define('dbUser','root');
define('dbPassword','mysql');
?>
