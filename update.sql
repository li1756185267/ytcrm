INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(404, 0, '任务统计', 342, 1, 1, 2, 'customer-task-count', '2018-05-18 10:17:26.000', NULL, 0, NULL, NULL, 1);

UPDATE crm_menu SET enabled=0 WHERE menu_name='预测任务';

INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(505, 0, '智能语音', 0, 9, 1, 4, 'http://localhost/IBOS-3.5/', '2017-12-28 08:31:43.000', 'fa fa-user-secret', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(506, 0, '我的任务', 505, 3, 1, 4, 'asr-robot-dashboard', '2017-12-28 08:33:42.000', NULL, 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(507, 0, '呼叫记录', 505, 2, 1, 4, 'asr-call-count', '2017-12-28 08:34:42.000', NULL, 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(508, 0, '资料库', 505, 1, 1, 4, 'asr-data-base', '2018-01-12 09:49:16.000', NULL, 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(509, 0, '我的客户', 0, 8, 1, 4, 'customer-search', '2018-01-12 09:49:16.000', 'fa fa-file-text-o', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(510, 0, '通话记录', 0, 7, 1, 4, 'call-records', '2018-01-12 09:49:16.000', 'fa fa-phone', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(511, 0, '预约提醒', 0, 6, 1, 4, 'appointment-remind', '2018-01-12 09:49:16.000', 'fa fa-history', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(512, 0, '服务记录', 0, 5, 1, 4, 'customer-servicerecords', '2018-01-12 09:49:16.000', 'fa fa-heart', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(513, 0, '手机短信', 0, 4, 1, 4, 'sms', '2018-01-12 09:49:16.000', 'fa fa-envelope-o', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(514, 0, '话务统计', 0, 3, 1, 4, 'calls-statistics', '2018-01-12 09:49:16.000', 'fa fa-pie-chart', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(515, 0, '统计报表', 0, 2, 1, 4, 'serve-statistics', '2018-01-12 09:49:16.000', 'fa fa-bar-chart-o', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(516, 0, '我的任务', 0, 9, 1, 4, 'customer-search', '2018-01-12 09:49:16.000', 'fa fa-bar-chart-o', 0, NULL, NULL, 1);

INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10707, 0, 59, 505, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10708, 0, 59, 506, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10709, 0, 59, 507, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10710, 0, 59, 508, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10711, 0, 59, 509, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10712, 0, 59, 510, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10713, 0, 59, 511, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10714, 0, 59, 512, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10715, 0, 59, 513, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10716, 0, 59, 514, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10717, 0, 59, 515, 1);
INSERT INTO crm_privilege
(id, `type`, role_id, menu_id, enabled)
VALUES(10718, 0, 59, 516, 1);

INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(NULL, 0, '语音设置', 343, 2, 1, 2, 'group-list', '2018-05-18 10:23:48.000', NULL, 0, NULL, NULL, 1);

# 短信微信发送需求记录表 add by alex 2018-07-12
DROP TABLE IF EXISTS `asrcall_sendwxsms_queue`;
CREATE TABLE `asrcall_sendwxsms_queue` (
  `unique_id` varchar(255) NOT NULL COMMENT '通话唯一标示',
  `caller_id` varchar(21) NOT NULL COMMENT '主叫号码',
  `callee_id` varchar(21) NOT NULL COMMENT '被叫号码',
  `wx_data` varchar(255) NOT NULL COMMENT '发送微信的数据',
  `sms_sign` int(2) NOT NULL DEFAULT '0' COMMENT '1：发送短信，0：不发',
  `wx_sign` int(2) NOT NULL DEFAULT '0' COMMENT '1：发送微信，0：不发',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`unique_id`),
  KEY `Callerid` (`caller_id`),
  KEY `Calleeid` (`callee_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

# 导航维护 add by alex 2018-07-16
DELETE FROM crm_menu
WHERE id=506;
DELETE FROM crm_menu
WHERE id=507;
DELETE FROM crm_menu
WHERE id=508;
DELETE FROM crm_menu
WHERE id=509;
DELETE FROM crm_menu
WHERE id=510;
DELETE FROM crm_menu
WHERE id=511;
DELETE FROM crm_menu
WHERE id=512;
DELETE FROM crm_menu
WHERE id=513;
DELETE FROM crm_menu
WHERE id=514;
DELETE FROM crm_menu
WHERE id=515;
DELETE FROM crm_menu
WHERE id=516;

INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(506, 0, '我的任务', 329, 1, 1, 4, 'asr-robot-dashboard', '2017-12-28 08:33:42.000', NULL, 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(507, 0, '呼叫记录', 329, 2, 1, 4, 'asr-call-count', '2017-12-28 08:34:42.000', NULL, 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(508, 0, '资料库', 329, 3, 1, 4, 'asr-data-base', '2018-01-12 09:49:16.000', NULL, 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(509, 0, '我的客户', 0, 2, 1, 4, 'customer-search', '2018-01-12 09:49:16.000', 'fa fa-file-text-o', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(510, 0, '通话记录', 0, 3, 1, 4, 'call-records', '2018-01-12 09:49:16.000', 'fa fa-phone', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(511, 0, '预约提醒', 0, 4, 1, 4, 'appointment-remind', '2018-01-12 09:49:16.000', 'fa fa-history', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(512, 0, '服务记录', 0, 5, 1, 4, 'customer-servicerecords', '2018-01-12 09:49:16.000', 'fa fa-heart', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(513, 0, '手机短信', 0, 6, 1, 4, 'sms', '2018-01-12 09:49:16.000', 'fa fa-envelope-o', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(514, 0, '话务统计', 0, 7, 1, 4, 'calls-statistics', '2018-01-12 09:49:16.000', 'fa fa-pie-chart', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(515, 0, '统计报表', 0, 8, 1, 4, 'serve-statistics', '2018-01-12 09:49:16.000', 'fa fa-bar-chart-o', 0, NULL, NULL, 1);
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(516, 0, '我的任务', 0, 1, 1, 4, 'customer-search', '2018-01-12 09:49:16.000', 'fa fa-tasks', 0, NULL, NULL, 1);

# menu update add by alex 2018-07-17
INSERT INTO crm_menu
(id, `type`, menu_name, parent_id, order_id, enabled, `identity`, url, create_time, icon, syscard, controller_name, action_name, is_display)
VALUES(NULL, 0, '呼叫状态', 329, 3, 1, 2, 'asr-call-status', '2017-12-28 08:34:42.000', NULL, 0, NULL, NULL, 1);

UPDATE crm_menu
SET `type`=0, menu_name='话费账单', parent_id=329, order_id=5, enabled=0, `identity`=2, url='asr-bill', create_time='2017-12-28 08:37:40.000', icon=NULL, syscard=0, controller_name=NULL, action_name=NULL, is_display=1
WHERE id=333;
UPDATE crm_menu
SET `type`=0, menu_name='语音资源库', parent_id=329, order_id=4, enabled=1, `identity`=2, url='asr-voice-repository', create_time='2017-12-28 08:35:58.000', icon=NULL, syscard=0, controller_name=NULL, action_name=NULL, is_display=1
WHERE id=332;
