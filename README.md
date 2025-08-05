# Xtock - Controle de Produtos

Projeto desenvolvido como teste técnico, para a vaga de *Programador PHP Jr. na Zamix*.

## Rodar o projeto

1. **Ambiente normal**
   - Se sua máquina possui `PHP >= 7.2`, apenas execute os arquivos SQL, ou então execute `php artisan migrate` na raíz do projeto.

2. **Ambiente Docker**
   - Minha máquina apenas possui `PHP 8.2`, então não seria possível utilizar a versão `Laravel 5.6`.
   - Para isso, utilizei de um pequeno container apenas para executar a versão do PHP recomendada pela documentação.
  ```
      dcnwfbhjwfev
  ```
   
## Funcionalidades desenvolvidas

- `Autenticação Web`
- `CRUD de usuários`
- `CRUD de produtos`
- `CRUD de requisições`
- `Entrada de produtos no estoque`
- `Retirada de produtos do estoque`
- `Registro de movimentações de estoque`

## Tecnologias

- Laravel 5.6
- MySQL / SQL

## Observações

- Os scripts para o banco de dados e relatórios de entrada e saída estão no arquivo `/sql`
