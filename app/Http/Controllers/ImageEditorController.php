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
        if (isset($request['payload-type'])) {
            if ($request['payload-type'] == 'simple') {
                $data = $request->validate([
                    's.w' => 'required|integer',
                    's.h' => 'required|integer',
                    's.b' => 'required|string',
                    'c' => 'required|array',
                    'c.*.p.x' => 'required|integer',
                    'c.*.p.y' => 'required|integer',
                    'c.*.s.w' => 'required|integer',
                    'c.*.s.h' => 'required|integer',
                    'c.*.cnt.t' => 'required|string',
                    'c.*.cnt.c' => 'required|string',
                    'c.*.clrs.b' => 'required|string',
                    'c.*.clrs.c' => 'required|string'
                ]);

                $components = [];

                foreach ($data['c'] as $key => $item) {
                    $components[] = [
                        'position' => $item['p'],
                        'size' => [
                            'width' => $item['s']['w'],
                            'height' => $item['s']['h'],
                        ],
                        'collors' => [
                            'background' => $item['clrs']['b'],
                            'background' => $item['clrs']['b'],
                        ],
                        'content' => [
                            'type' => $item['cnt']['t'],
                            'content' => $item['cnt']['c'],
                        ]
                    ];
                }

                $data = [
                    'sample' => [
                        'width' => $request->s->w,
                        'height' => $request->s->h,
                    ],
                    'components' => $components
                ];
            }
        } else {
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
        }

        try {
            if (isset($request->driver)) {
                if ($request->driver == "gd") {
                    return (new ImageWizardController)->buildImageGd($data);
                } else if ($request->driver == "image-wizard") {
                    return (new ImageWizardController)->buildImageIW($data);
                } else if ($request->driver == "b64") {
                    return (new ImageWizardController)->buildImageB64($data);
                }
            }

            return (new ImageWizardController)->buildImageGd($data);
        } catch (\Throwable $th) {
            if ($request->driver == "image-wizard") {
                return (new ImageWizardController)->buildImageIW($data);
            } else if ($request->driver == "b64") {
                return (new ImageWizardController)->buildImageB64($data);
            }

            return (new ImageWizardController)->buildImageIW($data);
        }
    }

    public function CreateImage(Request $request)
    {
        $request->validate([
            'payload' => 'required'
        ]);

        $payload = base64_decode($request->payload);
        $body = json_decode($payload, true);
        $body['driver'] = $request->driver ?? 'image-wizard';

        $requestBody = (new Request())->merge($body);
        return $this->editImage($requestBody);
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

            $now = date("Y_m_d_H:i:s");

            $enviromet  = ($client->get("https://img.shields.io/badge/enviroment-Laravel-red.png"))->getBody()->getContents();
            $deploy     = ($client->get("https://img.shields.io/badge/deployment-Vercel-black.png"))->getBody()->getContents();
            $version    = ($client->get("https://img.shields.io/badge/version-0.0.3-green.png"))->getBody()->getContents();
            $dateTime   = ($client->get("https://img.shields.io/badge/[Data|Hora]-[{$now}]-orange.png"))->getBody()->getContents();


            $backgroundResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/create?_token={$token}", [
                'json' => [
                    "width" => 720,
                    "height" => 300,
                    "color" => "#9b28ed"
                ]
            ]);

            $background = $backgroundResponse->getBody()->getContents();
            $logo = File::get(public_path("icon.png"));

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($background),
                    "overlayImageBuffer" => base64_encode($logo),
                    "x" => 300,
                    "y" => 50
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($enviromet),
                    "x" => 150,
                    "y" => 200
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($deploy),
                    "x" => 300,
                    "y" => 200
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($version),
                    "x" => 450,
                    "y" => 200
                ]
            ]);

            $imageResponse = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
                'json' => [
                    "baseImageBuffer" => base64_encode($imageResponse->getBody()->getContents()),
                    "overlayImageBuffer" => base64_encode($dateTime),
                    "x" => 250,
                    "y" => 250
                ]
            ]);

            return Response::make($imageResponse->getBody()->getContents(), 200, ['Content-Type' => 'image/png']);
        }
    }
}
