<?php

set_time_limit(300);
date_default_timezone_set('Asia/Jakarta');

$font_list = [
    'Snake',
    'roddy',
    'rabiohead',
    'AndThisHappened',
    'AYearWithoutRain',
    'CoveredByYourGrace'
];

$config  = [
    'line'          => 425,
    'width'         => 120,

    'name_sample'   => 'sample-1.png',

    'line_range'    => [75, 78],
    'width_range'   => [-2, 4],

    'font'          => 6,
    'color_font'    => '#313332',
    'text_blur'     => 1,
 
    // horizontal, vertical, rotate
    'date_position' => [350, 320, -0.8]
];

$draw    = new ImagickDraw();
$sample  = new Imagick();
$compile = new Imagick();

$line   = $config['line'];
$width  = $config['width'];

$text   = file_get_contents('data.txt');

$name_sample = 'sample/' . $config['name_sample'];
$file_sample = file_get_contents($name_sample);
$sample->readImageBlob($file_sample);

$font   = __DIR__."/font/".$font_list[($config['font']-1)].".ttf";

$compile->newImage($sample->getImageWidth(), $sample->getImageHeight(), new ImagickPixel('transparent'));

$draw->setFont($font);
$draw->setFillColor($config['color_font']);
$draw->setTextKerning(3.95);

$draw->setFontSize(45);

$compile->annotateImage($draw,
    ($sample->getImageWidth() - $config['date_position'][0]),
    $config['date_position'][1], $config['date_position'][2],
    date('d-m-Y')
);

$draw->setFontSize(rand(40, 40.5));

$text_compile = explode(PHP_EOL, $text);
foreach ($text_compile as $key => $value) {
    $random_tilted = [-0.7, -0.8, -0.9];
    $compile->annotateImage($draw, $width, $line, $random_tilted[rand(0, 2)], $value);

    $line  += rand($config['line_range'][0], $config['line_range'][1]);
    $width += rand($config['width_range'][0], $config['width_range'][1]);
}

$compile->blurImage(5, $config['text_blur']);

$sample->compositeImage($compile, Imagick::COMPOSITE_DEFAULT,
    (($sample->getImageWidth() - $compile->getImageWidth())/2), 
	(($sample->getImageHeight() - $compile->getImageHeight())/2)
);

header("Content-Type: image/png");
echo $sample;
$sample->destroy();
