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

        if(isset($request->driver)){
            if($request->driver == "gd"){
                return (new ImageWizardController)->buildImageGd($data);
            } else if($request->driver == "image-wizard"){
                return (new ImageWizardController)->buildImageIW($data);
            }
        }

        return (new ImageWizardController)->buildImageGd($data);
        
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
