<?php  /* create:2017-02-20 17:10:51*/

    try {
    /** Open Transaction Avoid Error **/
    Query::beginTransaction();


    $effect=($create=new Query('CREATE DATABASE IF NOT EXISTS '.Config::get('database.name').';'))->exec();
    if ($create->erron()==0){
            echo 'Create Database '.Config::get('database.name').' Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
            die('Database '.Config::get('database.name').'create filed!');   
        }

(new Query('DROP TABLE IF EXISTS #{user}'))->exec();        $effect=($query_user=new Query('CREATE TABLE `#{user}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'用户ID\',
	`name` varchar(13) NOT NULL   COMMENT \'用户名\',
	`email` varchar(50) NOT NULL   COMMENT \'邮箱\',
	`password` varchar(60) NOT NULL   COMMENT \'密码HASH\',
	`group` bigint(20) NOT NULL DEFAULT \'0\'  COMMENT \'分组ID\',
	`available` int(1) NOT NULL DEFAULT \'0\'  COMMENT \'邮箱验证\',
	`ip` varchar(32) NOT NULL   COMMENT \'注册IP\',
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`),
	UNIQUE KEY `email` (`email`),
	KEY `group` (`group`),
	KEY `available` (`available`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_user->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'user Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'user Error!,effect '.$effect.' rows'."\r\n";   
        }

    /** End Querys **/
    Query::commit();
    return true;
    } 
    catch (Exception $e)
    {
        Query::rollBack();
    return false;
    }