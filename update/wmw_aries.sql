-- phpMyAdmin SQL Dump
-- version 2.11.2.1
-- http://www.phpmyadmin.net
--
-- 主机: 192.168.1.254
-- 生成日期: 2013 年 03 月 13 日 10:30
-- 服务器版本: 5.5.3
-- PHP 版本: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `wmw_aries`
--

-- --------------------------------------------------------

--
-- 表的结构 `bms_school_request`
--

CREATE TABLE `bms_school_request` (
  `school_request_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL,
  `add_account` varchar(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`school_request_id`),
  UNIQUE KEY `index_school_request` (`school_request_id`),
  KEY `fk_school_id` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3012 ;

-- --------------------------------------------------------

--
-- 表的结构 `china_unicom`
--

CREATE TABLE `china_unicom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` bigint(20) NOT NULL,
  `sim_time` datetime NOT NULL,
  `area_code` char(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_id` (`phone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81115 ;

-- --------------------------------------------------------

--
-- 表的结构 `kd_online_voice`
--

CREATE TABLE `kd_online_voice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `category` smallint(4) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `grade` varchar(40) NOT NULL,
  `subject` varchar(40) NOT NULL,
  `pic_url` varchar(255) NOT NULL,
  `author` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1912 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_department`
--

CREATE TABLE `oa_department` (
  `dpt_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门id，主键',
  `school_id` int(11) NOT NULL COMMENT '学校id',
  `sort_id` int(11) NOT NULL COMMENT '部门自定义编号，用于排序',
  `dpt_name` varchar(50) NOT NULL COMMENT '部门名称',
  `dpt_phone` varchar(11) NOT NULL COMMENT '部门电话',
  `dpt_description` varchar(500) NOT NULL COMMENT '部门职能',
  `dpt_photo` varchar(50) NOT NULL COMMENT '部门人员照片',
  `up_id` int(11) NOT NULL COMMENT '上级部门id',
  PRIMARY KEY (`dpt_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='学校部门表' AUTO_INCREMENT=1512 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_department_members`
--

CREATE TABLE `oa_department_members` (
  `dptmb_id` int(11) NOT NULL AUTO_INCREMENT,
  `dpt_id` int(11) NOT NULL COMMENT '部门id',
  `client_account` bigint(20) NOT NULL,
  `role_ids` varchar(100) NOT NULL COMMENT '一个人在一个部门可以存在多个角色，使用逗号分开',
  `duty_name` varchar(50) NOT NULL COMMENT '职务名称',
  `sort_id` mediumint(9) DEFAULT NULL COMMENT '??’?o?id',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`dptmb_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='部门成员关系表,主关键词(client_account+department_id)' AUTO_INCREMENT=10282 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_pretreat_sms`
--

CREATE TABLE `oa_pretreat_sms` (
  `pretreat_id` int(11) NOT NULL AUTO_INCREMENT,
  `accept_phone` varchar(11) NOT NULL COMMENT '接收人账号列表, 用逗号分割',
  `sms_message` varchar(150) NOT NULL COMMENT '推送信息',
  `push_time` int(11) NOT NULL COMMENT '短信推送时间',
  `business_type` tinyint(4) NOT NULL COMMENT '学校对应的业务类型',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`pretreat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='短信预处理信息表' AUTO_INCREMENT=138 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_role`
--

CREATE TABLE `oa_role` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `role_access` int(11) NOT NULL COMMENT '角色权限,用二进制表示',
  `add_account` bigint(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户角色表' AUTO_INCREMENT=11188 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_role_system`
--

CREATE TABLE `oa_role_system` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `role_name` varchar(50) NOT NULL COMMENT '角色名',
  `role_access` int(11) NOT NULL COMMENT '角色权限,用二进制表示',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='系统角色表' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_schedule`
--

CREATE TABLE `oa_schedule` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) NOT NULL,
  `schedule_title` varchar(50) NOT NULL COMMENT '日程名称',
  `schedule_message` text NOT NULL COMMENT '日程内容',
  `type_id` int(11) NOT NULL,
  `schedule_start_time` int(11) NOT NULL,
  `is_draft` tinyint(4) NOT NULL COMMENT '是否是草稿，1表示是，0表示不是',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `upd_time` int(11) DEFAULT NULL,
  `expiration_time` int(11) DEFAULT NULL COMMENT '过期时间',
  `deadline_hours` int(11) DEFAULT NULL COMMENT '提醒时间',
  PRIMARY KEY (`schedule_id`),
  KEY `client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='个人日程管理表' AUTO_INCREMENT=463 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_schedule_type`
--

CREATE TABLE `oa_schedule_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `client_account` bigint(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户自定义日程类型表' AUTO_INCREMENT=10086 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_schedule_type_system`
--

CREATE TABLE `oa_schedule_type_system` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='系统日程分类信息表' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_task`
--

CREATE TABLE `oa_task` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '工作id',
  `task_title` varchar(100) NOT NULL COMMENT '工作标题',
  `task_content` text NOT NULL COMMENT '工作内容',
  `task_type` int(11) NOT NULL COMMENT '分类',
  `to_accounts` text NOT NULL COMMENT '接受人列表，按照部门进行分组保存',
  `expiration_time` int(11) NOT NULL COMMENT '交付日期',
  `deadline_hours` int(11) NOT NULL COMMENT '提醒日期',
  `add_time` int(11) NOT NULL COMMENT '添加时间',
  `upd_time` int(11) NOT NULL COMMENT '更新时间',
  `need_reply` tinyint(4) NOT NULL COMMENT '是否需要回复，1表示需要，0表示不需要',
  `need_sms_remind` tinyint(4) NOT NULL COMMENT '是否需要发送短信',
  `need_sms_push` tinyint(4) NOT NULL COMMENT '是否需要短信推送',
  `tag_ids` varchar(100) NOT NULL COMMENT '标签id,使用逗号分割',
  `add_account` bigint(20) NOT NULL COMMENT '工作发布人账号',
  `school_id` int(11) NOT NULL COMMENT '工作所属学校',
  `is_draft` tinyint(4) NOT NULL COMMENT '草稿状态，1表示草稿，0表示正式',
  PRIMARY KEY (`task_id`),
  KEY `add_account` (`add_account`),
  KEY `school_id` (`school_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='工作部署表' AUTO_INCREMENT=456 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_task_push`
--

CREATE TABLE `oa_task_push` (
  `push_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) NOT NULL,
  `task_id` int(11) NOT NULL,
  `is_viewed` tinyint(4) NOT NULL,
  `is_replied` tinyint(4) NOT NULL,
  `add_time` int(11) NOT NULL,
  `task_type` int(11) NOT NULL,
  PRIMARY KEY (`push_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='工作推送表，主关键词(client_account+task_id, 联合索引)' AUTO_INCREMENT=4856 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_task_reply`
--

CREATE TABLE `oa_task_reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT,
  `task_id` int(11) NOT NULL,
  `add_account` bigint(20) NOT NULL,
  `reply_content` varchar(200) COLLATE utf8_estonian_ci NOT NULL COMMENT '回复内容',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`reply_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_estonian_ci COMMENT='工作回复表，主关键词(task_id+add_account)' AUTO_INCREMENT=252 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_task_tags`
--

CREATE TABLE `oa_task_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL COMMENT '标签名称',
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='标签表，主关键词(tag_id),school_id应该属于属性外键' AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_task_tag_relation`
--

CREATE TABLE `oa_task_tag_relation` (
  `ttr_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`ttr_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='标签和工作的关系表' AUTO_INCREMENT=158 ;

-- --------------------------------------------------------

--
-- 表的结构 `oa_task_type_system`
--

CREATE TABLE `oa_task_type_system` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(50) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='系统工作类型表' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_album_info`
--

CREATE TABLE `old_wmw_album_info` (
  `album_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_name` varchar(30) NOT NULL,
  `album_explain` varchar(200) DEFAULT NULL,
  `album_img` varchar(50) DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `album_create_type` int(11) NOT NULL,
  `add_date` int(11) NOT NULL,
  `upd_date` int(11) NOT NULL,
  PRIMARY KEY (`album_id`),
  UNIQUE KEY `index_xiangce_info` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1273066 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_class_album`
--

CREATE TABLE `old_wmw_class_album` (
  `class_album_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `class_code` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`class_album_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1924 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_class_feed`
--

CREATE TABLE `old_wmw_class_feed` (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_content` mediumblob,
  `client_account` bigint(20) DEFAULT NULL,
  `upd_time` int(11) NOT NULL,
  `class_code` int(11) NOT NULL,
  PRIMARY KEY (`feed_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20738 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_class_log`
--

CREATE TABLE `old_wmw_class_log` (
  `class_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `class_code` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`class_log_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17390 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_class_talk`
--

CREATE TABLE `old_wmw_class_talk` (
  `talk_id` int(11) NOT NULL AUTO_INCREMENT,
  `talk_content` varchar(500) NOT NULL,
  `class_code` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` int(11) NOT NULL,
  `comment_nums` int(11) DEFAULT NULL,
  PRIMARY KEY (`talk_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61158 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_class_talkcomment`
--

CREATE TABLE `old_wmw_class_talkcomment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `talk_id` int(11) NOT NULL,
  `comment_content` varchar(500) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `talk_id` (`talk_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_client_feed`
--

CREATE TABLE `old_wmw_client_feed` (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_name` varchar(6000) NOT NULL,
  `feed_type_id` int(11) NOT NULL,
  `feed_type` int(11) NOT NULL,
  `add_account` bigint(20) NOT NULL,
  `add_date` datetime NOT NULL,
  `class_code` int(11) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`feed_id`),
  KEY `fk_class_code` (`class_code`),
  KEY `add_account_add_time` (`add_account`,`add_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1263172 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_curriculum_info`
--

CREATE TABLE `old_wmw_curriculum_info` (
  `curriculum_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_code` int(11) NOT NULL,
  `am_content` varchar(500) NOT NULL,
  `pm_content` varchar(500) NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_time` int(11) DEFAULT NULL,
  `subject_content` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`curriculum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6683 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_curriculum_skin`
--

CREATE TABLE `old_wmw_curriculum_skin` (
  `skin_id` int(11) NOT NULL AUTO_INCREMENT,
  `skin_name` varchar(20) NOT NULL,
  `skin_value` varchar(20) NOT NULL,
  PRIMARY KEY (`skin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_exam_info`
--

CREATE TABLE `old_wmw_exam_info` (
  `exam_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL,
  `class_code` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `exam_name` varchar(50) NOT NULL,
  `exam_date` varchar(10) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime DEFAULT NULL,
  `exam_well` float DEFAULT NULL,
  `subtype` int(11) DEFAULT NULL,
  `exam_good` float(4,1) NOT NULL DEFAULT '0.0',
  `exam_bad` float(4,1) NOT NULL DEFAULT '0.0',
  PRIMARY KEY (`exam_id`),
  UNIQUE KEY `index_exam_info` (`exam_id`),
  KEY `fk_school_id` (`school_id`),
  KEY `fk_class_code` (`class_code`),
  KEY `fk_subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1169 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_log_plun`
--

CREATE TABLE `old_wmw_log_plun` (
  `plun_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_id` int(11) NOT NULL,
  `plun_content` varchar(6000) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`plun_id`),
  UNIQUE KEY `index_log_plun` (`plun_id`),
  KEY `fk_log_id` (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3693270 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_log_types`
--

CREATE TABLE `old_wmw_log_types` (
  `logtype_id` int(11) NOT NULL AUTO_INCREMENT,
  `logtype_name` varchar(20) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `log_create_type` int(11) NOT NULL,
  PRIMARY KEY (`logtype_id`),
  UNIQUE KEY `index_log_types` (`logtype_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1299106 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_news_info`
--

CREATE TABLE `old_wmw_news_info` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_type` varchar(4) NOT NULL,
  `news_title` varchar(100) DEFAULT NULL,
  `news_toaccount` bigint(20) DEFAULT NULL,
  `news_content` varchar(6000) NOT NULL,
  `class_code` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `sendMessage` varchar(10) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime DEFAULT NULL,
  `expiration_date` datetime NOT NULL COMMENT '过期时间',
  `attachment` varchar(100) CHARACTER SET utf8 COLLATE utf8_estonian_ci DEFAULT NULL,
  `state` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  UNIQUE KEY `index_news_info` (`news_id`),
  KEY `fk_class_code` (`class_code`),
  KEY `fk_subject_id` (`subject_id`),
  KEY `news_toaccount_index` (`news_toaccount`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3932582 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_person_logs`
--

CREATE TABLE `old_wmw_person_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_name` varchar(60) NOT NULL,
  `log_content` text NOT NULL,
  `log_type` varchar(35) NOT NULL,
  `read_count` int(11) NOT NULL,
  `log_status` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime NOT NULL,
  `contentbg` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  UNIQUE KEY `index_person_logs` (`log_id`),
  KEY `fk_log_type_id` (`log_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1035878 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_person_talk`
--

CREATE TABLE `old_wmw_person_talk` (
  `sign_id` int(11) NOT NULL AUTO_INCREMENT,
  `sign_content` varchar(6000) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` int(11) NOT NULL,
  `comment_nums` int(11) DEFAULT NULL,
  PRIMARY KEY (`sign_id`),
  UNIQUE KEY `index_client_sign` (`sign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1030494 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_person_talkcomment`
--

CREATE TABLE `old_wmw_person_talkcomment` (
  `plun_id` int(11) NOT NULL AUTO_INCREMENT,
  `sign_id` int(11) NOT NULL,
  `plun_content` varchar(6000) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` int(11) NOT NULL,
  PRIMARY KEY (`plun_id`),
  UNIQUE KEY `index_sign_plun` (`plun_id`),
  KEY `fk_sign_id` (`sign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1992 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_photos_info`
--

CREATE TABLE `old_wmw_photos_info` (
  `photo_id` int(11) NOT NULL AUTO_INCREMENT,
  `album_id` int(11) NOT NULL,
  `photo_name` varchar(50) NOT NULL,
  `photo_url` varchar(50) NOT NULL,
  `photo_min_url` varchar(50) NOT NULL,
  `photo_explain` varchar(600) DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `add_date` int(11) NOT NULL,
  `upd_date` int(11) NOT NULL,
  PRIMARY KEY (`photo_id`),
  UNIQUE KEY `index_photos_info` (`photo_id`),
  KEY `fk_album_id` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1359829 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_photo_plun`
--

CREATE TABLE `old_wmw_photo_plun` (
  `plun_id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `plun_content` varchar(6000) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `photo_account` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`plun_id`),
  UNIQUE KEY `index_photo_plun` (`plun_id`),
  KEY `fk_photo_id` (`photo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1237184 ;

-- --------------------------------------------------------

--
-- 表的结构 `old_wmw_student_score`
--

CREATE TABLE `old_wmw_student_score` (
  `score_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) DEFAULT NULL,
  `exam_id` int(11) NOT NULL,
  `exam_score` float NOT NULL,
  `score_py` varchar(150) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime DEFAULT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`score_id`),
  UNIQUE KEY `index_student_score` (`client_account`,`exam_id`),
  KEY `fk_exam_id` (`exam_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46703 ;

-- --------------------------------------------------------

--
-- 表的结构 `sms_send`
--

CREATE TABLE `sms_send` (
  `sms_send_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sms_send_mphone` varchar(2000) NOT NULL,
  `sms_send_content` varchar(70) NOT NULL,
  `sms_send_mphone_num` tinyint(1) NOT NULL,
  `sms_send_type` tinyint(1) NOT NULL,
  `sms_send_result_info` varchar(50) DEFAULT NULL,
  `sms_send_transact_datetime` datetime DEFAULT NULL,
  `db_createtime` datetime NOT NULL,
  `db_updatetime` datetime DEFAULT NULL,
  `db_delete` bit(1) NOT NULL DEFAULT b'0',
  `sms_send_bussiness_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`sms_send_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- 表的结构 `sms_send_tmp`
--

CREATE TABLE `sms_send_tmp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(600) NOT NULL,
  `operation_strategy` varchar(30) NOT NULL,
  `send_status` tinyint(4) NOT NULL,
  `send_range` varchar(10) NOT NULL,
  `send_time` int(11) NOT NULL,
  `real_send_time` int(11) NOT NULL,
  `add_uid` bigint(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `send_time` (`send_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='短信群发过渡表' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `sms_send_tmp_class`
--

CREATE TABLE `sms_send_tmp_class` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(11) NOT NULL,
  `send_tmp_id` int(11) NOT NULL,
  `operation_strategy` tinyint(4) NOT NULL,
  `add_date` varchar(21) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `sms_send_tmp_school`
--

CREATE TABLE `sms_send_tmp_school` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL,
  `send_tmp_id` int(11) NOT NULL,
  `operation_strategy` tinyint(4) NOT NULL,
  `add_date` varchar(21) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `uc_login_attempts`
--

CREATE TABLE `uc_login_attempts` (
  `client_account` bigint(20) unsigned NOT NULL,
  `client_ip` int(11) unsigned NOT NULL,
  `attempts` mediumint(10) NOT NULL,
  `upd_time` int(11) NOT NULL,
  PRIMARY KEY (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `uc_oauth_bind`
--

CREATE TABLE `uc_oauth_bind` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` int(11) unsigned NOT NULL,
  `social_account` varchar(64) NOT NULL,
  `social_type` varchar(20) NOT NULL,
  `access_token` varchar(64) NOT NULL,
  `add_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `social_account` (`social_account`,`social_type`),
  KEY `client_account` (`client_account`,`social_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `uc_oauth_clients`
--

CREATE TABLE `uc_oauth_clients` (
  `client_id` int(11) unsigned NOT NULL,
  `app` varchar(10) NOT NULL,
  `client_secret` varchar(20) NOT NULL,
  `redirect_uri` varchar(200) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `uc_oauth_codes`
--

CREATE TABLE `uc_oauth_codes` (
  `code` varchar(40) NOT NULL,
  `client_id` int(11) unsigned NOT NULL,
  `redirect_uri` varchar(200) NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `uc_oauth_tokens`
--

CREATE TABLE `uc_oauth_tokens` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `access_token` varchar(40) NOT NULL,
  `client_id` int(11) unsigned NOT NULL,
  `expires` int(11) NOT NULL,
  `scope` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `oauth_token` (`access_token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=546093 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_account_lock`
--

CREATE TABLE `wmw_account_lock` (
  `lock_account` bigint(20) NOT NULL DEFAULT '0',
  `account_length` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`lock_account`),
  UNIQUE KEY `index_account_lock` (`lock_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_account_relation`
--

CREATE TABLE `wmw_account_relation` (
  `relation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) NOT NULL DEFAULT '0',
  `friend_account` bigint(20) NOT NULL DEFAULT '0',
  `friend_group` int(11) DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `index_account_relation` (`client_account`,`friend_account`) USING BTREE,
  KEY `fk_friend_group` (`friend_group`),
  KEY `client_account` (`client_account`),
  KEY `friend_account` (`friend_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_account_rule`
--

CREATE TABLE `wmw_account_rule` (
  `account_length` int(11) NOT NULL,
  `use_flag` varchar(1) NOT NULL,
  `use_count` varchar(20) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime NOT NULL,
  PRIMARY KEY (`account_length`),
  UNIQUE KEY `index_account_rule` (`account_length`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album`
--

CREATE TABLE `wmw_album` (
  `album_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_name` varchar(30) NOT NULL COMMENT '班级相册名称',
  `album_explain` varchar(200) NOT NULL COMMENT '对相册进行描述(不超过60字)',
  `album_img` varchar(50) NOT NULL COMMENT '相册封面图片',
  `add_account` bigint(20) unsigned NOT NULL COMMENT '添加信息人的账号',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加信息的时间',
  `upd_account` bigint(20) unsigned NOT NULL COMMENT '修改信息人的账号',
  `upd_time` int(10) unsigned NOT NULL COMMENT '修改信息的时间',
  `album_auto_img` varchar(50) NOT NULL COMMENT '系统相册封面',
  `photo_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '相片数',
  PRIMARY KEY (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='相册信息表' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album_class_grants`
--

CREATE TABLE `wmw_album_class_grants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` bigint(20) unsigned NOT NULL COMMENT '班级code',
  `album_id` int(10) unsigned NOT NULL COMMENT '相册id',
  `grant` tinyint(1) unsigned NOT NULL COMMENT '相册查看权限，0:公开 1:本班 2:管理员 3:本学校',
  PRIMARY KEY (`id`),
  KEY `class_code` (`class_code`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='班级相册权限表' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album_class_relation`
--

CREATE TABLE `wmw_album_class_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(20) unsigned NOT NULL COMMENT '班级编号',
  `album_id` int(11) unsigned NOT NULL COMMENT '相册id',
  PRIMARY KEY (`id`),
  KEY `class_code` (`class_code`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='班级与相册关系表' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album_person_grants`
--

CREATE TABLE `wmw_album_person_grants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `album_id` int(10) unsigned NOT NULL COMMENT '相册id',
  `grant` tinyint(3) unsigned NOT NULL COMMENT '相册查看权限，0:公开 1:好友 2:仅主人',
  PRIMARY KEY (`id`),
  KEY `client_account` (`client_account`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人相册权限表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album_person_relation`
--

CREATE TABLE `wmw_album_person_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `album_id` int(11) unsigned NOT NULL COMMENT '相册id',
  PRIMARY KEY (`id`),
  KEY `client_account` (`client_account`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=ucs2 COMMENT='个人与相册关系表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album_photos`
--

CREATE TABLE `wmw_album_photos` (
  `photo_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '照片id',
  `album_id` int(10) unsigned NOT NULL COMMENT '照片所属相册',
  `name` varchar(50) NOT NULL COMMENT '照片名称',
  `file_big` varchar(50) NOT NULL COMMENT '照片链接地址',
  `file_middle` varchar(50) NOT NULL COMMENT '照片缩略图',
  `file_small` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL COMMENT '照片描述',
  `comments` mediumint(9) unsigned NOT NULL COMMENT '照片评论总数',
  `upd_account` bigint(20) unsigned NOT NULL COMMENT '最后更新人账号',
  `upd_time` int(11) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='照片信息表' AUTO_INCREMENT=139 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_album_photo_comments`
--

CREATE TABLE `wmw_album_photo_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增',
  `up_id` int(10) unsigned NOT NULL COMMENT '对照片的评论内容id评论 或者 对相片的评论的评论',
  `photo_id` int(10) unsigned NOT NULL COMMENT '照片id',
  `content` varchar(255) NOT NULL COMMENT '对照片的评论内容',
  `client_account` bigint(20) unsigned NOT NULL COMMENT '评论人账号',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `level` tinyint(3) unsigned NOT NULL COMMENT '只支持两级 1，2 1:对照片的评论2:对照片评论的评',
  PRIMARY KEY (`comment_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='照片评论表' AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_ams_account`
--

CREATE TABLE `wmw_ams_account` (
  `ams_account` bigint(20) NOT NULL,
  `ams_password` varchar(35) NOT NULL,
  `ams_name` varchar(60) NOT NULL,
  `ams_email` varchar(60) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`ams_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog`
--

CREATE TABLE `wmw_blog` (
  `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `type_id` int(11) unsigned NOT NULL,
  `views` int(11) unsigned NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `contentbg` varchar(100) NOT NULL,
  `summary` varchar(800) NOT NULL,
  `comments` mediumint(8) unsigned NOT NULL,
  `add_account` bigint(20) unsigned NOT NULL,
  `add_time` int(11) unsigned NOT NULL,
  `upd_account` bigint(20) unsigned NOT NULL,
  `upd_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `fk_add_account` (`add_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=247 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_class_grants`
--

CREATE TABLE `wmw_blog_class_grants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(10) unsigned NOT NULL COMMENT '班级编号',
  `blog_id` int(10) unsigned NOT NULL COMMENT '日志ID',
  `grant` tinyint(1) NOT NULL COMMENT '权限',
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='班级日志权限表' AUTO_INCREMENT=218 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_class_relation`
--

CREATE TABLE `wmw_blog_class_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(10) unsigned NOT NULL COMMENT '班级编号',
  `blog_id` int(10) unsigned NOT NULL COMMENT '日志ID',
  PRIMARY KEY (`id`),
  KEY `class_code` (`class_code`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='班级与日志关系表' AUTO_INCREMENT=224 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_comments`
--

CREATE TABLE `wmw_blog_comments` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) unsigned NOT NULL COMMENT '日志ID ',
  `content` varchar(255) NOT NULL COMMENT '对日志的评论内容',
  `up_id` int(10) unsigned NOT NULL COMMENT '对日志的评论内容id评论 或者 对日志的评论的评论',
  `client_account` bigint(20) unsigned NOT NULL COMMENT '评论信息人的账号',
  `add_time` int(10) unsigned NOT NULL COMMENT '评论信息时的时间',
  `level` tinyint(1) NOT NULL COMMENT '只支持两级 1，2 1:对照片的评论2:对照片评论的评论',
  PRIMARY KEY (`comment_id`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='日志评论表' AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_content`
--

CREATE TABLE `wmw_blog_content` (
  `blog_id` int(11) unsigned NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_person_grants`
--

CREATE TABLE `wmw_blog_person_grants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `blog_id` int(10) unsigned NOT NULL COMMENT '日志ID',
  `grant` tinyint(1) NOT NULL COMMENT '权限',
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `client_account` (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人日志权限表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_person_relation`
--

CREATE TABLE `wmw_blog_person_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `blog_id` int(10) unsigned NOT NULL COMMENT '日志ID',
  PRIMARY KEY (`id`),
  KEY `client_account` (`client_account`),
  KEY `blog_id` (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人与日志关系表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_types`
--

CREATE TABLE `wmw_blog_types` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `add_account` bigint(20) unsigned NOT NULL,
  `add_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_types_class_relation`
--

CREATE TABLE `wmw_blog_types_class_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(10) unsigned NOT NULL COMMENT '班级编号',
  `type_id` int(10) unsigned NOT NULL COMMENT '日志类型ID',
  PRIMARY KEY (`id`),
  KEY `class_code` (`class_code`),
  KEY `blog_id` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='班级与日志关系表' AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_blog_types_person_relation`
--

CREATE TABLE `wmw_blog_types_person_relation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `type_id` int(10) unsigned NOT NULL COMMENT '日志类型ID',
  PRIMARY KEY (`id`),
  KEY `client_account` (`client_account`),
  KEY `blog_id` (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人与日志关系表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_bms_account`
--

CREATE TABLE `wmw_bms_account` (
  `base_account` varchar(20) NOT NULL,
  `base_password` varchar(35) NOT NULL,
  `base_name` varchar(60) NOT NULL,
  `add_account` varchar(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  `base_email` varchar(30) DEFAULT '0',
  PRIMARY KEY (`base_account`),
  UNIQUE KEY `index_base_account` (`base_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_business_phone`
--

CREATE TABLE `wmw_business_phone` (
  `account_phone_id1` bigint(20) NOT NULL DEFAULT '0',
  `account_phone_id2` bigint(20) DEFAULT NULL,
  `seq_type` tinyint(1) DEFAULT '0',
  `dbcreatetime` datetime NOT NULL,
  `dbupdatetime` datetime NOT NULL,
  PRIMARY KEY (`account_phone_id1`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_business_phone_error_log`
--

CREATE TABLE `wmw_business_phone_error_log` (
  `wbp_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `wbp_log_bnum` varchar(20) DEFAULT NULL,
  `wbp_log_phone` varchar(11) DEFAULT NULL,
  `wbp_log_begtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `wbp_log_error_content` text NOT NULL,
  `wbp_log_error_flag` tinyint(1) NOT NULL DEFAULT '1',
  `wbp_log_error_type` tinyint(10) NOT NULL,
  `client_ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`wbp_log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=217 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_business_phone_log`
--

CREATE TABLE `wmw_business_phone_log` (
  `wbp_log_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `wbp_log_bnum` varchar(50) NOT NULL,
  `wbp_log_phone` bigint(20) NOT NULL,
  `wbp_log_begtime` datetime NOT NULL,
  `wbp_log_name` varchar(20) DEFAULT NULL,
  `wbp_log_type` tinyint(1) NOT NULL,
  `wbp_log_flag` tinyint(1) NOT NULL,
  `wbp_log_opername` varchar(20) NOT NULL,
  `wbp_log_opertime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`wbp_log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93061 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_checkin`
--

CREATE TABLE `wmw_checkin` (
  `checkin_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '签到ID，主键自增',
  `client_account` bigint(20) unsigned NOT NULL COMMENT '签到用户账号',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`checkin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_course`
--

CREATE TABLE `wmw_class_course` (
  `course_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(11) unsigned NOT NULL,
  `weekday` tinyint(1) NOT NULL,
  `num_th` tinyint(1) NOT NULL,
  `name` varchar(21) NOT NULL,
  `upd_account` bigint(20) unsigned NOT NULL,
  `upd_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`course_id`),
  KEY `fk_class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=17854 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_course_config`
--

CREATE TABLE `wmw_class_course_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL,
  `skin_id` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2931 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_course_skin`
--

CREATE TABLE `wmw_class_course_skin` (
  `skin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `url` varchar(20) NOT NULL COMMENT '课程表皮肤',
  `small_img` varchar(20) DEFAULT NULL COMMENT '课程表皮肤小图',
  PRIMARY KEY (`skin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_exam`
--

CREATE TABLE `wmw_class_exam` (
  `exam_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(10) unsigned NOT NULL,
  `subject_id` int(10) unsigned NOT NULL,
  `exam_name` varchar(20) NOT NULL,
  `exam_time` int(10) unsigned NOT NULL,
  `add_account` bigint(20) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `upd_account` bigint(20) unsigned NOT NULL,
  `upd_time` int(10) unsigned NOT NULL,
  `exam_good` float unsigned NOT NULL,
  `exam_bad` float unsigned NOT NULL,
  `exam_well` float unsigned NOT NULL,
  `is_published` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否发布 0：草稿 1：发布',
  `is_sms` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '否是发短信 0：不发送 1：发送',
  PRIMARY KEY (`exam_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_exam_score`
--

CREATE TABLE `wmw_class_exam_score` (
  `score_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL,
  `exam_id` int(10) unsigned NOT NULL,
  `exam_score` float unsigned NOT NULL,
  `score_py` varchar(150) NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `add_account` bigint(20) unsigned NOT NULL,
  `upd_time` int(10) unsigned NOT NULL,
  `upd_account` bigint(20) unsigned NOT NULL,
  `is_join` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_sms` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`score_id`),
  KEY `exam_id` (`exam_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=86 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_homework`
--

CREATE TABLE `wmw_class_homework` (
  `homework_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(10) unsigned NOT NULL,
  `subject_id` int(10) unsigned NOT NULL,
  `add_account` bigint(20) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `upd_account` bigint(20) unsigned NOT NULL,
  `upd_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `attachment` varchar(50) NOT NULL,
  `content` varchar(200) NOT NULL,
  `is_sms` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `accepters` varchar(10) NOT NULL,
  PRIMARY KEY (`homework_id`),
  KEY `class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_homework_send`
--

CREATE TABLE `wmw_class_homework_send` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `homework_id` int(10) unsigned NOT NULL,
  `client_account` bigint(20) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  `is_view` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `homework_id` (`homework_id`,`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2629 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_info`
--

CREATE TABLE `wmw_class_info` (
  `class_code` int(11) NOT NULL AUTO_INCREMENT,
  `school_id` int(11) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  `headteacher_account` bigint(20) DEFAULT NULL,
  `upgrade_year` smallint(6) NOT NULL,
  PRIMARY KEY (`class_code`),
  UNIQUE KEY `index_class_info` (`class_code`),
  KEY `fk_school_id` (`school_id`),
  KEY `fk_headteacher` (`headteacher_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24668 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_info_history`
--

CREATE TABLE `wmw_class_info_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_code` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `add_account` bigint(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  `headteacher_account` bigint(20) NOT NULL,
  `upgrade_year` smallint(6) NOT NULL,
  `graduation_time` int(11) NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=452 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_notice`
--

CREATE TABLE `wmw_class_notice` (
  `notice_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class_code` int(11) unsigned NOT NULL,
  `notice_title` varchar(20) NOT NULL,
  `notice_content` varchar(200) NOT NULL,
  `add_account` bigint(20) unsigned NOT NULL,
  `add_time` int(11) unsigned NOT NULL,
  `is_sms` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`notice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_notice_foot`
--

CREATE TABLE `wmw_class_notice_foot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `notice_id` int(10) unsigned NOT NULL,
  `client_account` bigint(20) unsigned NOT NULL,
  `add_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notice_id` (`notice_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_style`
--

CREATE TABLE `wmw_class_style` (
  `style_id` int(11) NOT NULL AUTO_INCREMENT,
  `style_css` varchar(30) NOT NULL,
  `style_name` varchar(30) NOT NULL,
  `style_img` varchar(30) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`style_id`),
  UNIQUE KEY `index_style_id` (`style_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_class_teacher`
--

CREATE TABLE `wmw_class_teacher` (
  `class_teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) DEFAULT NULL,
  `class_code` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_time` int(11) DEFAULT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`class_teacher_id`),
  KEY `client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112780 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_account`
--

CREATE TABLE `wmw_client_account` (
  `client_account` bigint(20) NOT NULL,
  `client_password` varchar(35) NOT NULL,
  `client_type` tinyint(4) NOT NULL,
  `client_name` varchar(20) NOT NULL,
  `client_headimg` varchar(40) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '-1',
  `add_time` int(11) NOT NULL,
  `upd_time` int(11) NOT NULL,
  `active_date` int(11) NOT NULL,
  `lastlogin_date` int(11) NOT NULL,
  PRIMARY KEY (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_active`
--

CREATE TABLE `wmw_client_active` (
  `active_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `value` mediumint(8) unsigned NOT NULL COMMENT '用户总活跃值',
  PRIMARY KEY (`active_id`),
  UNIQUE KEY `client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=640 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_active_log`
--

CREATE TABLE `wmw_client_active_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) unsigned NOT NULL COMMENT '账号',
  `value` mediumint(8) unsigned NOT NULL COMMENT '本次所得活跃值',
  `message` varchar(255) NOT NULL COMMENT '活跃说明',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `module` smallint(4) NOT NULL,
  `action` tinyint(4) NOT NULL,
  PRIMARY KEY (`log_id`),
  KEY `client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_class`
--

CREATE TABLE `wmw_client_class` (
  `client_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) DEFAULT NULL,
  `class_code` int(11) DEFAULT NULL,
  `client_class_role` int(11) DEFAULT NULL,
  `teacher_class_role` int(11) DEFAULT NULL,
  `class_admin` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_time` int(11) DEFAULT NULL,
  `client_type` int(11) NOT NULL,
  `sort_seq` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`client_class_id`),
  KEY `fk_client_account` (`client_account`),
  KEY `fk_class_code` (`class_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2557689 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_class_history`
--

CREATE TABLE `wmw_client_class_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_class_id` int(11) NOT NULL,
  `client_account` bigint(20) NOT NULL,
  `class_code` int(11) NOT NULL,
  `client_class_role` int(11) NOT NULL,
  `teacher_class_role` int(11) NOT NULL,
  `class_admin` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `add_account` bigint(20) NOT NULL,
  `upd_account` bigint(20) NOT NULL,
  `upd_time` int(11) NOT NULL,
  `client_type` int(11) NOT NULL,
  `graduation_time` int(11) NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52946 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_group`
--

CREATE TABLE `wmw_client_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) DEFAULT NULL,
  `group_name` varchar(20) NOT NULL,
  `group_type` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` int(11) NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `index_client_group` (`group_id`),
  KEY `fk_client_accoutn` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1004597 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_info`
--

CREATE TABLE `wmw_client_info` (
  `client_account` bigint(20) NOT NULL,
  `client_firstchar` tinyint(4) NOT NULL,
  `client_sex` tinyint(4) NOT NULL,
  `client_birthday` varchar(10) NOT NULL,
  `client_phone` varchar(13) NOT NULL,
  `client_email` varchar(60) NOT NULL,
  `area_id` int(11) NOT NULL,
  `client_trade` smallint(11) NOT NULL,
  `client_job` smallint(11) NOT NULL,
  `client_character` varchar(100) NOT NULL,
  `client_interest` varchar(100) NOT NULL,
  `client_classrole` varchar(100) NOT NULL,
  `like_teacher` varchar(100) NOT NULL,
  `like_subject` varchar(100) NOT NULL,
  `like_cartoon` varchar(100) NOT NULL,
  `like_game` varchar(100) NOT NULL,
  `like_movement` varchar(100) NOT NULL,
  `add_time` int(11) NOT NULL,
  `upd_time` int(11) NOT NULL,
  `client_zodiac` tinyint(4) NOT NULL,
  `client_constellation` tinyint(4) NOT NULL,
  `client_blood_type` tinyint(4) NOT NULL,
  `teach_time` int(11) NOT NULL,
  `client_title` tinyint(4) NOT NULL,
  `job_address_name` varchar(100) NOT NULL,
  `client_address` varchar(100) NOT NULL,
  PRIMARY KEY (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_info_bak`
--

CREATE TABLE `wmw_client_info_bak` (
  `client_account` bigint(20) NOT NULL DEFAULT '0',
  `client_name` varchar(60) DEFAULT NULL,
  `client_firstchar` varchar(1) DEFAULT NULL,
  `client_headimg` varchar(50) DEFAULT NULL,
  `client_sex` int(11) DEFAULT NULL,
  `client_birthday` varchar(10) DEFAULT NULL,
  `client_phone` varchar(13) DEFAULT NULL,
  `area_id` int(11) DEFAULT NULL,
  `client_trade` int(11) DEFAULT NULL,
  `client_job` int(11) DEFAULT NULL,
  `client_character` varchar(400) DEFAULT NULL,
  `client_interest` varchar(400) DEFAULT NULL,
  `client_classrole` varchar(400) DEFAULT NULL,
  `like_teacher` varchar(400) DEFAULT NULL,
  `like_subject` varchar(400) DEFAULT NULL,
  `like_cartoon` varchar(400) DEFAULT NULL,
  `like_game` varchar(400) DEFAULT NULL,
  `like_movement` varchar(400) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `client_type` int(11) NOT NULL,
  `client_zodiac` int(11) DEFAULT NULL,
  `client_constellation` int(11) DEFAULT NULL,
  `client_blood_type` int(11) DEFAULT NULL,
  `teach_time` date DEFAULT NULL,
  `client_title` int(11) DEFAULT NULL,
  `job_address_name` varchar(100) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  `business_enable` int(11) NOT NULL,
  `phone_create_time` datetime DEFAULT NULL,
  `phone_status` int(11) NOT NULL,
  `client_address` varchar(100) DEFAULT NULL,
  `onread` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`client_account`),
  KEY `client_name_index` (`client_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_mibao`
--

CREATE TABLE `wmw_client_mibao` (
  `client_account` bigint(20) NOT NULL DEFAULT '0',
  `asks_one` varchar(60) NOT NULL,
  `answers_one` varchar(60) NOT NULL,
  `asks_two` varchar(60) NOT NULL,
  `answers_two` varchar(60) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime NOT NULL,
  PRIMARY KEY (`client_account`),
  UNIQUE KEY `index_client_mibao` (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_request`
--

CREATE TABLE `wmw_client_request` (
  `client_account` bigint(20) NOT NULL DEFAULT '0',
  `request_type` int(11) NOT NULL,
  `new_birthday` varchar(10) DEFAULT NULL,
  `request_content` varchar(200) DEFAULT NULL,
  `deal_status` int(11) NOT NULL,
  `deal_content` varchar(200) DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime DEFAULT NULL,
  PRIMARY KEY (`client_account`,`request_type`),
  UNIQUE KEY `index_sys_action` (`client_account`,`request_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_client_role_relation`
--

CREATE TABLE `wmw_client_role_relation` (
  `relation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) NOT NULL DEFAULT '0',
  `role_code` varchar(10) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `index_client_role_relation` (`client_account`,`role_code`),
  KEY `client_account` (`client_account`),
  KEY `role_code` (`role_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_communicate_info`
--

CREATE TABLE `wmw_communicate_info` (
  `communicate_id` int(11) NOT NULL AUTO_INCREMENT,
  `communicate_content` varchar(500) NOT NULL,
  `to_account` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  `child_account` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`communicate_id`),
  KEY `child_account` (`child_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8254 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_email_binding`
--

CREATE TABLE `wmw_email_binding` (
  `bind_id` int(11) NOT NULL AUTO_INCREMENT,
  `time33_key` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `client_account` bigint(20) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`bind_id`),
  KEY `index_time33` (`time33_key`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5357 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_family_relation`
--

CREATE TABLE `wmw_family_relation` (
  `relation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) NOT NULL DEFAULT '0',
  `family_account` bigint(20) NOT NULL DEFAULT '0',
  `family_type` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `index_family_id` (`family_account`,`client_account`),
  KEY `client_account` (`client_account`),
  KEY `family_account` (`family_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1607266 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_feed`
--

CREATE TABLE `wmw_feed` (
  `feed_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增',
  `feed_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1：说说 2：日志  3：相册',
  `title` varchar(50) NOT NULL COMMENT '动态标题',
  `add_account` bigint(20) unsigned NOT NULL COMMENT '添加人',
  `timeline` int(10) unsigned NOT NULL COMMENT '添加时间',
  `feed_content` varchar(255) NOT NULL COMMENT '动态内容',
  `img_url` varchar(255) NOT NULL COMMENT '动态中涉及到得图片的url',
  `from_id` int(10) unsigned NOT NULL COMMENT '来源id',
  `action` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '动作 1:发布 2：评论',
  PRIMARY KEY (`feed_id`),
  KEY `add_account` (`add_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='动态表' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_feed_class_relation`
--

CREATE TABLE `wmw_feed_class_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) unsigned NOT NULL COMMENT '动态ID',
  `class_code` int(11) unsigned NOT NULL COMMENT '班级ID',
  `feed_type` tinyint(1) unsigned NOT NULL COMMENT '动态类型',
  `timeline` int(11) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='班级动态关系表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_feed_person_relation`
--

CREATE TABLE `wmw_feed_person_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) unsigned NOT NULL COMMENT '动态ID',
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户帐号',
  `feed_type` tinyint(1) unsigned NOT NULL COMMENT '动态类型',
  `timeline` int(11) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='个人动态关系表' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_feed_timeline`
--

CREATE TABLE `wmw_feed_timeline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) unsigned NOT NULL,
  `feed_type` tinyint(1) NOT NULL,
  `client_account` bigint(20) unsigned NOT NULL,
  `timeline` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='动态时间线表' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_func`
--

CREATE TABLE `wmw_func` (
  `func_code` varchar(20) NOT NULL,
  `func_type` varchar(2) NOT NULL,
  `super_func_code` varchar(20) DEFAULT NULL,
  `func_name` varchar(40) NOT NULL,
  `func_url` varchar(600) NOT NULL,
  `is_showflag` int(11) NOT NULL,
  `func_num` int(11) NOT NULL,
  PRIMARY KEY (`func_code`),
  UNIQUE KEY `index_func` (`func_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_gazx_regist_info`
--

CREATE TABLE `wmw_gazx_regist_info` (
  `regist_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_account` bigint(20) unsigned NOT NULL,
  `parent_phone` bigint(20) unsigned NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `child_account` bigint(20) unsigned NOT NULL,
  `child_phone` bigint(20) unsigned NOT NULL,
  `child_id` varchar(20) NOT NULL,
  `add_date` int(11) NOT NULL,
  PRIMARY KEY (`regist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_guestbook_info`
--

CREATE TABLE `wmw_guestbook_info` (
  `guestbook_id` int(11) NOT NULL AUTO_INCREMENT,
  `to_account` bigint(20) DEFAULT NULL,
  `guestbook_content` varchar(6000) NOT NULL,
  `upid` int(11) DEFAULT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`guestbook_id`),
  KEY `add_account_index` (`add_account`),
  KEY `to_account_index` (`to_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32992 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_jyyz_article`
--

CREATE TABLE `wmw_jyyz_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `article_name` varchar(60) NOT NULL,
  `article_content` text NOT NULL,
  `zhuanlan_type` varchar(2) NOT NULL,
  `fabiao_flag` int(11) NOT NULL,
  `read_counts` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime NOT NULL,
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000102 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_log_stationery`
--

CREATE TABLE `wmw_log_stationery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sty_type` char(10) DEFAULT NULL,
  `sty_name` varchar(50) DEFAULT NULL,
  `sty_url` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_mood`
--

CREATE TABLE `wmw_mood` (
  `mood_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '说说ID，主键自增',
  `content` varchar(255) NOT NULL COMMENT '说说内容',
  `img_url` varchar(255) NOT NULL COMMENT '说说图片，默认为空串',
  `add_account` bigint(20) unsigned NOT NULL COMMENT '添加用户id',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mood_id`),
  KEY `index_add_user` (`add_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='说说表' AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_mood_class_relation`
--

CREATE TABLE `wmw_mood_class_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID，主键自增',
  `class_code` bigint(20) unsigned NOT NULL COMMENT '班级编号',
  `mood_id` int(11) unsigned NOT NULL COMMENT '说说ID',
  PRIMARY KEY (`id`),
  KEY `index_class_code` (`class_code`),
  KEY `index_mood_id` (`mood_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='班级与说说关系表' AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_mood_comments`
--

CREATE TABLE `wmw_mood_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '说说评论id，主键自增',
  `up_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级评论id',
  `mood_id` int(11) unsigned NOT NULL COMMENT '说说id',
  `content` varchar(255) NOT NULL COMMENT '评论内容',
  `client_account` bigint(20) unsigned NOT NULL COMMENT '评论人',
  `add_time` int(10) unsigned NOT NULL COMMENT '评论时间',
  `level` tinyint(1) unsigned NOT NULL COMMENT '评论等级，1:对说说的评论2:对说说评论的评论',
  PRIMARY KEY (`comment_id`),
  KEY `index_mood_id` (`mood_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='说说评论表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_mood_person_relation`
--

CREATE TABLE `wmw_mood_person_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID，主键自增',
  `client_account` bigint(20) unsigned NOT NULL COMMENT '用户账号',
  `mood_id` int(11) unsigned NOT NULL COMMENT '说说ID',
  PRIMARY KEY (`id`),
  KEY `index_client_account` (`client_account`),
  KEY `index_mood_id` (`mood_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='个人与说说关系表' AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_msg_require`
--

CREATE TABLE `wmw_msg_require` (
  `req_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(100) NOT NULL COMMENT '请求内容',
  `to_account` bigint(20) unsigned NOT NULL COMMENT '接收人账号',
  `add_account` bigint(20) unsigned NOT NULL COMMENT '请求人账号',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`req_id`),
  KEY `to_account` (`to_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_msg_response`
--

CREATE TABLE `wmw_msg_response` (
  `res_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(100) NOT NULL COMMENT '请求内容',
  `to_account` bigint(20) unsigned NOT NULL COMMENT '接收人账号',
  `add_account` bigint(20) unsigned NOT NULL COMMENT '回应人账号',
  `add_time` int(10) unsigned NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`res_id`),
  KEY `to_account` (`to_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_old_school_url`
--

CREATE TABLE `wmw_old_school_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(60) NOT NULL,
  `school_url` varchar(60) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `school_url` (`school_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=224 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_password_appeal`
--

CREATE TABLE `wmw_password_appeal` (
  `appeal_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(20) NOT NULL,
  `client_account` bigint(20) NOT NULL,
  `client_phone` varchar(13) NOT NULL,
  `client_email` varchar(60) NOT NULL,
  `area_id` int(11) NOT NULL,
  `school_name` varchar(60) NOT NULL,
  `class_name` varchar(20) NOT NULL,
  `question_description` varchar(100) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`appeal_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4134 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_person_config`
--

CREATE TABLE `wmw_person_config` (
  `client_account` int(11) NOT NULL,
  `space_skin_id` int(11) DEFAULT NULL,
  `space_access` tinyint(1) DEFAULT NULL,
  `space_name` varchar(20) DEFAULT NULL,
  `curriculum_bg_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_person_feed`
--

CREATE TABLE `wmw_person_feed` (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_content` mediumblob,
  `client_account` bigint(20) DEFAULT NULL,
  `upd_time` int(11) NOT NULL,
  PRIMARY KEY (`feed_id`),
  UNIQUE KEY `client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17490 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_person_space_skin`
--

CREATE TABLE `wmw_person_space_skin` (
  `skin_id` int(11) NOT NULL AUTO_INCREMENT,
  `use_type` int(11) NOT NULL,
  `skin_name` varchar(20) NOT NULL,
  `skin_value` varchar(20) NOT NULL,
  PRIMARY KEY (`skin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_person_vistior`
--

CREATE TABLE `wmw_person_vistior` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '主人',
  `vuid` bigint(20) unsigned NOT NULL COMMENT '访客',
  `timeline` int(11) unsigned NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='个人空间访客' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_phone_info`
--

CREATE TABLE `wmw_phone_info` (
  `phone_id` bigint(20) NOT NULL,
  `business_enable_time` int(10) NOT NULL,
  `business_enable` tinyint(1) NOT NULL DEFAULT '0',
  `phone_status` tinyint(1) NOT NULL DEFAULT '0',
  `flag` tinyint(1) NOT NULL DEFAULT '1',
  `dbcreatetime` datetime NOT NULL,
  `dbupdatetime` datetime NOT NULL,
  `phone_type` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`phone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_private_msg`
--

CREATE TABLE `wmw_private_msg` (
  `msg_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '私信Id',
  `send_uid` bigint(20) unsigned NOT NULL COMMENT '发起者',
  `to_uid` bigint(20) unsigned NOT NULL COMMENT '接受者',
  `content` varchar(255) NOT NULL COMMENT '内容',
  `add_time` int(11) unsigned NOT NULL COMMENT '添加时间',
  `img_url` varchar(255) DEFAULT NULL COMMENT '图片url',
  PRIMARY KEY (`msg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=223 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_private_msg_relation`
--

CREATE TABLE `wmw_private_msg_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_uid` bigint(20) unsigned NOT NULL COMMENT '与我相关',
  `to_uid` bigint(20) unsigned NOT NULL COMMENT '与我私信的帐号',
  `new_msg_id` int(11) unsigned NOT NULL COMMENT '最新一条私信ID',
  `msg_count` mediumint(11) unsigned NOT NULL DEFAULT '1' COMMENT '共几条私信',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=108 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_private_msg_session`
--

CREATE TABLE `wmw_private_msg_session` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '私信Id',
  `send_uid` bigint(20) unsigned NOT NULL COMMENT '发起者',
  `to_uid` bigint(20) unsigned NOT NULL COMMENT '接受者',
  `msg_id` int(11) unsigned NOT NULL COMMENT '私信id',
  PRIMARY KEY (`id`),
  KEY `send_uid` (`send_uid`,`to_uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=349 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_py_collect`
--

CREATE TABLE `wmw_py_collect` (
  `collect_id` int(11) NOT NULL AUTO_INCREMENT,
  `py_content` varchar(150) NOT NULL,
  `client_account` bigint(20) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  `py_type` tinyint(4) NOT NULL,
  `py_att` tinyint(4) NOT NULL,
  PRIMARY KEY (`collect_id`),
  KEY `client_account` (`client_account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=399 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_py_info`
--

CREATE TABLE `wmw_py_info` (
  `py_id` int(11) NOT NULL AUTO_INCREMENT,
  `py_content` varchar(150) NOT NULL,
  `add_account` int(11) NOT NULL,
  `add_date` int(11) NOT NULL,
  `py_type` int(11) DEFAULT NULL,
  `py_att` int(11) DEFAULT NULL,
  PRIMARY KEY (`py_id`),
  UNIQUE KEY `index_py_info` (`py_id`),
  KEY `py_type` (`py_type`,`py_att`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=871 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_role_func_relation`
--

CREATE TABLE `wmw_role_func_relation` (
  `relation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_code` varchar(10) NOT NULL,
  `func_code` varchar(20) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `index_role_func_relation` (`role_code`,`func_code`),
  KEY `func_code` (`func_code`),
  KEY `role_code` (`role_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=194 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_role_info`
--

CREATE TABLE `wmw_role_info` (
  `role_code` smallint(10) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(40) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`role_code`),
  UNIQUE KEY `index_role_info` (`role_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_school_client_statistics`
--

CREATE TABLE `wmw_school_client_statistics` (
  `school_id` int(10) NOT NULL COMMENT '学校id',
  `school_name` varchar(100) NOT NULL COMMENT '学校名称',
  `school_address` varchar(100) NOT NULL COMMENT '学校地址',
  `parents_num` int(10) NOT NULL COMMENT '学校父母数量',
  `teacher_num` int(10) NOT NULL COMMENT '学校老师数量',
  `student_num` int(10) NOT NULL COMMENT '学校学生数量',
  `phone_old_num` int(10) NOT NULL COMMENT '学校手机绑定老用户',
  `phone_new_num` int(10) NOT NULL COMMENT '学校手机绑定新用户',
  `teacher_phone_num` int(11) NOT NULL DEFAULT '0',
  `family_phone_num` int(11) NOT NULL DEFAULT '0',
  `area_id` int(9) NOT NULL COMMENT '学校地址码',
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`school_id`),
  KEY `area_id` (`area_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_school_info`
--

CREATE TABLE `wmw_school_info` (
  `school_id` int(11) NOT NULL AUTO_INCREMENT,
  `school_name` varchar(60) NOT NULL,
  `school_address` varchar(100) DEFAULT NULL,
  `school_url_old` varchar(60) DEFAULT NULL,
  `school_url_new` varchar(60) DEFAULT NULL,
  `net_manager_phone` varchar(15) NOT NULL,
  `school_status` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `refuse_reason` varchar(600) DEFAULT NULL,
  `add_account` varchar(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_date` datetime NOT NULL,
  `add_time` int(11) NOT NULL,
  `post_code` varchar(6) NOT NULL,
  `school_create_date` date NOT NULL,
  `school_type` int(11) NOT NULL,
  `resource_advantage` int(11) NOT NULL,
  `school_master` varchar(60) NOT NULL,
  `contact_person` varchar(60) NOT NULL,
  `class_num` int(11) NOT NULL,
  `teacher_num` int(11) NOT NULL,
  `student_num` int(11) NOT NULL,
  `net_manager` varchar(60) NOT NULL,
  `net_manager_email` varchar(30) NOT NULL,
  `school_scan` varchar(120) NOT NULL,
  `check_date` datetime DEFAULT NULL,
  `operation_strategy` int(11) DEFAULT NULL,
  `net_manager_account` bigint(20) DEFAULT NULL,
  `school_logo` varchar(100) DEFAULT NULL,
  `grade_type` tinyint(4) NOT NULL DEFAULT '1',
  `is_pub` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`school_id`),
  UNIQUE KEY `index_school_info` (`school_id`),
  KEY `index_school_url_old` (`school_url_old`),
  KEY `index_school_url_new` (`school_url_new`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3170 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_school_teacher`
--

CREATE TABLE `wmw_school_teacher` (
  `teacher_school_id` int(11) NOT NULL AUTO_INCREMENT,
  `client_account` bigint(20) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `add_time` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `upd_account` bigint(20) DEFAULT NULL,
  `upd_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`teacher_school_id`),
  UNIQUE KEY `index_teacher_school` (`teacher_school_id`),
  KEY `fk_client_account` (`client_account`),
  KEY `fk_school_id` (`school_id`),
  KEY `fk_subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=77430 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_sls_action`
--

CREATE TABLE `wmw_sls_action` (
  `user_id` int(11) NOT NULL,
  `title` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `biddy_code` varchar(150) DEFAULT '',
  `production_name` varchar(150) DEFAULT NULL,
  `production_url` varchar(300) DEFAULT NULL,
  `describes` varchar(600) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `scores` int(11) DEFAULT NULL,
  `bigid` int(11) NOT NULL DEFAULT '0',
  `smallid` int(11) NOT NULL DEFAULT '0',
  `audit_userid` int(11) DEFAULT NULL,
  `add_date` int(11) unsigned zerofill NOT NULL DEFAULT '00000000000',
  PRIMARY KEY (`user_id`,`bigid`,`smallid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_subject_info`
--

CREATE TABLE `wmw_subject_info` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(20) NOT NULL,
  `school_id` int(11) NOT NULL,
  `sys_subject_id` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  `add_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `index_account_relation` (`subject_id`),
  KEY `fk_school_id` (`school_id`),
  KEY `fk_sys_subject_id` (`sys_subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53206 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_sys_notice`
--

CREATE TABLE `wmw_sys_notice` (
  `notice_id` int(11) NOT NULL AUTO_INCREMENT,
  `notice_content` varchar(600) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`notice_id`),
  UNIQUE KEY `index_notice` (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_sys_subject`
--

CREATE TABLE `wmw_sys_subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(20) NOT NULL,
  `subject_type` int(11) NOT NULL,
  `add_account` bigint(20) DEFAULT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `index_subject_id` (`subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_upgrade_lock`
--

CREATE TABLE `wmw_upgrade_lock` (
  `upgrade_task_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_code` int(11) NOT NULL,
  `is_complete` tinyint(4) NOT NULL COMMENT '1进行中 2结束',
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `add_account` bigint(20) NOT NULL,
  `upgrade_year` smallint(6) NOT NULL,
  PRIMARY KEY (`upgrade_task_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3320 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_user_scode`
--

CREATE TABLE `wmw_user_scode` (
  `client_account` bigint(20) NOT NULL,
  `client_email` varchar(60) NOT NULL,
  `security_code` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  PRIMARY KEY (`client_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_wms_account`
--

CREATE TABLE `wmw_wms_account` (
  `wms_account` bigint(20) NOT NULL,
  `wms_password` varchar(35) NOT NULL,
  `wms_name` varchar(60) NOT NULL,
  `wms_email` varchar(60) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY (`wms_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_wo`
--

CREATE TABLE `wmw_wo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `accounda` int(11) NOT NULL,
  `accoundb` int(11) NOT NULL,
  `note` varchar(3000) CHARACTER SET utf8 DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `addtime` int(10) NOT NULL,
  `state` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8342 ;

-- --------------------------------------------------------

--
-- 表的结构 `wmw_wo_c`
--

CREATE TABLE `wmw_wo_c` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `accounda` int(11) NOT NULL,
  `accoundb` int(11) NOT NULL,
  `note` varchar(3000) CHARACTER SET utf8 DEFAULT NULL,
  `addtime` int(10) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3092 ;
