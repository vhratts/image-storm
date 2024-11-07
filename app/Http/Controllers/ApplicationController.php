<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function ApiAssetController($any = '/', Request $request)
    {
        if(!Str::contains($any, '.')){
            return response()->json([
                "status" => false,
                "message" => "URI is not file"
            ], 404); 
        }

        $filePath = public_path($any);

        // Verifica se o arquivo existe
        if (File::exists($filePath)) {
            // Obtém o MIME type dinamicamente
            $mimeType = mime_content_type($filePath);

            // Retorna o arquivo com o tipo MIME correto
            return Response::file($filePath, [
                'Content-Type' => $mimeType
            ]);
        }

        return abort(404);
    }
}
