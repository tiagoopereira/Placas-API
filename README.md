# API-Placas
### Execução
####  Makefile:
    - make run
#### Rodando separadamente
    - composer install (Para instalar as dependências e gerar o arquivo de autoload).

    > Utilizando Docker:
        - docker-compose up -d
        - docker exec -it php php bin/console doctrine:migrations:migrate 
  
    > Utilizando somente PHP:
        - Necessário PHP:8.*
        - php -S 0.0.0.0:80 -t public/
        - php bin/console doctrine:migrations:migrate 
