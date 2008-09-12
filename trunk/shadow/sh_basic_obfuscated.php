<?php
/**
 * PNG Shadow Generator
 * (c) Richard Ingham, Dec 17 2007
 * <richard@richardingham.net>
 *
 * URL Parameters:
 * - w: width of image to surround.
 * - h: height of image to surround.
 * - a: spread of shadow (equivalent to 8 x deviantArt equivalent).
 * - b: transparency of main shadow.
 *
 * Saves the image to dir:
 *  ./a-b/w/h/sh.png
 *
 * License: LGPL v2.
 */
 
if (isset($_GET['w']) && $_GET['w'] > 1000) exit;
if (isset($_GET['h']) && $_GET['h'] > 1000) exit;
if (isset($_GET['a']) && $_GET['a'] > 99)   exit;
if (isset($_GET['b']) && $_GET['b'] > 1)    exit;

ini_set('error_reporting', 0);
header('Content-Type: image/png');
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 30000000) . " GMT");

$a=(float)(isset($_GET['a'])?$_GET['a']:(8));$b=(float)(isset($_GET['b'])?$_GET
['b']:.35);$k=chr(9*11);list($j,$B,$x,$I,$l,$T,$O,$o,$f,$X,$t,$Y,$W,$H)=explode
($k,'0c255cimagecdc127csinczcarray_mapc_GETcmincstrtrcureatebuolorallouatealph'
.'abfilledreutanglebsetpixelbpngbdestroycwch');list($o,$O,$Y,$I,$t,$_,$Q)=$o($I
,explode($O,$t($Y,'ub',"$k$O")));$W=(int)(((isset(${$f}[$W])?${$f}[$W]:(int)$B)
+$a));$H=(int)((isset(${$f}[$H])?${$f}[$H]:$B)+$a);$S=(int)(($a/$B[$j])+($X($a,
(float)($W-$a))/($B[$j]*$B[$j])));$O($i=$o($W,$H),$B,$B,$B,$l);$u=$l-((int)($b*
$l));$C[$u]=$O($i,$j,$j,$j,$u);$Y($i,$S,$S,$W-$S,$H-$S,$C[$u]);function c($_,$t
){global $C,$O,$i,$j,$l;$_=$l-((int)($t*$_*$t*$l*$t));return((isset($C[$_]))?$C
[$_]:$C[$_]=$O($i,$j,$j,$j,$_));}function d($_){global $x;return"$x$_";}for($n=
$j,$f=$l[$j]/(float)$S;$n<$S;++$n){${$k}=$k($b,$T($n*($f*M_PI_2)));for($P=$S,$M
=$W-$S;$P<$M;++$P){$I($i,$P,$n,$c);$P;$I($i,$P,$H-$n,$c);}for($p=$S,$M=$H-$S;$p
<$M;++$p){$I($i,$n,$p,$c);$I($i,$W-$n,$p,$c);}}for($n=$j,$f=$l[$j]/(float)$S;$n
<=$S;++$n){for($S,$N=$j,$F=$l[$j]/((float)$S);$N<=$S;++$N){$$k=$k($b,$T($n*($f*
M_PI_2))*$T($N*$F*M_PI_2));$I($i,$n,$N,$c);$I($i,$W-$n,$N,$c);$I($i,$n,$H-$N,$c
);$I($i,$W-$n,$H-$N,$c);}}$k=dirname(__FILE__)."/$a-$b/".($W-$a).'/'.($H-$a);if
(!file_exists($k))mkdir($k,0777,true);$t($i,$k.chr(47).'sh.png');$t($i);$_($i);
