<?php

spl_autoload_register(function ($class) {
    if ('Mobile_Detect' === $class) {
        require_once __DIR__ . '/mobiledetect/Mobile_Detect.php';
    }

    if ('TitanFramework' === $class) {
        require_once __DIR__ . '/titan-fonts/titan-fonts.php';
        require_once __DIR__ . '/titan-framework/titan-framework.php';
        require_once __DIR__ . '/titan-extensions/titan-extensions.php';
    }

    if (strpos($class, 'Primal\Color') !== false) {
        $in = array('\\', 'Primal/Color');
        $out = array('/', 'color');
        $file = __DIR__ . '/' . str_replace($in, $out, $class) . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }
});

require_once __DIR__ . '/YaLinqo/Linq.php';