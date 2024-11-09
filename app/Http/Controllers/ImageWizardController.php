<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ImageWizardController extends Controller
{

    private $image = null;

    public function buildImageGd(array $data)
    {
        $width = $data['sample']['width'];
        $height = $data['sample']['height'];
        $backgroundColor = (new ApplicationController)->hexToRgb($data['sample']['background']);
        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, $backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
        imagefill($image, 0, 0, $bgColor);

        // Ordena os componentes com base no parâmetro 'overlay' (menor para maior)
        // usort($data['components'], function ($a, $b) {
        //     return $a['overlay'] <=> $b['overlay'];
        // });

        foreach ($data['components'] as $component) {
            (new ApplicationController)->addComponentToCanvas($image, $component);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return Response::make($imageData, 200, ['Content-Type' => 'image/png']);
    }

    public function buildImageIW(array $data)
    {
        $width = $data['sample']['width'];
        $height = $data['sample']['height'];
        $image = $this->createLayer($width, $height, $data['sample']['background']);

        foreach ($data['components'] as $component) {
            if(!isset($this->image)){
                $this->image = (new ApplicationController)->addComponentToLayer($image, $component);
            } else {
                $this->image = (new ApplicationController)->addComponentToLayer($this->image, $component);
            }
        }

        $responseImage = $this->image;
        $this->image = null;
        return Response::make($responseImage, 200, ['Content-Type' => 'image/png', 'Cache-Control' => 'max-age=3600; s-maxage=3599, stale-while-revalidate=3600']);
    }

    protected function createLayer(string $width, string $height, string $collor = "#ffff")
    {
        try {
            $client = new Client();
            $response = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/create", [
                'json' => [
                    "width" => $width,
                    "height" => $height,
                    "color" => $collor
                ],
                'headers' => [
                    'Accept' => 'image/png',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            }

            return null;
        } catch (\Throwable $th) {
            return throw new Exception("Falha ao Gerar Layout: " . $th->getMessage());
        }
    }
}
