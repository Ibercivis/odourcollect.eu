<?php 

$an_get = $_GET['an'];
$in = $_GET['in'];

$an = convertAN($an_get);

$x = intval($an) * 10 / 9;
$y = intval($in) * 10 / 9;

$aux = ( 1.5 * $x + $y ) / 2.5;
$result = intval($aux);

echo 'With Annoy: '.$an_get. '('.$an.') --> X ='.$x;
echo '<br>';
echo 'With Intensity: '.$in. ' --> Y ='.$y;
echo '<br>';
echo '<br>';
echo 'The final result is: '.min(7, $result).' intval of --> '.$aux;

function convertAN($an){

	switch ($an){
		case '4': return 1; break;
		case '3': return 2; break;
		case '2': return 3; break;
		case '1': return 4; break;
		case '0': return 5; break;
		case '-1': return 6; break;
		case '-2': return 7; break;
		case '-3': return 8; break;
		case '-4': return 9; break;
	}

	return false;
}

?>