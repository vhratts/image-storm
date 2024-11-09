<div class="app-card-header">
    <x-card class="app-card">
        <div class="app-card-title">
            <h4>🌪 Image-Storm</h4>
            <x-button primary label="🔃 Atualizar " />
        </div>
    </x-card>
    <x-card class="app-card">
        <div class="container">
            <h1>Image Storm API</h1>
            <p><strong>Image Storm</strong> é uma API de edição de imagens desenvolvida em Laravel 11, que permite a
                criação e manipulação de imagens de forma programática. A API recebe um payload JSON com parâmetros como
                dimensões, cor de fundo e componentes (textos, imagens em base64 ou URLs). A saída é uma imagem PNG
                gerada com as especificações fornecidas.</p>

            <h2>Instalação</h2>
            <p>Siga os passos abaixo para instalar a aplicação:</p>
            <ol>
                <li><strong>Clone o repositório:</strong></li>
                <pre><code>git clone https://github.com/vhratts/image-storm.git
    cd image-storm</code></pre>
                <li><strong>Instale as dependências:</strong></li>
                <pre><code>composer install</code></pre>
                <li><strong>Configuração do arquivo <code>.env</code>:</strong></li>
                <p>Copie o arquivo de exemplo:</p>
                <pre><code>cp .env.example .env</code></pre>
                <p>Configure as variáveis de ambiente, como <code>APP_KEY</code> e detalhes de conexão com o banco de
                    dados.</p>
                <li><strong>Gere a chave da aplicação:</strong></li>
                <pre><code>php artisan key:generate</code></pre>
                <li><strong>Configuração do servidor local:</strong></li>
                <pre><code>php artisan serve</code></pre>
            </ol>
            <p>A aplicação estará acessível em <code>http://localhost:8000</code>.</p>

            <h2>Utilização da API</h2>
            <h3>Endpoint</h3>
            <p><code>POST /api-service/image-editor</code></p>

            <h3>Parâmetros</h3>
            <p>A requisição deve ser enviada no corpo como <code>application/json</code> e conter os seguintes
                parâmetros:</p>
            <ul>
                <li><strong>sample</strong> (object): Configurações principais da imagem.
                    <ul>
                        <li><strong>width</strong> (int): Largura da imagem em pixels.</li>
                        <li><strong>height</strong> (int): Altura da imagem em pixels.</li>
                        <li><strong>background</strong> (string): Cor de fundo em hexadecimal.</li>
                    </ul>
                </li>
                <li><strong>components</strong> (array): Lista de componentes.
                    <ul>
                        <li><strong>overlay</strong> (int): Define a ordem de sobreposição (camadas).</li>
                        <li><strong>position</strong> (object): Posição do componente.
                            <ul>
                                <li><strong>x</strong> (int): Posição horizontal.</li>
                                <li><strong>y</strong> (int): Posição vertical.</li>
                            </ul>
                        </li>
                        <li><strong>size</strong> (object): Tamanho do componente.
                            <ul>
                                <li><strong>width</strong> (int): Largura em pixels.</li>
                                <li><strong>height</strong> (int): Altura em pixels.</li>
                            </ul>
                        </li>
                        <li><strong>collors</strong> (object): Definição de cores.
                            <ul>
                                <li><strong>background</strong> (string|null): Cor de fundo em hexadecimal.</li>
                                <li><strong>content</strong> (string): Cor do conteúdo (texto).</li>
                            </ul>
                        </li>
                        <li><strong>content</strong> (object): Conteúdo do componente.
                            <ul>
                                <li><strong>type</strong> (string): Tipo de conteúdo (<code>text</code> ou
                                    <code>image/png</code>).</li>
                                <li><strong>content</strong> (string): Texto a ser exibido ou imagem (em Base64 ou URL).
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <h3>Exemplo de Payload</h3>
            <pre><code>{
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
    }</code></pre>

            <h3>Exemplo de Uso com cURL</h3>
            <pre><code>curl -X POST "http://localhost:8000/api-service/image-editor" \
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
    }'</code></pre>

            <h3>Observações</h3>
            <p>A ordem de sobreposição dos componentes depende do valor de <code>overlay</code>, onde números mais altos
                indicam camadas superiores. Certifique-se de fornecer URLs válidos ou strings de Base64 corretamente
                formatadas para que as imagens sejam renderizadas com sucesso.</p>
        </div>
    </x-card>
</div>
