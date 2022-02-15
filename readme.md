# Blog com laravel 8 e vue 3

## Sobre
Aplicação feita para testar o sanctum, uma das bibliotecas de segurança do laravel.
#

para testar, siga o processo:


### Clone o respositório
    `$ git clone https://github.com/josedoce/blog_laravel.git`
### Atenda aos requisitos
    
1- php 8

2- composer

3- mysql

4- node

#### Estrutura de pastas:
```terminal
    /app - frontend vue
    /blog - backend laravel
    /readme.md
```
#

### Instale dependencias de app e blog:

    
* dependencias do app:

    `$ cd ./app ` e `$ yarn install ` ou ` $ npm install`



* dependencias do blog:

    `$ cd ./blog` e `$ composer install`

---
### iniciando aplicação

* app
    
    - inicie o frontend com o seguinte comando `$ yarn serve`
        
* blog
    
    - crie um banco de dados com o nome `blog` e popule o banco de dados com `$ php artisan migrate:refresh --seed`.

    - inicie o servidor na porta 8000 com o seguinte comando: `$ php artisan serve`  