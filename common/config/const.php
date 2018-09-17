<?php
define('COMMON', dirname(__DIR__));
define('FRONTEND', dirname(dirname(__DIR__)) . '/frontend');
define('BACKEND', dirname(dirname(__DIR__)) . '/backend');
define('CONSOLE', dirname(dirname(__DIR__)) . '/console');
define('API_BACKEND', dirname(dirname(__DIR__)) . '/api_backend');
define('SYSADMIN', dirname(dirname(__DIR__)) . '/sysadmin');
define('SUPERADMIN', dirname(dirname(__DIR__)) . '/superadmin');
define('STAFF', dirname(dirname(__DIR__)) . '/staff');
define('API_STAFF', dirname(dirname(__DIR__)) . '/api_staff');
define('AGENT', dirname(dirname(__DIR__)) . '/agent');

define('STATIC_FRONTEND', '/static/frontend');
define('STATIC_BACKEND', '/static/backend');
define('STATIC_SYSADMIN', '/static/sysadmin');
define('STATIC_SUPERADMIN', '/static/superadmin');
define('STATIC_STAFF', '/static/staff');
define('STATIC_AGENT', '/static/agent');
define('STATIC_COMMON', '/static/common');

define('ROBOT_SOUND_PATH','/var');      //录音文件路径

define('ASR_PATH','/upload/');      //智能语音通话记录,录音文件路径
// define('BACKEND1', dirname(dirname(__DIR__)) . '/frontend/web/static/backend');