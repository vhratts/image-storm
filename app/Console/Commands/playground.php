<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class playground extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:playground';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Playground de testes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payload = [
            "sample" => [
                "width" => 320,
                "height" => 720,
                "background" => "#cdd1cd"
            ],
            "components" => [
                [
                    "overlay" => 0,
                    "position" => [
                        "x" => 60,
                        "y" => 10
                    ],
                    "size" => [
                        "width" => 100,
                        "height" => 40
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Banco"
                    ]
                ],
                [
                    "overlay" => 1,
                    "position" => [
                        "x" => 20,
                        "y" => 10
                    ],
                    "size" => [
                        "width" => 40,
                        "height" => 40
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#ffff"
                    ],
                    "content" => [
                        "type" => "image/png",
                        "content" => "https://cdn-icons-png.flaticon.com/512/1052/1052854.png"
                    ]
                ],
                [
                    "overlay" => 2,
                    "position" => [
                        "x" => 20,
                        "y" => 60
                    ],
                    "size" => [
                        "width" => 200,
                        "height" => 30
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Comprovante de"
                    ]
                ],
                [
                    "overlay" => 3,
                    "position" => [
                        "x" => 20,
                        "y" => 90
                    ],
                    "size" => [
                        "width" => 150,
                        "height" => 30
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Transferência"
                    ]
                ],
                [
                    "overlay" => 4,
                    "position" => [
                        "x" => 22,
                        "y" => 125
                    ],
                    "size" => [
                        "width" => 150,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "10 NOV 2024 - 14:59"
                    ]
                ],
                [
                    "overlay" => 5,
                    "position" => [
                        "x" => 22,
                        "y" => 185
                    ],
                    "size" => [
                        "width" => 45,
                        "height" => 15
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Valor"
                    ]
                ],
                [
                    "overlay" => 6,
                    "position" => [
                        "x" => 210,
                        "y" => 185
                    ],
                    "size" => [
                        "width" => 80,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "R$ 8.920,90"
                    ]
                ],
                [
                    "overlay" => 7,
                    "position" => [
                        "x" => 25,
                        "y" => 210
                    ],
                    "size" => [
                        "width" => 140,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Tipo de transferência"
                    ]
                ],
                [
                    "overlay" => 8,
                    "position" => [
                        "x" => 260,
                        "y" => 215
                    ],
                    "size" => [
                        "width" => 30,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "PIX"
                    ]
                ],
                [
                    "overlay" => 9,
                    "position" => [
                        "x" => 20,
                        "y" => 255
                    ],
                    "size" => [
                        "width" => 80,
                        "height" => 30
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Origem"
                    ]
                ],
                [
                    "overlay" => 10,
                    "position" => [
                        "x" => 22,
                        "y" => 305
                    ],
                    "size" => [
                        "width" => 50,
                        "height" => 25
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Nome"
                    ]
                ],
                [
                    "overlay" => 11,
                    "position" => [
                        "x" => 180,
                        "y" => 305
                    ],
                    "size" => [
                        "width" => 110,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Fulano de sousa"
                    ]
                ],
                [
                    "overlay" => 12,
                    "position" => [
                        "x" => 20,
                        "y" => 350
                    ],
                    "size" => [
                        "width" => 40,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "CPF"
                    ]
                ],
                [
                    "overlay" => 13,
                    "position" => [
                        "x" => 180,
                        "y" => 350
                    ],
                    "size" => [
                        "width" => 110,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "123.456.789-00"
                    ]
                ],
                [
                    "overlay" => 14,
                    "position" => [
                        "x" => 20,
                        "y" => 380
                    ],
                    "size" => [
                        "width" => 85,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Instituição"
                    ]
                ],
                [
                    "overlay" => 15,
                    "position" => [
                        "x" => 205,
                        "y" => 380
                    ],
                    "size" => [
                        "width" => 85,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Nubank SA"
                    ]
                ],
                [
                    "overlay" => 16,
                    "position" => [
                        "x" => 20,
                        "y" => 420
                    ],
                    "size" => [
                        "width" => 80,
                        "height" => 30
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Destino"
                    ]
                ],
                [
                    "overlay" => 17,
                    "position" => [
                        "x" => 22,
                        "y" => 470
                    ],
                    "size" => [
                        "width" => 50,
                        "height" => 25
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Nome"
                    ]
                ],
                [
                    "overlay" => 18,
                    "position" => [
                        "x" => 180,
                        "y" => 470
                    ],
                    "size" => [
                        "width" => 110,
                        "height" => 20
                    ],
                    "collors" => [
                        "background" => "#616362",
                        "content" => "#616362"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Comércio LTDA"
                    ]
                ],
                [
                    "overlay" => 17,
                    "position" => [
                        "x" => 22,
                        "y" => 470
                    ],
                    "size" => [
                        "width" => 50,
                        "height" => 25
                    ],
                    "collors" => [
                        "background" => "#00000",
                        "content" => "#00000"
                    ],
                    "content" => [
                        "type" => "text",
                        "content" => "Nome"
                    ]
                ],
            ]
        ];

        $payloadBase64 = base64_encode(json_encode($payload));

        $image = file_get_contents("https://image-storm.vercel.app/api-service/image-creator?payload={$payloadBase64}");

        file_put_contents(__dir__ . "/image.png", $image);
    }
}
