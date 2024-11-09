<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ImageEditorController extends Controller
{
    public function editImage(Request $request)
    {
        $data = $request->validate([
            'sample.width' => 'required|integer',
            'sample.height' => 'required|integer',
            'sample.background' => 'required|string',
            'components' => 'required|array',
            'components.*.position.x' => 'required|integer',
            'components.*.position.y' => 'required|integer',
            'components.*.size.width' => 'required|integer',
            'components.*.size.height' => 'required|integer',
            'components.*.content.type' => 'required|string',
            'components.*.content.content' => 'required|string',
            'components.*.collors.background' => 'required|string',
            'components.*.collors.content' => 'required|string'
        ]);

        if (isset($request->driver)) {
            if ($request->driver == "gd") {
                return (new ImageWizardController)->buildImageGd($data);
            } else if ($request->driver == "image-wizard") {
                return (new ImageWizardController)->buildImageIW($data);
            }
        }

        return (new ImageWizardController)->buildImageGd($data);
    }



    public function MimeTest()
    {
        try {
            // Cria a imagem com GD
            $handle = ImageCreateTrueColor(130, 50) or die("Não foi possível criar a imagem");

            // Define as cores
            $bgColor = ImageColorAllocate($handle, 255, 0, 0); // Fundo vermelho
            $txtColor = ImageColorAllocate($handle, 255, 255, 255); // Texto branco
            $lineColor = ImageColorAllocate($handle, 0, 0, 0); // Linha preta

            // Preenche o fundo e desenha uma linha e o texto
            ImageFill($handle, 0, 0, $bgColor);
            ImageLine($handle, 65, 0, 130, 50, $lineColor);
            ImageString($handle, 5, 5, 18, "->Image-Storm", $txtColor);

            // Armazena a imagem em um buffer de saída
            ob_start();
            ImagePng($handle);
            $imageData = ob_get_clean();
            ImageDestroy($handle);
            // Retorna a imagem com o cabeçalho correto
            return response($imageData, 200)->header('Content-Type', 'image/png');
        } catch (\Throwable $th) {

            $client = new Client();
            $token = Str::random();

            $enviromet  = ($client->get("https://img.shields.io/badge/enviroment-Laravel-red"))->getBody()->getContents();
            $deploy     = ($client->get("https://img.shields.io/badge/deployment-Vercel-black"))->getBody()->getContents();
            $version     = ($client->get("https://img.shields.io/badge/Version-0.0.3-green"))->getBody()->getContents();


            $backgroundResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/create?_token={$token}", [
                'json' => [
                    "width" => 720,
                    "height" => 300,
                    "color" => "#9b28ed"
                ]
            ]);

            $background = $backgroundResponse->getBody()->getContents();
            $logo = File::get(public_path("app.png"));

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($background),
                    "overlayImageBuffer" => base64_encode($logo),
                    "x" => 340,
                    "y" => 100
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($enviromet),
                    "x" => 240,
                    "y" => 150
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($deploy),
                    "x" => 240,
                    "y" => 200
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($version),
                    "x" => 240,
                    "y" => 250
                ]
            ]);

            return Response::make($imageResponse->getBody()->getContents(), 200, ['Content-Type' => 'image/png']);
        }
    }
}
