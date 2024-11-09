<p align="center">
<a href="#" target="_blank" style="display: flex; justify-content: center;">
<img src="https://image-storm.vercel.app/api-service"  alt="logo" style="width: 700px;">
</a>
</p>

<p align="center">
<a href="#" style="font-size: 30px;">🌪 ImageStorm</a>
</p>

**Image Storm** é uma API de edição de imagens desenvolvida em Laravel 11, que permite a criação e manipulação de imagens de forma programática. A API recebe um payload JSON contendo parâmetros como dimensões, cor de fundo e componentes para compor a imagem (textos, imagens em base64 ou URLs). A saída é uma imagem PNG gerada com as especificações fornecidas.

## Instalação

1. **Clone o repositório:**

    ```bash
    git clone https://github.com/vhratts/image-storm.git
    cd image-storm
    ```

2. **Instale as dependências:**

    ```bash
    composer install
    ```

3. **Configuração do arquivo `.env`:**

    Copie o arquivo de exemplo `.env.example` para criar um novo arquivo `.env`:

    ```bash
    cp .env.example .env
    ```

    Em seguida, configure as variáveis de ambiente, como `APP_KEY`, `DB_CONNECTION`, `DB_DATABASE`, etc.

4. **Gere a chave da aplicação:**

    ```bash
    php artisan key:generate
    ```

5. **Configure as permissões (se necessário):**

    Dependendo do seu ambiente, você pode precisar ajustar permissões para os diretórios de armazenamento e cache para que o Laravel possa gravar neles:

    ```bash
    chmod -R 775 storage bootstrap/cache
    ```

6. **Configuração do servidor local:**

    Para rodar a aplicação em ambiente local, use o servidor embutido do Laravel:

    ```bash
    php artisan serve
    ```

    A aplicação estará acessível em `http://localhost:8000`.

## Utilização da API

### Endpoints

**GET** `/api-service` => DEMO

**GET** `/api-service/image-creator` => Production

**POST** `/api-service/image-editor` => Production

### Parâmetros para requisição POST

A requisição deve ser enviada no corpo com o tipo `application/json` e conter os seguintes parâmetros:

-   `sample` (object): Configurações principais da imagem.
    -   `width` (int): Largura da imagem em pixels.
    -   `height` (int): Altura da imagem em pixels.
    -   `background` (string): Cor de fundo em hexadecimal.
-   `components` (array): Lista de componentes para adicionar à imagem.
    -   `overlay` (int): Define a ordem de sobreposição (camadas).
    -   `position` (object): Define a posição do componente.
        -   `x` (int): Posição horizontal (em pixels).
        -   `y` (int): Posição vertical (em pixels).
    -   `size` (object): Tamanho do componente.
        -   `width` (int): Largura do componente em pixels.
        -   `height` (int): Altura do componente em pixels.
    -   `collors` (object): Definição de cores para o componente.
        -   `background` (string|null): Cor de fundo em hexadecimal.
        -   `content` (string): Cor do conteúdo (texto).
    -   `content` (object): Conteúdo do componente.
        -   `type` (string): Tipo de conteúdo (`text` ou `image/png`).
        -   `content` (string): Texto a ser exibido (para `text`) ou imagem em Base64/URL (para `image/png`).

### Exemplo de Payload

```json
{
    "sample": {
        "width": 300,
        "height": 720,
        "background": "#ffffff"
    },
    "components": [
        {
            "overlay": 0,
            "position": {
                "x": 0,
                "y": 0
            },
            "size": {
                "width": 300,
                "height": 100
            },
            "collors": {
                "content": "#292929"
            },
            "content": {
                "type": "text",
                "content": "Texto no componente de imagem"
            }
        },
        {
            "overlay": 1,
            "position": {
                "x": 300,
                "y": 0
            },
            "size": {
                "width": 300,
                "height": 100
            },
            "content": {
                "type": "image/png",
                "content": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
            }
        },
        {
            "overlay": 2,
            "position": {
                "x": 600,
                "y": 0
            },
            "size": {
                "width": 300,
                "height": 100
            },
            "content": {
                "type": "image/png",
                "content": "https://example.com/image.png"
            }
        }
    ]
}
```

## Resposta

A API retornará uma imagem PNG no corpo da resposta com o `Content-Type: image/png`.

### Exemplo de Uso

Para consumir a API, um exemplo em cURL é mostrado abaixo:

```bash
curl -X POST "http://localhost:8000/api-service/image-editor" \
-H "Content-Type: application/json" \
-d '{
    "sample": {
        "width": 300,
        "height": 720,
        "background": "#ffffff"
    },
    "components": [
        {
            "overlay": 0,
            "position": {
                "x": 0,
                "y": 0
            },
            "size": {
                "width": 300,
                "height": 100
            },
            "collors": {
                "content": "#292929"
            },
            "content": {
                "type": "text",
                "content": "Texto no componente de imagem"
            }
        },
        {
            "overlay": 1,
            "position": {
                "x": 300,
                "y": 0
            },
            "size": {
                "width": 300,
                "height": 100
            },
            "content": {
                "type": "image/png",
                "content": "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
            }
        }
    ]
}'
```

### Parametros para requisição GET

No criador de imagens você pode requisitar a imagem passando o `payload` na query como um base64. Ex:

```php
<?php

<?php

$array = [
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

```

## Observações

-   A ordem de sobreposição dos componentes depende do valor de `overlay`, em que números mais altos indicam camadas superiores.
-   Certifique-se de fornecer URLs válidos ou strings de Base64 corretamente formatadas para que as imagens sejam renderizadas com sucesso.
