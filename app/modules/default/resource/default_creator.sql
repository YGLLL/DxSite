-- create:2017-02-20 17:10:51

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

