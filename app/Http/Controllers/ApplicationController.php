<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Symfony\Component\Uid\BinaryUtil;

class ApplicationController extends Controller
{
    public function ApiAssetController($any = '/', Request $request)
    {
        if (!Str::contains($any, '.')) {
            return response()->json([
                "status" => false,
                "message" => "URI is not file"
            ], 404);
        }

        $filePath = public_path($any);

        // Verifica se o arquivo existe
        if (File::exists($filePath)) {
            // Determina o MIME type do arquivo baseado em sua extensão
            $extension = File::extension($filePath);
            $mimeType = $this->getMimeType($extension);

            // Retorna o arquivo com o MIME type correto
            return Response::file($filePath, [
                'Content-Type' => $mimeType
            ]);
        }

        return abort(404);
    }

    protected function getMimeType($extension)
    {
        $mimeTypes = [
            // Textos e códigos
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'csv' => 'text/csv',
            'txt' => 'text/plain',

            // Imagens
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'heic' => 'image/heic',
            'heif' => 'image/heif',

            // Fontes
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',

            // Áudio
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'm4a' => 'audio/mp4',
            'aac' => 'audio/aac',
            'flac' => 'audio/flac',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',

            // Vídeos
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'wmv' => 'video/x-ms-wmv',
            'flv' => 'video/x-flv',
            'mkv' => 'video/x-matroska',
            'webm' => 'video/webm',
            'ogv' => 'video/ogg',

            // Documentos
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'rtf' => 'application/rtf',

            // Arquivos compactados
            'zip' => 'application/zip',
            'rar' => 'application/vnd.rar',
            '7z' => 'application/x-7z-compressed',
            'gz' => 'application/gzip',
            'tar' => 'application/x-tar',

            // Outros tipos de arquivos
            'apk' => 'application/vnd.android.package-archive',
            'exe' => 'application/x-msdownload',
            'bin' => 'application/octet-stream',
            'dmg' => 'application/x-apple-diskimage',
            'iso' => 'application/x-iso9660-image',
            'epub' => 'application/epub+zip',
            'swf' => 'application/x-shockwave-flash',

            // Microsoft Office antigos
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pot' => 'application/vnd.ms-powerpoint',
            'pps' => 'application/vnd.ms-powerpoint',
        ];


        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    public function addComponentToCanvas($image, $component)
    {
        $position = $component['position'];
        $size = $component['size'];
        $content = $component['content'];
        $collor = $component['collors']['content'] ?? "#000000";

        if ($content['type'] === 'text') {
            $this->addTextToImage($image, $content['content'], $position, $collor, $size);
        } elseif ($content['type'] === 'image/png' && str_starts_with($content['content'], 'data:image')) {
            $this->addBase64ImageToCanvas($image, $content['content'], $position, $size);
        } elseif ($content['type'] === 'image/png' && filter_var($content['content'], FILTER_VALIDATE_URL)) {
            $this->addUrlImageToCanvas($image, $content['content'], $position, $size);
        }
    }

    public function addTextToImage($image, $text, $position, $colorHex, $size)
    {
        $color = $this->hexToRgb($colorHex);
        $textColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        $fontPath = public_path('fonts/Nunito_Sans/index.ttf');
        $fontSize = $size['width'];

        imagettftext($image, $fontSize, 0, $position['x'], $position['y'] + $fontSize, $textColor, $fontPath, $text);
    }

    public function addBase64ImageToCanvas($image, $base64String, $position, $size)
    {
        // Decodificar Base64 e criar a imagem a partir da string
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        if (!$imageData) {
            return; // Caso a decodificação falhe
        }

        $overlay = imagecreatefromstring($imageData);
        if (!$overlay) {
            return; // Verificar se a criação da imagem falhou
        }

        // Redimensionar a imagem de sobreposição
        $resizedOverlay = imagescale($overlay, $size['width'], $size['height']);
        if ($resizedOverlay) {
            // Preservar a transparência para imagens PNG
            imagealphablending($image, true);
            imagesavealpha($image, true);
            imagecopy($image, $resizedOverlay, $position['x'], $position['y'], 0, 0, $size['width'], $size['height']);
            imagedestroy($resizedOverlay);
        }

        imagedestroy($overlay);
    }

    public function addUrlImageToCanvas($image, $url, $position, $size)
    {
        // Tentar criar a imagem a partir da URL
        $overlay = @imagecreatefrompng($url);
        if (!$overlay) {
            return; // Caso a criação da imagem falhe
        }

        // Redimensionar a imagem de sobreposição
        $resizedOverlay = imagescale($overlay, $size['width'], $size['height']);
        if ($resizedOverlay) {
            // Preservar a transparência para imagens PNG
            imagealphablending($image, true);
            imagesavealpha($image, true);
            imagecopy($image, $resizedOverlay, $position['x'], $position['y'], 0, 0, $size['width'], $size['height']);
            imagedestroy($resizedOverlay);
        }

        imagedestroy($overlay);
    }

    public function hexToRgb($hex)
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }

