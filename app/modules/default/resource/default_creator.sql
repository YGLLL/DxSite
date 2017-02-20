-- create:2017-02-20 20:11:00

CREATE TABLE `notification_data` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '通知ID',
	`data` text NOT NULL   COMMENT '通知数据',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `notification` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '通知ID',
	`send` bigint(20) NOT NULL   COMMENT '发送人',
	`recv` bigint(20) NOT NULL   COMMENT '接受人',
	`type` int(11) NOT NULL   COMMENT '通知类型',
	`time` int(11) NOT NULL   COMMENT '通知时间',
	`state` tinyint(1) NOT NULL   COMMENT '状态',
	`data` bigint(20) NOT NULL   COMMENT '通知内容',
	PRIMARY KEY (`id`),
	KEY `send` (`send`),
	KEY `recv` (`recv`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `upload_data` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '文件ID',
	`hash` varchar(32) NOT NULL   COMMENT 'MD5哈希',
	`time` int(11) NOT NULL   COMMENT '最后更新时间',
	`ref` int(11) NOT NULL   COMMENT '引用计数',
	PRIMARY KEY (`id`),
	KEY `hash` (`hash`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `upload` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '文件ID',
	`uid` bigint(20) NOT NULL   COMMENT '使用用户',
	`name` varchar(80) NOT NULL   COMMENT '文件名',
	`size` int(11) NOT NULL   COMMENT '文件大小',
	`time` int(11) NOT NULL   COMMENT '上传时间',
	`type` varchar(10) NOT NULL   COMMENT '扩展名',
	`data` bigint(20) NOT NULL   COMMENT '文件数据',
	`state` tinyint(1) NOT NULL   COMMENT '状态',
	PRIMARY KEY (`id`),
	KEY `uid` (`uid`),
	KEY `type` (`type`),
	KEY `data` (`data`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `user_client` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '客户端ID',
	`name` varchar(80) NOT NULL   COMMENT '客户端名',
	`description` varchar(255) NOT NULL   COMMENT '客户端描述',
	`token` varchar(32) NOT NULL   COMMENT '客户端识别码',
	`auths` text NOT NULL   COMMENT '权限描述',
	`time` int(11) NOT NULL   COMMENT '创建时间',
	`beat` int(11) NOT NULL   COMMENT '最低心跳',
	`alive` int(11) NOT NULL   COMMENT '登陆超时',
	`state` int(1) NOT NULL   COMMENT '客户端状态',
	PRIMARY KEY (`id`),
	KEY `name` (`name`),
	KEY `token` (`token`),
	KEY `state` (`state`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `user_group` (
	`id` int(11) NOT NULL  AUTO_INCREMENT COMMENT '分组ID',
	`user` bigint(20) NOT NULL   COMMENT '用户ID',
	`name` varchar(80) NOT NULL   COMMENT '分组名',
	`sort` int(11) NOT NULL   COMMENT '排序索引',
	`auths` text NOT NULL   COMMENT '权限数据',
	PRIMARY KEY (`id`),
	UNIQUE KEY `user` (`user`),
	KEY `name` (`name`),
	KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `user_token` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '令牌ID',
	`user` bigint(20) NOT NULL   COMMENT '使用的用户',
	`token` varchar(32) NOT NULL   COMMENT '令牌',
	`client` bigint(20) NOT NULL   COMMENT '客户端',
	`ip` varchar(32) NOT NULL   COMMENT '使用令牌的ID',
	`time` int(11) NOT NULL   COMMENT '使用的时间',
	`expire` int(11) NOT NULL   COMMENT '过期时间',
	`value` varchar(255) NOT NULL   COMMENT '附加值',
	PRIMARY KEY (`id`),
	KEY `user` (`user`),
	KEY `token` (`token`),
	KEY `client` (`client`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;


CREATE TABLE `user` (
	`id` bigint(20) NOT NULL  AUTO_INCREMENT COMMENT '用户ID',
	`name` varchar(13) NOT NULL   COMMENT '用户名',
	`email` varchar(50) NOT NULL   COMMENT '邮箱',
	`password` varchar(60) NOT NULL   COMMENT '密码HASH',
	`group` bigint(20) NOT NULL DEFAULT '0'  COMMENT '分组ID',
	`available` int(1) NOT NULL DEFAULT '0'  COMMENT '邮箱验证',
	`ip` varchar(32) NOT NULL   COMMENT '注册IP',
	PRIMARY KEY (`id`),
	UNIQUE KEY `name` (`name`),
	UNIQUE KEY `email` (`email`),
	KEY `group` (`group`),
	KEY `available` (`available`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

