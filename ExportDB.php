<?php

require_once 'core/XCore.php';

echo 'export Database:'.APP_RES.'/debug-datebase.php '.Database::export(APP_RES.'/debug-datebase.php')."\r\n";
echo 'export SQL Database '.APP_RES.'/debug-datebase.sql '.Database::exportSQL(APP_RES.'/debug-datebase.sql')."\r\n";