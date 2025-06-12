<?php

namespace App\Http\Controllers;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class QRCodeController extends Controller
{
    public function generate($entity)
    {
        $urls = [
            'acs' => route('acs.index'),
            'acn2s' => route('acn2s.index'),
            'ars' => route('ars.index'),
        ];
        //$url = $urls[$entity] ?? url('/');
        $url = "https://google.com.br";
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 6,
        ]);
        $qrcode = (new QRCode($options))->render($url);

        return response($qrcode, 200)->header('Content-Type', 'image/png');
    }
}
