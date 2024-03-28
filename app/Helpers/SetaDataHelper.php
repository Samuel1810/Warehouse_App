<?php

namespace App\Helpers;

class SetaDataHelper
{
    public static function getSetaData($prateleira, $numeroArmazem)
    {
        $numeroArmazem;
        
        if($numeroArmazem === 1){
            switch ($prateleira) {
                case 1:
                    return [
                        'top' => '-286px',
                        'left' => '150px',
                        'armazem' => 'armazem1.jpg'
                    ];
                case 2:
                    return [
                        'top' => '-220px',
                        'left' => '150px',
                        'armazem' => 'armazem1.jpg'
                    ];
                case 3:
                    return [
                        'top' => '-194px',
                        'left' => '150px',
                        'armazem' => 'armazem1.jpg'
                    ];
            }
        } else if($numeroArmazem === 2){
            switch ($prateleira) {
                case 1:
                    return [
                        'top' => '-280px',
                        'left' => '328px',
                        'armazem' => 'armazem2.jpg'
                    ];
                case 2:
                    return [
                        'top' => '-245px',
                        'left' => '328px',
                        'armazem' => 'armazem2.jpg'
                    ];
                case 3:
                    return [
                        'top' => '-124px',
                        'left' => '328px',
                        'armazem' => 'armazem2.jpg'
                    ];
            }
        }
    }
}