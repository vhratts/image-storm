<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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

        $width = $data['sample']['width'];
        $height = $data['sample']['height'];
        $backgroundColor = $this->hexToRgb($data['sample']['background']);
        $image = imagecreatetruecolor($width, $height);
        $bgColor = imagecolorallocate($image, $backgroundColor['r'], $backgroundColor['g'], $backgroundColor['b']);
        imagefill($image, 0, 0, $bgColor);

        // Ordena os componentes com base no parâmetro 'overlay' (menor para maior)
        // usort($data['components'], function ($a, $b) {
        //     return $a['overlay'] <=> $b['overlay'];
        // });

        foreach ($data['components'] as $component) {
            $this->addComponentToCanvas($image, $component);
        }

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return Response::make($imageData, 200, ['Content-Type' => 'image/png']);
    }

    private function addComponentToCanvas($image, $component)
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

    private function addTextToImage($image, $text, $position, $colorHex, $size)
    {
        $color = $this->hexToRgb($colorHex);
        $textColor = imagecolorallocate($image, $color['r'], $color['g'], $color['b']);
        $fontPath = public_path('fonts/Nunito_Sans/index.ttf');
        $fontSize = $size['width'];

        imagettftext($image, $fontSize, 0, $position['x'], $position['y'] + $fontSize, $textColor, $fontPath, $text);
    }

    private function addBase64ImageToCanvas($image, $base64String, $position, $size)
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

    private function addUrlImageToCanvas($image, $url, $position, $size)
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

    private function hexToRgb($hex)
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

    public function MimeTest()
    {
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
    }
}
