This library, currently written in PHP, was created as a reverse-engineering effort of the deviantART shadow generator. You can add shadows around images using JavaScript and/or CSS on page-load, rather than by a pre-processing method such as Photoshop.

Images are cached on-disk to bypass the generation script on subsequent accesses.

There are a few "versions" available:
  * "Full" version, allowing you to produce shadows in different colours.
  * "Basic" version, which just does black / alpha.
  * Obfuscated basic version (bit of a prank).

I have also provided sample JavaScript (for use with jQuery) and CSS code for auto-shadow addition to images surrounded by SPAN elements with a class of "shadow".

Requirements:
  * PHP 5 or greater.
  * GD extension enabled.
  * The directory containing sh.php must we writable by the web server.
