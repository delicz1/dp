<?php
// app/config/assetic.php
$applicationPath = __DIR__ . '/../../';
$scripts = (new \Proj\Base\Object\JsCss\JsBuilder())->setApplicationPath($applicationPath)->render();
$inputsJs = [];
foreach ($scripts as $script) {
    $script = preg_replace('/^.*\/src\/Nil\/Resources\/public\/(.*)$/', 'bundles/nil/$1', $script);
    $inputsJs[] = $script;
}
$styles = (new \Proj\Base\Object\JsCss\CssBuilder())->setApplicationPath($applicationPath)->render();
$inputsCss = [];
foreach ($styles as $style) {
    $style = preg_replace('/^.*\/src\/Nil\/Resources\/public\/(.*)$/', 'bundles/nil/$1', $style);
    $inputsCss[] = $style;
}
$container->loadFromExtension('assetic', [
    'assets' => [
        'base_js' => [
            'inputs' => $inputsJs,
        ],
        'base_css' => [
            'inputs' => $inputsCss,
        ],
    ],
    'filters' => [
        'uglifyjs2' => [
            'bin' => '/usr/bin/uglifyjs'
        ],
        'cssrewrite' => null
    ],
    'debug' => "%kernel.debug%",
    'bundles' => ['ProjBaseBundle', 'JMoseCommandSchedulerBundle'],
]);