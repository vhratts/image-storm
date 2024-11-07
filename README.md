<p align="center">
<a href="#" target="_blank" style="display: flex; justify-content: center;">
<img src="public/app.png" width="400" alt="logo" style="width: 80px;">
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

### Endpoint

**POST** `/api/image-editor`

### Parâmetros

A requisição deve ser enviada no corpo com o tipo `application/json` e conter os seguintes parâmetros:

- `sample` (object): Configurações principais da imagem.
  - `width` (int): Largura da imagem em pixels.
  - `height` (int): Altura da imagem em pixels.
  - `background` (string): Cor de fundo em hexadecimal.
- `components` (array): Lista de componentes para adicionar à imagem.
  - `overlay` (int): Define a ordem de sobreposição (camadas).
  - `position` (object): Define a posição do componente.
    - `x` (int): Posição horizontal (em pixels).
    - `y` (int): Posição vertical (em pixels).
  - `size` (object): Tamanho do componente.
    - `width` (int): Largura do componente em pixels.
    - `height` (int): Altura do componente em pixels.
  - `collors` (object): Definição de cores para o componente.
    - `background` (string|null): Cor de fundo em hexadecimal.
    - `content` (string): Cor do conteúdo (texto).
  - `content` (object): Conteúdo do componente.
    - `type` (string): Tipo de conteúdo (`text` ou `image/png`).
    - `content` (string): Texto a ser exibido (para `text`) ou imagem em Base64/URL (para `image/png`).

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
curl -X POST "http://localhost:8000/api/image-editor" \
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

## Observações

- A ordem de sobreposição dos componentes depende do valor de `overlay`, em que números mais altos indicam camadas superiores.
- Certifique-se de fornecer URLs válidos ou strings de Base64 corretamente formatadas para que as imagens sejam renderizadas com sucesso.