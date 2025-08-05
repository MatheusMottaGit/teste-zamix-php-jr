# Xtock - Controle de Produtos

Projeto desenvolvido como teste técnico, para a vaga de *Programador PHP Jr. na Zamix*.

## Rodar o projeto

1. **Ambiente necessário**
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

## Observações

- Os scripts para o banco de dados e relatórios de entrada e saída estão no arquivo `/sql`
