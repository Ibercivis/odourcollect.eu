<?php

namespace App\Services;

class OdorColor
{

    /**
     * @param int $annoy
     * @param int $intensity
     * @return string (color #hex)
     * @throws \Exception
     */
    public function getOdorColor( $annoy, $intensity )
    {

        $in = $intensity;
        $an = $this->normalizeAnnoy( $annoy );

        $x = intval( $an ) * 10 / 9;
        $y = intval( $in ) * 10 / 9;

        $aux = ( 1.5 * $x + $y ) / 2.5;
        $result = intval( $aux );

        $matrixNumber = min( 7, $result );

        if($matrixNumber == 0){ $matrixNumber = 1;}

        return $matrixNumber;

    }

    protected function normalizeAnnoy( $annoy )
    {

        switch ($annoy){
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

    protected function getColorFromMatrixNumber( $color )
    {

        switch ($color){
            case '1': return '#36e8ca'; break;
            case '2': return '#6ff38a'; break;
            case '3': return '#e6f9e5'; break;
            case '4': return '#ffff00'; break;
            case '5': return '#ff9933'; break;
            case '6': return '#fc4b04'; break;
            case '7': return '#ff0000'; break;
        }

        return false;
    }

}