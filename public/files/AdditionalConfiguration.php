<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}
/*
    !IMPORTANT!
    The file '$databaseCredentialsFile' is not versioned and must be created and changed!
    For security reasons please create the database accesses outside the document roots!

    Die Datei '$databaseCredentialsFile' wird nicht mit Versioniert und muss extra angelegt und geändert werden!
    aus Sicherheitsgründen bitte die Datenbank zugänge außerhalb des document roots anlegen!
*/


$databaseCredentialsFile = PATH_site . './../typo3_config/typo3_config.php';
if (file_exists($databaseCredentialsFile)) { require_once ($databaseCredentialsFile); }

// Production / Live:
$customChanges = [
    'BE' => [
        'compressionLevel' => '0',
        'lockSSL' => 0, // if https is on set this to 1
        'versionNumberInFilename' => 0,
    ],
    'FE' => [
        'compressionLevel' => '0',
        'noPHPscriptInclude' => '1',
        'pageNotFound_handling' => '404.html',
        'pageUnavailable_handling' => '503.html',
        'disableNoCacheParameter' => 1
    ],
    'EXT' => [
        'extConf' => [
        ]
    ],
    'GFX' => [
        'processor_interlace' => 'none' // TYPO3 > 9.2
    ],
    'SYS' => [
        'UTF8filesystem' => 1,
        'clearCacheSystem' => 1,
        'enableDeprecationLog' => 0,
        'phpTimeZone' => 'Europe/Berlin',
        'systemLocale' => 'de_DE.UTF-8',
        'ipAnonymization' => '2'
    ]
];

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive($GLOBALS['TYPO3_CONF_VARS'], (array)$customChanges);


// Developement:
if(\TYPO3\CMS\Core\Utility\GeneralUtility::getApplicationContext()->isDevelopment()) {

    $customDevelopmentChanges = [
        'BE' => [
            'compressionLevel' => '0',
            'lockSSL' => 0, // if https is on set this to 1
            'versionNumberInFilename' => 0,
        ],
        'FE' => [
            'compressionLevel' => '0',
            'debug' => 1,
            'noPHPscriptInclude' => 1,
            'disableNoCacheParameter' => 0
        ],
        'EXT' => [
            'extConf' => [
            ]
        ],
        'SYS' => [
            'displayErrors' => 1,
            'sqlDebug' => 1,
            'systemLog' => 'error_log',
            'systemLogLevel' => '2',
            'enableDeprecationLog' => 'file',
            // disable Caching: https://usetypo3.com/did-you-know.html
            'caching' => [
                'cacheConfigurations' => [
                    'cache_core' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'cache_hash' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'cache_pages' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'cache_pagesection' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'cache_phpcode' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'cache_runtime' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\TransientMemoryBackend::class
                    ],
                    'cache_rootline' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'cache_imagesizes' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'l10n' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'extbase_object' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'extbase_reflection' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ],
                    'extbase_datamapfactory_datamap' => [
                        'backend' => \TYPO3\CMS\Core\Cache\Backend\NullBackend::class
                    ]
                ]
            ]
        ]
    ];

    $GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive($GLOBALS['TYPO3_CONF_VARS'], (array)$customDevelopmentChanges);
}

$realurlConfig = PATH_site . './typo3conf/ext/YOURTHEME/Resources/Private/Extensions/realurl/realurl_theme_conf.php';
if (file_exists($realurlConfig)) {
    $customRealurl = array(
        'EXT' => array(
            'extConf' => array(
                'realurl' => serialize(array(
                    'configFile' => 'typo3conf/ext/YOURTHEME/Resources/Private/Extensions/realurl/realurl_theme_conf.php',
                    'enableAutoConf' => 1,
                    'enableDevLog' => 0,
                    'enableChashUrlDebug' => 0
                ))
            )
        ),
    );
    $GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive($GLOBALS['TYPO3_CONF_VARS'], (array)$customRealurl);
}
