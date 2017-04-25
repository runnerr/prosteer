<?php

define('APP_PATH', realpath(__DIR__.'/../../'));

return new \Phalcon\Config([
    'database' => [
        'adapter'       => 'Mysql',
        'host'          => 'localhost',
        'username'      => 'prosteer',
        'password'      => 'I6RJOhRhLuS4qAg47',
        'dbname'        => 'prosteer',
        'charset'       => 'utf8',
    ],
    'application' => [
        'controllersDir'=> APP_PATH . '/app/controllers/',
        'modelsDir'     => APP_PATH . '/app/models/',
        'tasksDir'      => APP_PATH . '/app/tasks/',
        'migrationsDir' => APP_PATH . '/app/migrations/',
        'viewsDir'      => APP_PATH . '/app/views/',
        'pluginsDir'    => APP_PATH . '/app/plugins/',
        'libraryDir'    => APP_PATH . '/app/library/',
        'cacheDir'      => APP_PATH . '/app/cache/',
        'baseUri'       => '/app/',
    ],
    'prosteer' => [
        'sitemapUrl'    => 'https://masteram.com.ua/sitemap.xml',
        'sitemapFile'   => APP_PATH . '/app/cache/prosteer_sitemap.xml',
        'priceUrls'     => APP_PATH . '/app/cache/prosteer_urls.txt',
        'priceList'     => APP_PATH . '/app/cache/prosteer_price.csv',
        'masteramCsv'   => 'http://138.68.74.79/masteram.csv',
    ],
    'gtest' => [
        'sitemapUrl'    => 'http://gtest.com.ua/index.php?route=feed/google_sitemap',
        'sitemapFile'   => APP_PATH . '/app/cache/gtest_sitemap.xml',
        'priceUrls'     => APP_PATH . '/app/cache/gtest_urls.txt',
        'priceList'     => APP_PATH . '/app/cache/gtest_price.csv',
        'htmlsDir'      => APP_PATH . '/app/cache/gtest/',
    ],
    'rcs' => [
        'sitemapUrl'    => 'https://www.rcscomponents.kiev.ua/sitemap.xml',
        'sitemapFile'   => APP_PATH . '/app/cache/rcs_sitemap%s.xml',
        'priceUrls'     => APP_PATH . '/app/cache/rcs_urls.txt',
        'priceList'     => APP_PATH . '/app/cache/rcs_price.csv',
        'htmlsDir'      => APP_PATH . '/app/cache/rcs/',
    ],
]);


