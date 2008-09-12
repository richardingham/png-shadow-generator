<?php
/**
 * PNG Shadow Generator
 * (c) Richard Ingham, Dec 17 2007
 * <richard@richardingham.net>
 *
 * URL Parameters:
 * - w:  width of image to surround.
 * - h:  height of image to surround.
 * - a:  spread of shadow (equivalent to 8 x deviantArt equivalent).
 * - b:  transparency of main shadow.
 *
 * Saves the image to dir:
 *  ./a-b/w/h/sh.png
 *
 * License: LGPL v2.
 */
 
ini_set('error_reporting', 0);
header('Content-Type: image/png');
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 30000000) . " GMT");

// Parameters
$w = (int) (isset($_GET['w'])    ? $_GET['w'] : 150);
$h = (int) (isset($_GET['h'])    ? $_GET['h'] : 150);
$a = (float) (isset($_GET['a'])  ? $_GET['a'] : 8); // == $dA * 3
$b = (float) (isset($_GET['b'])  ? $_GET['b'] : 0.35);

// Too large things slow or crash the server!
if ($w > 1000 || $h > 1000 || $a > 99 || $b > 1) exit;

// Derived
$im_width  = (int) ($w + $a);
$im_height = (int) ($h + $a);

//
// STEPS
//
$num_steps = (int) (($a / 2) + (min($a, (float) $h) / 4));

//
// CREATE IMAGE
//
$im = imagecreate($im_width, $im_height);
imagealphablending($im, false);

$background = imagecolorallocatealpha($im, 255, 255, 255, 127);
	
$centre_alpha = 127 - ((int) ($b * 127));
$centre = imagecolorallocatealpha($im, 0, 0, 0, $centre_alpha);

$colours = array();
$colours[$centre_alpha] = $centre;

imagefilledrectangle($im, $num_steps, $num_steps, $im_width - $num_steps, $im_height - $num_steps, $centre);

//
// FUNCTIONS
// 
function c ($b, $s) {
	global $colours, $im;
	
	$alpha = $b * $s * $s * $s;
	
	$i_alpha = 127 - (int) ($alpha * 127);
	
	if (!isset($colours[$i_alpha])) {
		$colours[$i_alpha] = imagecolorallocatealpha($im, 0, 0, 0, $i_alpha);
	}
	
	return $colours[$i_alpha];
}

//
// TOP
//
for ($n = 0, $f = 1 / (float) $num_steps; $n < $num_steps; ++$n) {
	$c = c($b, sin($n * $f * M_PI_2));
	
	for ($x = $num_steps, $max = $im_width - $num_steps; $x < $max; ++$x) {
		// Top
		imagesetpixel($im, $x, $n, $c);
		
		// Bottom
		imagesetpixel($im, $x, $im_height - $n, $c);
	}
	
	for ($y = $num_steps, $max = $im_height - $num_steps; $y < $max; ++$y) {
		// Left
		imagesetpixel($im, $n, $y, $c);
		
		// Right
		imagesetpixel($im, $im_width - $n, $y, $c);
	}
}


//
// TOP LEFT
//
for ($n_x = 0, $f_x = 1 / (float) $num_steps; $n_x <= $num_steps; ++$n_x) {
	for ($n_y = 0, $f_y = 1 / (float) $num_steps; $n_y <= $num_steps; ++$n_y) {
		$c = c($b, sin($n_x * $f_x * M_PI_2) * sin($n_y * $f_y * M_PI_2));
		
		// Top Left
		imagesetpixel($im, $n_x, $n_y, $c);
		
		// Top Right
		imagesetpixel($im, $im_width - $n_x, $n_y, $c);
		
		// Bottom Left
		imagesetpixel($im, $n_x, $im_height - $n_y, $c);
		
		// Bottom Right
		imagesetpixel($im, $im_width - $n_x, $im_height - $n_y, $c);
	}
}


// output
$dir = dirname(__FILE__) . "/$a-$b/$w/$h";

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

imagepng($im);
imagepng($im, $dir . '/sh.png');

imagedestroy($im);

