<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ctrlDatos extends Controller
{
    //
    public function AccesoDatosVista()
    {
        $pro = Product::all();

        return view('vistadatosblade')->with(compact('pro'));
    }

    public function AccesoDatosVistaLink()

    {
        $response = Http::get('https://api.sampleapis.com/movies/comedy');
        $enlace = $response->successful() ? $response->json() : [];

        return view('vistadatoslink')->with(compact('enlace'));

    }

    public function AccesoDatosApiMia()
    {
        $response = Http::get('https://holisss.mundoiti.com/');
        $enlace = $response->successful() ? $response->json() : [];

        return view('apimia')->with(compact('enlace'));
    }
public function AccesoDatosViewMio()
{
    $apiUrl = env('VIEW_MIO_API_URL', 'https://api.sampleapis.com/movies/comedy');
    $mensaje = null;
    $response = Http::acceptJson()
        ->timeout(20)
        ->get($apiUrl);

    $data = $response->successful() ? $response->json() : [];

    if (!is_array($data)) {
        $decoded = json_decode($response->body(), true);
        $data = is_array($decoded) ? $decoded : [];
    }

    if (empty($data)) {
        $mensaje = 'No se pudieron obtener datos desde la API configurada: ' . $apiUrl;
    }

    $enlace = $data;

    return view('viewmio', compact('enlace', 'mensaje', 'apiUrl'));
}

}
