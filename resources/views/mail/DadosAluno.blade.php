<?php ?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <title>Confirmação - I Olimpíadas Científicas do Sertão Produtivo</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: sans-serif;
      }

      .container {
        background-color: #f1f1f1;
        padding: 15px 20px;
        min-height: 100vh;
      }

      .header {
        color: #fff;
        padding: 20px;
        background: linear-gradient(45deg, rgba(255,102,0,1) 0%, rgba(153,0,204,1) 100%);
        -webkit-box-shadow: 0px 0px 5px 0px #a9a9a9; 
        box-shadow: 0px 0px 5px 0px #a9a9a9;
      }

      .header img {
        height: 3rem;
      }

      .header h1 {
        font-size: 24px;
      }

      .container,
      .body {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }

      .body {
        padding: 0 5px;
      }

      .body ul {
        padding-left: 2rem;
      }

      .body p {
        font-size: 18px;
        line-height: 1.5;
      }

      .destaque {
        font-weight: bold;
      }

      .dados-acesso {
        background-color: #c7c7c7;
        padding: 10px;
        border-radius: 10px;
      }

      .dados-acesso ul {
        list-style: none;
      }

      li:not(:last-child) {
        margin-bottom: 10px;
      }

      .chamada-acao {
        background-color: #007bff;
        color: #fff;
        padding: 20px;
      }

      .chamada-acao a {
        color: #fff;
        text-decoration: none;
      }

      .header,
      .contato,
      .assinatura,
      .chamada-acao {
        text-align: center;
      }

      .contato a {
        color: #007bff;
      }

      .strong-text {
        font-weight: bold;
      }

      @media screen and (min-width: 1024px) {
        .container {
          margin: 0 10%;
          padding: 20px 40px;
        }
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <img src="data:image/png;base64, {{ $logo }}" alt="logo" />
        <h1>I Olimpíadas Científicas do Sertão Produtivo</h1>
      </div>

      <div class="body">
        <p><strong>Parabéns!</strong> Você deu o primeiro passo para desvendar o mundo da ciência nas I Olimpíadas Científicas do Sertão Produtivo!</p>

        <p class="strong-text">Prepare-se para uma jornada de aprendizado e descobertas:</p>

        <ul>
          <li><strong>Explore:</strong> Mergulhe em um universo de desafios e atividades em diferentes áreas do conhecimento.</li>
          <li><strong>Conecte-se:</strong> Interaja com estudantes de toda a região, trocando ideias e experiências.</li>
          <li><strong>Desenvolva:</strong> Aprimore suas habilidades e construa um futuro promissor na ciência.</li>
        </ul>

        <p class="strong-text">Para acessar nossa página, anote seus dados de acesso:</p>

        <div class="dados-acesso">
          <ul>
            <li><strong>Usuário:</strong> {{ $dados['usuario'] }}</li>
            <li><strong>Senha:</strong> {{ $dados['senha'] }} </li>
            <li><strong>Código da escola:</strong> {{ $dados['codigo'] }} </li>
          </ul>
        </div>

        <p class="strong-text">Lembre-se:</p>

        <ul>
          <li>Guarde essas informações com segurança.</li>
          <li>Acesse o <a href="<?php echo $dados['linkPortal']?>">portal do evento</a> para conferir sua inscrição e explorar as regras da olimpíada.</li>
          <li><strong>Dúvidas?</strong> Entre em contato conosco pelo email 
          <a href="<?php echo $dados['linkEmailDuvida']?>">support@olimpiadasdosertaoprodutivo.com</a>.</li>
        </ul>

        <p class="destaque strong-text">Junte-se a nós nesta jornada inesquecível!</p>

        <p class="assinatura strong-text">Equipe I Olimpíadas Científicas do Sertão Produtivo</p>
      </div>
    </div>
  </body>
</html>