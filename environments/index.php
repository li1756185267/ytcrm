<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'agent/runtime',
            'agent/web/assets',
            'api_backend/runtime',
            'api_backend/web/assets',
            'sysadmin/runtime',
            'sysadmin/web/assets',
            'superadmin/runtime',
            'superadmin/web/assets',
            'staff/runtime',
            'staff/web/assets',
            'api_staff/runtime',
            'api_staff/web/assets',
            'batch/runtime',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'agent/config/main-local.php',
            'api_backend/config/main-local.php',
            'sysadmin/config/main-local.php',
            'superadmin/config/main-local.php',
            'staff/config/main-local.php',
            'api_staff/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'agent/runtime',
            'agent/web/assets',
            'api_backend/runtime',
            'api_backend/web/assets',
            'sysadmin/runtime',
            'sysadmin/web/assets',
            'superadmin/runtime',
            'superadmin/web/assets',
            'staff/runtime',
            'staff/web/assets',
            'api_staff/runtime',
            'api_staff/web/assets',
            'batch/runtime',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'agent/config/main-local.php',
            'api_backend/config/main-local.php',
            'sysadmin/config/main-local.php',
            'superadmin/config/main-local.php',
            'staff/config/main-local.php',
            'api_staff/config/main-local.php',
        ],
    ],
];
