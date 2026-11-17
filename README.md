# Sistema de Ponto de Venda (PDV) — PHP e MySQL

Projeto acadêmico da matéria de Desenvolvimento de Sistemas / ADS. A ideia foi aproveitar a versão MSQL do PDV fornecido em aula e organizar melhor o código, incluindo a arquitetura MVC, alguns padrões de projeto e as telas solicitadas: cadastro de clientes, carrinho de vendas e relatórios.

O cadastro de produtos que já existia na versão original também foi mantido em uma estrutura organizada, porque ele é necessário para alimentar o carrinho.

## 1. Arquitetura MVC — 1 ponto

O projeto foi separado em três responsabilidades principais:

- **Model (`app/Models` e `app/Repositories`)**: os Models representam as regras e validações das entidades Cliente, Produto e Venda. Os Repositories fazem a comunicação com o MySQL e concentram os comandos SQL.
- **View (`app/Views`)**: contém as telas em PHP/HTML. Elas exibem os dados e os formulários, sem executar consultas SQL diretamente.
- **Controller (`app/Controllers`)**: recebe as ações do usuário, valida a requisição, chama Models, Repositories e Services e escolhe qual View será exibida.

O arquivo `public/index.php` é o ponto de entrada e direciona as requisições de acordo com o parâmetro `r`. A inicialização da aplicação e o carregamento das classes ficam em `bootstrap.php`.

### Exemplo do fluxo MVC

1. O usuário abre `/?r=clientes`.
2. `public/index.php` chama `ClienteController`.
3. O Controller solicita os clientes ao `ClienteRepository`.
4. O Repository consulta o MySQL e devolve os dados.
5. O Controller envia os dados para `app/Views/clientes/index.php`, que monta a tabela.

## 2. Design Patterns — 1 ponto

Foram utilizados padrões que fazem sentido para o tamanho deste projeto:

### Singleton — `config/database.php`

A classe `Database` mantém uma única instância de conexão PDO durante a requisição. Assim, os Controllers e Repositories utilizam a mesma conexão, sem criar uma nova conexão em cada operação.

### Repository — `app/Repositories`

As classes `ClienteRepository`, `ProdutoRepository` e `VendaRepository` isolam as consultas SQL e as operações de leitura, inclusão, alteração e exclusão. Isso evita espalhar SQL pelas telas e pelos Controllers.

### Service Layer — `app/Services/CheckoutService.php`

O `CheckoutService` concentra o processo de finalização de uma venda. Ele confere o estoque, grava a venda e seus itens e atualiza as quantidades usando uma transação do banco. Se alguma etapa falhar, a transação é desfeita para evitar uma venda incompleta.

## 3. Telas e operações — CRUDs — 1 ponto

### 3.1 Cadastro de clientes

- **Create:** cadastrar nome, CPF, e-mail, telefone e endereço.
- **Read:** listar e pesquisar clientes por nome, CPF ou e-mail.
- **Update:** editar os dados de um cliente.
- **Delete:** excluir um cliente. Se ele já estiver relacionado a uma venda, a venda permanece registrada sem o vínculo com o cadastro excluído.

### 3.2 Carrinho de vendas

- Pesquisar produtos pelo nome ou código.
- Adicionar produto e quantidade ao carrinho.
- Alterar a quantidade ou remover um item.
- Escolher um cliente cadastrado (opcional).
- Finalizar a venda, registrar os itens e baixar o estoque.
- Conferir o total antes de concluir.

O carrinho fica armazenado na sessão enquanto o usuário navega no sistema. O estoque é conferido novamente no fechamento da venda para evitar vender uma quantidade que não está disponível.

### 3.3 Relatórios de vendas

- Consultar as vendas registradas.
- Filtrar por data inicial e final.
- Abrir os detalhes de uma venda e conferir seus itens.
- Cancelar uma venda concluída. O cancelamento muda o status e devolve ao estoque as quantidades vendidas; a venda não é apagada do histórico.

### Cadastro de produtos (funcionalidade original organizada)

Também é possível cadastrar, listar, pesquisar, editar e excluir produtos. Os campos principais da versão de aula foram mantidos: código, nome, categoria, validade, quantidade, preço unitário, preço do pacote e nome da imagem.

## 4. POP — Procedimento Operacional Padrão de implantação — 1 ponto

### 4.1 Objetivo

Orientar a instalação e a execução local do sistema PDV em um computador com PHP e MySQL.

### 4.2 Requisitos

- PHP 8.1 ou superior.
- Extensão `pdo_mysql` habilitada no PHP.
- MySQL 8 ou compatível.
- XAMPP, WAMP ou servidor PHP embutido.
- Navegador atualizado.

