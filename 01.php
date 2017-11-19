<?php
$input = 'Welcome to RegExr v2.1 by gskinner.com, proudly hosted by Media Temple!

Edit the Expression & Text to see matches. Roll over matches or the expression for details. Undo mistakes with ctrl-z. Save Favorites & Share expressions with friends or the Community. Explore your results with Tools. A full Reference & Help is available in the Library, or watch the video Tutorial.
dn10
Sample text for testing: дн 5
abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ
0123456789 _+-.,!@#$%^&*();\/|<>"\'
12345 -98.7 3.141 .6180 9,000 +42
555.123.4567	+1-(800)-555-2468';
$dn = preg_match_all('/(dn|дн)(\s)?([0-9]+)/i',$input,$matches);

var_export($matches);