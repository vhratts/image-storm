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
}
