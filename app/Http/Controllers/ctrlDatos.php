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
        $hostUrl = request()->getSchemeAndHttpHost();
        $defaultApiUrl = rtrim($hostUrl, '/') . '/api/comedy-hosted';
        $apiUrl = env('VIEW_MIO_API_URL', $defaultApiUrl);
        $mensaje = null;
        $fuenteMostrada = 'https://lara23emi.onrender.com/';
        $data = [];

        try {
            $requestHost = request()->getHost();
            $apiHost = parse_url($apiUrl, PHP_URL_HOST);

            // Evita auto-llamadas al mismo host que pueden provocar timeout/500 en un solo worker.
            if (!empty($apiHost) && $apiHost === $requestHost) {
                $response = Http::acceptJson()
                    ->timeout(20)
                    ->get('https://api.sampleapis.com/movies/comedy');
            } else {
                $response = Http::acceptJson()
                    ->timeout(20)
                    ->get($apiUrl);
            }

            $data = $response->successful() ? $response->json() : [];

            if (!is_array($data)) {
                $decoded = json_decode($response->body(), true);
                $data = is_array($decoded) ? $decoded : [];
            }
        } catch (\Throwable $e) {
            $mensaje = 'Error al consultar la API configurada.';
            $data = [];
        }

        if (empty($data)) {
            $mensaje = $mensaje ?? ('No se pudieron obtener datos desde la API configurada: ' . $apiUrl);
        }

        $enlace = $data;

        return view('viewmio', compact('enlace', 'mensaje', 'apiUrl', 'fuenteMostrada'));
    }
    public function detalle($id)
    {
        $pelicula = null;

        try {
            $responseById = Http::acceptJson()
                ->timeout(20)
                ->get("https://api.sampleapis.com/movies/comedy/$id");

            $dataById = $responseById->successful() ? $responseById->json() : [];
            if (!is_array($dataById)) {
                $decodedById = json_decode($responseById->body(), true);
                $dataById = is_array($decodedById) ? $decodedById : [];
            }

            if (isset($dataById['id']) && (string) $dataById['id'] === (string) $id) {
                $pelicula = $dataById;
            }

            if (!$pelicula) {
                $responseList = Http::acceptJson()
                    ->timeout(20)
                    ->get('https://api.sampleapis.com/movies/comedy');

                $dataList = $responseList->successful() ? $responseList->json() : [];
                if (!is_array($dataList)) {
                    $decodedList = json_decode($responseList->body(), true);
                    $dataList = is_array($decodedList) ? $decodedList : [];
                }

                $pelicula = collect($dataList)->first(function ($item) use ($id) {
                    return is_array($item) && isset($item['id']) && (string) $item['id'] === (string) $id;
                });
            }
        } catch (\Throwable $e) {
            $pelicula = null;
        }

        if (!$pelicula) {
            return redirect('/viewmio')->with('mensaje', 'No se encontro la pelicula solicitada.');
        }

        return view('vistadetalles', compact('pelicula'));
    }

}