    public function addComponentToLayer($image, $component)
    {
        $position = $component['position'];
        $size = $component['size'];
        $content = $component['content'];
        $collor = $component['collors']['content'] ?? "#000000";

        if ($content['type'] === 'text') {
            return $this->addTextToImageComponent($image, $content['content'], $position, $collor, $size);
        } elseif ($content['type'] === 'image/png' && str_starts_with($content['content'], 'data:image')) {
            return $this->addBase64ImageToCanvasComponent($image, $content['content'], $position, $size);
        } elseif ($content['type'] === 'image/png' && filter_var($content['content'], FILTER_VALIDATE_URL)) {
            return $this->addUrlImageToCanvasComponent($image, $content['content'], $position, $size);
        }
    }

    public function addUrlImageToCanvasComponent($image, $url, $position, $size) {
        $client = new Client();

        $response = $client->get($url);

        if ($response->getStatusCode() === 200) {
            $imageContent = $response->getBody()->getContents();
            $imageEncoded = base64_encode($imageContent);

            return $this->addBase64ImageToCanvasComponent($image, $imageEncoded, $position, $size);

        }

        return null;
    }

    public function addBase64ImageToCanvasComponent($image, $base64String, $position, $size)
    {
        $image          = base64_encode($image);
        $base64String   = base64_encode($this->resizeImage($base64String, $size['width'], $size['height']));
        $token          = Str::random();

        $client = new Client();
        $response = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/overlay?_token={$token}", [
            'json' => [
                "baseImageBuffer" => $image,
                "overlayImageBuffer" => $base64String,
                "x" => $position['x'],
                "y" => $position['y'],
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return $response->getBody()->getContents();
        }

        return null;
    }

    public function addTextToImageComponent($image, $text, $position, $colorHex, $size)
    {
        
        $image = base64_encode($image);
        $token = Str::random();

        $fontSize = $size['width'];

        $client = new Client();
        $response = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/add-text?_token={$token}", [
            'json' => [
                "imageBuffer" =>  $image,
                "fontUrl" => 'https://github.com/googlefonts/NunitoSans/raw/refs/heads/main/fonts/ttf/NunitoSans-Regular.ttf',
                "text" => $text,
                "color" => $colorHex,
                "fontSize" => $size['width'],
                "x" => $position['x'],
                "y" => $position['y'] + $fontSize,
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            return $response->getBody()->getContents();
        }

        return null;
    }

    private function resizeImage(string $imageBase64, int $width, int $height) {
        $client = new Client();
        $response = $client->post(env("IW_PROVIDER", "https://image-wizard-eight.vercel.app") . "/api/image/resize", [
            'json' => [
                "imageBuffer" =>  $imageBase64,
                "width" => $width,
                "height" => $height
            ]
        ]);

        return $response->getBody()->getContents();
    }
}
