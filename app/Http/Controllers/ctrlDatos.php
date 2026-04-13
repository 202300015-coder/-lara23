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

    public function ApiComedyHosted()
    {
        $response = Http::acceptJson()
            ->timeout(20)
            ->get('https://api.sampleapis.com/movies/comedy');

        if (!$response->successful()) {
            return response()->json([
                'message' => 'No se pudo obtener la fuente externa.',
            ], 502);
        }

        $data = $response->json();

        if (!is_array($data)) {
            $decoded = json_decode($response->body(), true);
            $data = is_array($decoded) ? $decoded : [];
        }

        return response()->json($data);
    }

    public function AccesoDatosViewMio()
    {
        $defaultApiUrl = rtrim((string) env('APP_URL', 'http://127.0.0.1:8000'), '/') . '/api/comedy-hosted';
        $apiUrl = env('VIEW_MIO_API_URL', $defaultApiUrl);
        $mensaje = null;
        $fuenteMostrada = $apiUrl;

        $partes = parse_url($apiUrl);
        if (!empty($partes['scheme']) && !empty($partes['host'])) {
            $fuenteMostrada = $partes['scheme'].'://'.$partes['host'];
            if (!empty($partes['port'])) {
                $fuenteMostrada .= ':'.$partes['port'];
            }
        }

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

        return view('viewmio', compact('enlace', 'mensaje', 'apiUrl', 'fuenteMostrada'));
    }

}
