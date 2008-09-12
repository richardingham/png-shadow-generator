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
 * - bg: hex code of background colour, or "alpha"
 * - fg: hex code of foreground colour
 *
 * Saves the image to dir:
 *  ./bg-fg/a-b/w/h/sh.png
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

$bg = (string) (isset($_GET['bg']) ? $_GET['bg'] : 'alpha');
$fg = (string) (isset($_GET['fg']) ? $_GET['fg'] : '000000');

// Too large things slow or crash the server!
if ($w > 1000 || $h > 1000 || $a > 99 || $b > 1) exit;

// Derived
$im_width  = (int) ($w + $a);
$im_height = (int) ($h + $a);

//
// STEPS
//
$num_steps_right = $num_steps_bottom = (int) (($a / 2) + (min($a, (float) $h) / 4));
$num_steps_left = $num_steps_top = $num_steps_right;

//
// CREATE IMAGE
//
if ($bg == 'alpha') {
	$im = imagecreate($im_width, $im_height);
	
	$background = imagecolorallocatealpha($im, 255, 255, 255, 127);
	
	imagealphablending($im, false);
	
} else {
	$im = imagecreatetruecolor($im_width, $im_height);
	
	$bg_r = hexdec(substr($bg, 0, 2));
	$bg_g = hexdec(substr($bg, 2, 2));
	$bg_b = hexdec(substr($bg, 4, 2));
	$background = imagecolorallocatealpha($im, $bg_r, $bg_g, $bg_b, 0);
	
	imagefill($im, 0, 0, $background);

	imagealphablending($im, true);	
}

//
// COLOURS
//
$c_r = hexdec(substr($fg, 0, 2));
$c_g = hexdec(substr($fg, 2, 2));
$c_b = hexdec(substr($fg, 4, 2));

$centre_alpha = 127 - ((int) ($b * 127));
$centre = imagecolorallocatealpha($im, $c_r, $c_g, $c_b, $centre_alpha);

$colours = array();
$colours[$centre_alpha] = $centre;

imagefilledrectangle($im, $num_steps_left, $num_steps_top, $im_width - $num_steps_right, $im_height - $num_steps_bottom, $centre);

//
// FUNCTIONS
// 
function sides ($b, $n, $f) {
	global $colours, $c_r, $c_g, $c_b, $im;
	
	$sin = sin($n * $f * M_PI_2);
	$alpha = $b * $sin * $sin * $sin;
	
	$i_alpha = 127 - (int) ($alpha * 127);
	
	if (!isset($colours[$i_alpha])) {
		$colours[$i_alpha] = imagecolorallocatealpha($im, $c_r, $c_g, $c_b, $i_alpha);
	}
	
	return $colours[$i_alpha];
}

function corners ($b, $n_x, $f_x, $n_y, $f_y) {
	global $colours, $c_r, $c_g, $c_b, $im;
	
	$sin = sin($n_x * $f_x * M_PI_2) * sin($n_y * $f_y * M_PI_2);
	$alpha = $b * $sin * $sin * $sin;
	
	$i_alpha = 127 - (int) ($alpha * 127);
	
	if (!isset($colours[$i_alpha])) {
		$colours[$i_alpha] = imagecolorallocatealpha($im, $c_r, $c_g, $c_b, $i_alpha);
	}
	
	return $colours[$i_alpha];
}

//
// TOP
//
for ($n = 0, $f = 1 / (float) $num_steps_top; $n < $num_steps_top; ++$n) {
	$cur_color = sides($b, $n, $f);
	
	$y = $n;
	for ($x = $num_steps_left, $max = $im_width - $num_steps_right; $x < $max; ++$x) {
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// BOTTOM
//
for ($n = 0, $f = 1 / (float) $num_steps_bottom; $n < $num_steps_bottom; ++$n) {
	$cur_color = sides($b, $n, $f);
	
	$y = $im_height - $n;
	for ($x = $num_steps_left, $max = $im_width - $num_steps_right; $x < $max; ++$x) {
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// LEFT
//
for ($n = 0, $f = 1 / (float) $num_steps_left; $n < $num_steps_left; ++$n) {
	$cur_color = sides($b, $n, $f);
	
	$x = $n;
	for ($y = $num_steps_top, $max = $im_height - $num_steps_bottom; $y < $max; ++$y) {
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// RIGHT
//
for ($n = 0, $f = 1 / (float) $num_steps_right; $n < $num_steps_right; ++$n) {
	$cur_color = sides($b, $n, $f);
	
	$x = $im_width - $n;
	for ($y = $num_steps_top, $max = $im_height - $num_steps_bottom; $y < $max; ++$y) {
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// TOP LEFT
//
for ($n_x = 0, $f_x = 1 / (float) $num_steps_left; $n_x < $num_steps_left; ++$n_x) {
	for ($n_y = 0, $f_y = 1 / (float) $num_steps_top; $n_y < $num_steps_top; ++$n_y) {
		$cur_color = corners ($b, $n_x, $f_x, $n_y, $f_y);
		
		$x = $n_x;
		$y = $n_y;
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// TOP RIGHT
//
for ($n_x = 0, $f_x = 1 / (float) $num_steps_right; $n_x <= $num_steps_right; ++$n_x) {
	for ($n_y = 0, $f_y = 1 / (float) $num_steps_top; $n_y < $num_steps_top; ++$n_y) {
		$cur_color = corners ($b, $n_x, $f_x, $n_y, $f_y);
		
		$x = $im_width - $n_x;
		$y = $n_y;
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// BOTTOM LEFT
//
for ($n_x = 0, $f_x = 1 / (float) $num_steps_left; $n_x < $num_steps_left; ++$n_x) {
	for ($n_y = 0, $f_y = 1 / (float) $num_steps_bottom; $n_y <= $num_steps_bottom; ++$n_y) {
		$cur_color = corners ($b, $n_x, $f_x, $n_y, $f_y);
		
		$x = $n_x;
		$y = $im_height - $n_y;
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

//
// BOTTOM RIGHT
//
for ($n_x = 0, $f_x = 1 / (float) $num_steps_right; $n_x <= $num_steps_right; ++$n_x) {
	for ($n_y = 0, $f_y = 1 / (float) $num_steps_bottom; $n_y <= $num_steps_bottom; ++$n_y) {
		$cur_color = corners ($b, $n_x, $f_x, $n_y, $f_y);
		
		$x = $im_width - $n_x;
		$y = $im_height - $n_y;
		imagesetpixel($im, $x, $y, $cur_color);
	}
}

// output
$dir = dirname(__FILE__) . "/$bg-$fg/$a-$b/$w/$h";

if (!file_exists($dir)) {
	mkdir($dir, 0777, true);
}

imagepng($im);
imagepng($im, $dir . '/sh.png');

imagedestroy($im);

