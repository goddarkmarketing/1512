<?php
$src = __DIR__ . '/../assets/logo/logo-new-source.jpg';
$im = imagecreatefromjpeg($src);
if (!$im) {
    fwrite(STDERR, "fail jpeg\n");
    exit(1);
}
imagealphablending($im, true);
imagesavealpha($im, true);
imagepng($im, __DIR__ . '/../assets/logo/logo.png', 6);

$w = imagesx($im);
$h = imagesy($im);
$max = 320;
$nw = $max;
$nh = (int) round($h * ($max / $w));
$hero = imagecreatetruecolor($nw, $nh);
imagealphablending($hero, false);
imagesavealpha($hero, true);
$transparent = imagecolorallocatealpha($hero, 0, 0, 0, 127);
imagefilledrectangle($hero, 0, 0, $nw, $nh, $transparent);
imagealphablending($hero, true);
imagecopyresampled($hero, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
imagepng($hero, __DIR__ . '/../assets/logo/logo-hero.png', 6);
imagedestroy($im);
imagedestroy($hero);
echo "ok\n";
