<?php  /* create:2017-02-18 13:56:28*/

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

(new Query('DROP TABLE IF EXISTS #{article}'))->exec();        $effect=($query_article=new Query('CREATE TABLE `#{article}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'文章ID\',
	`author` bigint(20) NOT NULL   COMMENT \'作者\',
	`categroy` int(11) NOT NULL   COMMENT \'文章分类\',
	`index` varchar(128) NOT NULL   COMMENT \'文章索引\',
	`title` varchar(255) NOT NULL   COMMENT \'文章标题\',
	`abstract` varchar(255) NOT NULL   COMMENT \'摘要\',
	`content` text NOT NULL   COMMENT \'文章内容\',
	`type` tinyint(1) NOT NULL   COMMENT \'内容类型\',
	`view` int(11) NOT NULL   COMMENT \'阅读\',
	`create` int(11) NOT NULL   COMMENT \'创建时间\',
	`update` int(11) NOT NULL   COMMENT \'最后更新\',
	`reply` int(11) NOT NULL   COMMENT \'回复\',
	`allow_reply` tinyint(1) NOT NULL DEFAULT \'1\'  COMMENT \'可回复\',
	`state` tinyint(1) NOT NULL DEFAULT \'1\'  COMMENT \'文章状态\',
	PRIMARY KEY (`id`),
	KEY `author` (`author`),
	KEY `categroy` (`categroy`),
	KEY `index` (`index`),
	KEY `title` (`title`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_article->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'article Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'article Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{article_attachment}'))->exec();        $effect=($query_article_attachment=new Query('CREATE TABLE `#{article_attachment}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'文件ID\',
	`article` bigint(20) NOT NULL   COMMENT \'文章ID\',
	`data` bigint(20) NOT NULL   COMMENT \'数据\',
	`type` bigint(1) NOT NULL   COMMENT \'类型\',
	PRIMARY KEY (`id`),
	KEY `article` (`article`),
	KEY `data` (`data`),
	KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_article_attachment->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'article_attachment Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'article_attachment Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{article_comment}'))->exec();        $effect=($query_article_comment=new Query('CREATE TABLE `#{article_comment}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'评论ID\',
	`article` bigint(20) NOT NULL   COMMENT \'评论的文章\',
	`count` int(11) NOT NULL   COMMENT \'评论计数\',
	`reply` bigint(20) NOT NULL   COMMENT \'被评论数\',
	`author` bigint(20) NOT NULL   COMMENT \'评论的人\',
	`text` varchar(500) NOT NULL   COMMENT \'评论内容\',
	`time` int(11) NOT NULL   COMMENT \'评论的时间\',
	`ip` varchar(20) NOT NULL   COMMENT \'评论IP\',
	`state` tinyint(1) NOT NULL   COMMENT \'状态\',
	PRIMARY KEY (`id`),
	KEY `article` (`article`),
	KEY `count` (`count`),
	KEY `reply` (`reply`),
	KEY `author` (`author`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_article_comment->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'article_comment Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'article_comment Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{article_reply}'))->exec();        $effect=($query_article_reply=new Query('CREATE TABLE `#{article_reply}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'回复ID\',
	`reply` bigint(20) NOT NULL   COMMENT \'回复的回复\',
	`comment` bigint(20) NOT NULL   COMMENT \'回复的评论\',
	`author` bigint(20) NOT NULL   COMMENT \'回复的人\',
	`text` varchar(500) NOT NULL   COMMENT \'回复内容\',
	`time` int(11) NOT NULL   COMMENT \'回复的时间\',
	`ip` varchar(20) NOT NULL   COMMENT \'回复IP\',
	`state` tinyint(1) NOT NULL   COMMENT \'状态\',
	PRIMARY KEY (`id`),
	KEY `reply` (`reply`),
	KEY `comment` (`comment`),
	KEY `author` (`author`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_article_reply->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'article_reply Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'article_reply Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{article_tag}'))->exec();        $effect=($query_article_tag=new Query('CREATE TABLE `#{article_tag}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'索引\',
	`article` bigint(20) NOT NULL   COMMENT \'文章ID\',
	`tag` bigint(20) NOT NULL   COMMENT \'标签ID\',
	PRIMARY KEY (`id`),
	KEY `article` (`article`),
	UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_article_tag->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'article_tag Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'article_tag Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{categroy}'))->exec();        $effect=($query_categroy=new Query('CREATE TABLE `#{categroy}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'分类ID\',
	`icon` bigint(20) NOT NULL   COMMENT \'分类图标资源\',
	`name` varchar(20) NOT NULL   COMMENT \'分类名\',
	`slug` varchar(20) NOT NULL   COMMENT \'英文缩写\',
	`discription` varchar(255) NOT NULL   COMMENT \'分类描述\',
	`sort` int(11) NOT NULL   COMMENT \'排序\',
	`count` int(11) NOT NULL   COMMENT \'分类下的文章\',
	`parent` bigint(20) NOT NULL   COMMENT \'父分类\',
	PRIMARY KEY (`id`),
	KEY `icon` (`icon`),
	KEY `name` (`name`),
	KEY `slug` (`slug`),
	KEY `sort` (`sort`),
	KEY `parent` (`parent`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_categroy->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'categroy Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'categroy Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{notification_data}'))->exec();        $effect=($query_notification_data=new Query('CREATE TABLE `#{notification_data}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'通知ID\',
	`data` text NOT NULL   COMMENT \'通知数据\',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_notification_data->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'notification_data Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'notification_data Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{notification}'))->exec();        $effect=($query_notification=new Query('CREATE TABLE `#{notification}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'通知ID\',
	`send` bigint(20) NOT NULL   COMMENT \'发送人\',
	`recv` bigint(20) NOT NULL   COMMENT \'接受人\',
	`type` int(11) NOT NULL   COMMENT \'通知类型\',
	`time` int(11) NOT NULL   COMMENT \'通知时间\',
	`state` tinyint(1) NOT NULL   COMMENT \'状态\',
	`data` bigint(20) NOT NULL   COMMENT \'通知内容\',
	PRIMARY KEY (`id`),
	KEY `send` (`send`),
	KEY `recv` (`recv`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_notification->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'notification Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'notification Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{site_navigation}'))->exec();        $effect=($query_site_navigation=new Query('CREATE TABLE `#{site_navigation}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'导航ID\',
	`name` varchar(80) NOT NULL   COMMENT \'导航名\',
	`url` varchar(255) NOT NULL   COMMENT \'导航URL\',
	`state` tinyint(1) NOT NULL   COMMENT \'状态\',
	`sort` int(11) NOT NULL   COMMENT \'排序\',
	`parent` bigint(20) NOT NULL   COMMENT \'父导航\',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `state` (`state`),
	KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_site_navigation->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'site_navigation Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'site_navigation Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{site_setting}'))->exec();        $effect=($query_site_setting=new Query('CREATE TABLE `#{site_setting}` (
	`id` int(11) NOT NULL  AUTO_INCREMENT COMMENT \'设置ID\',
	`name` varchar(80) NOT NULL   COMMENT \'设置KEY\',
	`value` varchar(255) NOT NULL   COMMENT \'设置数据\',
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_site_setting->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'site_setting Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'site_setting Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{tag}'))->exec();        $effect=($query_tag=new Query('CREATE TABLE `#{tag}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'分类标签\',
	`name` varchar(20) NOT NULL   COMMENT \'标签名\',
	`count` int(11) NOT NULL   COMMENT \'标签下的内容\',
	PRIMARY KEY (`id`),
	KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_tag->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'tag Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'tag Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{token_client}'))->exec();        $effect=($query_token_client=new Query('CREATE TABLE `#{token_client}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'客户端ID\',
	`name` varchar(80) NOT NULL   COMMENT \'客户端名\',
	`description` varchar(255) NOT NULL   COMMENT \'客户端描述\',
	`token` varchar(32) NOT NULL   COMMENT \'客户端识别码\',
	`auths` text NOT NULL   COMMENT \'权限描述\',
	`time` int(11) NOT NULL   COMMENT \'创建时间\',
	`beat` int(11) NOT NULL   COMMENT \'最低心跳\',
	`alive` int(11) NOT NULL   COMMENT \'登陆超时\',
	`state` int(1) NOT NULL   COMMENT \'客户端状态\',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `token` (`token`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_token_client->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'token_client Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'token_client Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{token}'))->exec();        $effect=($query_token=new Query('CREATE TABLE `#{token}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'令牌ID\',
	`user` bigint(20) NOT NULL   COMMENT \'使用的用户\',
	`token` varchar(32) NOT NULL   COMMENT \'令牌\',
	`client` bigint(20) NOT NULL   COMMENT \'客户端\',
	`ip` varchar(32) NOT NULL   COMMENT \'使用令牌的ID\',
	`time` int(11) NOT NULL   COMMENT \'使用的时间\',
	`expire` int(11) NOT NULL   COMMENT \'过期时间\',
	`value` varchar(255) NOT NULL   COMMENT \'附加值\',
	PRIMARY KEY (`id`),
	KEY `user` (`user`),
	KEY `token` (`token`),
	KEY `client` (`client`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_token->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'token Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'token Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{upload_data}'))->exec();        $effect=($query_upload_data=new Query('CREATE TABLE `#{upload_data}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'文件ID\',
	`hash` varchar(32) NOT NULL   COMMENT \'MD5哈希\',
	`time` int(11) NOT NULL   COMMENT \'最后更新时间\',
	`ref` int(11) NOT NULL   COMMENT \'引用计数\',
	PRIMARY KEY (`id`),
	KEY `hash` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_upload_data->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'upload_data Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'upload_data Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{upload}'))->exec();        $effect=($query_upload=new Query('CREATE TABLE `#{upload}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'文件ID\',
	`uid` bigint(20) NOT NULL   COMMENT \'使用用户\',
	`name` varchar(80) NOT NULL   COMMENT \'文件名\',
	`size` int(11) NOT NULL   COMMENT \'文件大小\',
	`time` int(11) NOT NULL   COMMENT \'上传时间\',
	`type` varchar(10) NOT NULL   COMMENT \'扩展名\',
	`data` bigint(20) NOT NULL   COMMENT \'文件数据\',
	`state` tinyint(1) NOT NULL   COMMENT \'状态\',
	PRIMARY KEY (`id`),
	KEY `uid` (`uid`),
	KEY `type` (`type`),
	KEY `data` (`data`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_upload->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'upload Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'upload Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{user_group}'))->exec();        $effect=($query_user_group=new Query('CREATE TABLE `#{user_group}` (
	`id` int(11) NOT NULL  AUTO_INCREMENT COMMENT \'分组ID\',
	`user` bigint(20) NOT NULL   COMMENT \'用户ID\',
	`name` varchar(80) NOT NULL   COMMENT \'分组名\',
	`sort` int(11) NOT NULL   COMMENT \'排序索引\',
	`auths` text NOT NULL   COMMENT \'权限数据\',
	PRIMARY KEY (`id`),
	UNIQUE KEY `user` (`user`),
	KEY `name` (`name`),
	KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_user_group->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'user_group Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'user_group Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{user_option_log}'))->exec();        $effect=($query_user_option_log=new Query('CREATE TABLE `#{user_option_log}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'日志ID\',
	`user_id` bigint(20) NOT NULL   COMMENT \'使用的用户\',
	`name` varchar(80) NOT NULL   COMMENT \'操作名\',
	`sketch` varchar(255) NOT NULL   COMMENT \'操作附加描述\',
	`ip` varchar(32) NOT NULL   COMMENT \'使用令牌的ID\',
	`time` int(11) NOT NULL   COMMENT \'使用的时间\',
	PRIMARY KEY (`id`),
	KEY `user_id` (`user_id`),
	KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_user_option_log->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'user_option_log Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'user_option_log Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{user}'))->exec();        $effect=($query_user=new Query('CREATE TABLE `#{user}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'用户ID\',
	`name` varchar(13) NOT NULL   COMMENT \'用户名\',
	`email` varchar(50) NOT NULL   COMMENT \'邮箱\',
	`password` varchar(60) NOT NULL   COMMENT \'密码HASH\',
	`group` bigint(20) NOT NULL DEFAULT \'0\'  COMMENT \'分组ID\',
	`verify_email` int(1) NOT NULL DEFAULT \'0\'  COMMENT \'邮箱验证\',
	`avatar` bigint(20) NOT NULL DEFAULT \'0\'  COMMENT \'头像ID\',
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`),
	UNIQUE KEY `email` (`email`),
	KEY `group` (`group`),
	KEY `verify_email` (`verify_email`),
	KEY `avatar` (`avatar`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_user->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'user Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'user Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{verify}'))->exec();        $effect=($query_verify=new Query('CREATE TABLE `#{verify}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'请求ID\',
	`alive` int(11) NOT NULL   COMMENT \'过期时间\',
	`data` varchar(255) NOT NULL   COMMENT \'数据\',
	PRIMARY KEY (`id`),
	KEY `alive` (`alive`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_verify->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'verify Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'verify Error!,effect '.$effect.' rows'."\r\n";   
        }


(new Query('DROP TABLE IF EXISTS #{vote_reply}'))->exec();        $effect=($query_vote_reply=new Query('CREATE TABLE `#{vote_reply}` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT \'ID\',
	`root` bigint(20) NOT NULL   COMMENT \'文章ID\',
	`item` bigint(20) NOT NULL   COMMENT \'回复ID\',
	`user` bigint(20) NOT NULL   COMMENT \'用户ID\',
	`score` int(1) NOT NULL   COMMENT \'正赞负踩\',
	`time` int(11) NOT NULL   COMMENT \'操作时间\',
	`ip` varchar(32) NOT NULL   COMMENT \'操作IP\',
	PRIMARY KEY (`id`),
	KEY `root` (`root`),
	KEY `item` (`item`),
	KEY `user` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;'))->exec();
        if ($query_vote_reply->erron()==0){
            echo 'Create Table:'.\Config::get('database.prefix').'vote_reply Ok,effect '.$effect.' rows'."\r\n";
        }
        else{
             echo 'Create Table:'.\Config::get('database.prefix').'vote_reply Error!,effect '.$effect.' rows'."\r\n";   
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