### 4.3 Procedimento de instalação

1. Baixe o ZIP do projeto ou clone o repositório do GitHub.
2. Extraia a pasta `PDV_MVC_MySQL` em um local de sua preferência.
3. Inicie o serviço MySQL pelo XAMPP/WAMP ou pela ferramenta instalada.
4. Abra o phpMyAdmin ou outro cliente MySQL.
5. Importe o arquivo `database/schema.sql`. Ele cria o banco `pdv_faculdade` e as tabelas `produtos`, `clientes`, `vendas` e `venda_itens`.
6. Faça uma cópia do arquivo `.env.example` com o nome `.env`.
7. Edite o `.env` e informe os dados do MySQL local (`DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASS`). O arquivo `.env` é ignorado pelo Git e não deve ser publicado.
8. Se quiser manter os produtos que já estavam no banco usado em aula, exporte a tabela `produtos` do banco antigo e importe os registros para a tabela `produtos` do banco `pdv_faculdade`. Confira se os campos `cod`, `nome`, `categoria`, `validade`, `quantidade`, `preco_unitario`, `preco_pacote` e `imagem` estão mapeados corretamente. Faça backup antes de importar.
9. Abra um terminal na pasta principal do projeto e execute:

   ```bash
   php -S localhost:8000 -t public
   ```

10. Acesse `http://localhost:8000` no navegador.
11. Entre em **Produtos** e confira se existem produtos com estoque. Depois teste o cadastro de cliente, o carrinho, a finalização de uma venda e o relatório.

### 4.4 Procedimento de uso e conferência

- Cadastre um cliente de teste.
- Cadastre ou importe pelo menos um produto com quantidade maior que zero.
- Adicione o produto ao carrinho e finalize uma venda.
- Confira se a quantidade do produto diminuiu no estoque.
- Abra Relatórios e confira a venda e seus itens.
- Cancele a venda de teste e confirme se o estoque foi devolvido.
- Exclua o cliente de teste, se desejar.

### 4.5 Solução de problemas comuns

- **Erro de conexão:** confira se o MySQL está iniciado e se os dados no `.env` estão corretos.
- **Driver PDO ausente:** habilite a extensão `pdo_mysql` no PHP utilizado pelo terminal/servidor.
- **Produto não aparece no carrinho:** confira se foi cadastrado e se a quantidade em estoque é maior que zero.
- **Página não abre:** execute o comando do passo 9 a partir da pasta principal do projeto e acesse a porta 8000.

## 5. Estrutura de pastas

```text
PDV_MVC_MySQL/
├── app/
│   ├── Controllers/   # recebe as ações e coordena o fluxo
│   ├── Models/        # regras e validações das entidades
│   ├── Repositories/  # consultas SQL e persistência
│   ├── Services/      # regras de negócio, como fechar venda
│   └── Views/         # telas do sistema
├── config/            # conexão PDO com MySQL
├── database/          # script de criação das tabelas
├── public/            # ponto de entrada e arquivos públicos
├── storage/           # espaço para arquivos locais/logs
├── .env.example       # exemplo de configuração
├── .gitignore
├── bootstrap.php
└── README.md
```

## 6. Publicação no GitHub

Na página do GitHub, crie um repositório chamado `pdv-mvc-mysql`. Depois, abra o terminal dentro da pasta do projeto e execute os comandos abaixo, substituindo `SEU-USUARIO` pelo seu usuário do GitHub:

```bash
git init
git add .
git commit -m "Organiza PDV em MVC e adiciona módulos de vendas"
git branch -M main
git remote add origin https://github.com/SEU-USUARIO/pdv-mvc-mysql.git
git push -u origin main
```

Depois de publicar, confira se o `README.md`, `database/schema.sql` e as pastas `app`, `config` e `public` aparecem no repositório. Não envie o arquivo `.env` nem credenciais do banco.

## 7. Observações sobre a versão entregue

- As consultas ao banco utilizam prepared statements.
- A conexão é feita por PDO e as credenciais são configuradas localmente no `.env`.
- O fechamento e o cancelamento de venda utilizam transações.
- A aplicação foi verificada quanto à sintaxe dos arquivos PHP (`php -l`). Para testar as operações completas, é necessário configurar um MySQL local e executar o roteiro de conferência descrito no POP.
- Este é um projeto didático. Em uma aplicação de produção, ainda seria importante acrescentar autenticação, controle de permissões, proteção CSRF e testes automatizados.

---

**Trabalho acadêmico — Análise e Desenvolvimento de Sistemas (ADS)**  
**Tecnologias:** PHP, MySQL, PDO, HTML, CSS e Bootstrap.
