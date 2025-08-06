# Xtock - Controle de Produtos

Projeto desenvolvido como teste técnico, para a vaga de *Programador PHP Jr. na Zamix*.

## Rodar o projeto

**Ambiente necessário**
   - Sua máquina deve possuir `PHP >= 7.2`, conforme recomenda a própria [documentação do Laravel](https://laravel.com/docs/5.6/installation): 
   - No seu SGBD, execute os arquivos SQL.
  
     OU
  
   ```
      php artisan migrate
   ```
   E finalmente:
   
   ```
      php artisan serve
   ```
## Exceção
   - No meu caso, como não possuo PHP >= 7.2, utilizei de Docker:
   - Na raíz do projeto execute:
  
     ```
      docker compose up --build -d
     ```
   - Depois, entre no container do app com `docker exec -it nome-do-container bash`
   - Execute as migrations com `php artisan migrate`, ou então estabeleça conexão com o banco no seu SGBD e execute o arquivo `sql/database.sql`
   
## Funcionalidades desenvolvidas

- `Autenticação Web`
- `CRUD de usuários`
- `CRUD de produtos`
- `CRUD de requisições`
- `Entrada de produtos no estoque`
- `Retirada de produtos do estoque`
- `Registro de movimentações de estoque`

## Tecnologias

- `PHP:7.2`
- `Laravel 5.6`
- `MySQL / SQL`
- `CSS + Bootstrap`

## Diagramas

- Modelo Entidade Relacionamento (MER)
  
   ![MER](diagrams/MER/Zamix%20Teste%20-%20Modelo%20ER.png)

- Diagrama de Classes UML
  
   ![UML](diagrams/UML/Zamix%20Teste%20-%20Diagrama%20de%20Classes%20-%20Página%201.png)


## Observações

- Os scripts para o banco de dados e relatórios de entrada e saída estão no arquivo `/sql`
