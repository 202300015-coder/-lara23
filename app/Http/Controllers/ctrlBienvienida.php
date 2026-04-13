<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ctrlBienvienida extends Controller
{
    public function Bienvenidos(){
        return view('welcome');
    }

    public function realizarSuma(){
        // Realizar la suma
        $numero1 = 15;
        $numero2 = 90;
        $resultado = $numero1 + $numero2;

        // Retornar la vista con el resultado
        return view('holisss', ['resultado' => $resultado]);
    }
    public function suma(){
        return 3+3;
    }
    public function Datossuma(){
        return('si funciona');
    }
       public function suma2($n1, $n2){
        return "el resultado es " . ($n1 + $n2);
    }
        public function suma3($n1, $n2){
        // Realizar la suma de los parámetros recibidos
        $resultado = $n1 + $n2;
        $datos = $resultado;

        // Retornar la vista welcome con el resultado
        return view('welcome', compact('datos'));
    }
}
