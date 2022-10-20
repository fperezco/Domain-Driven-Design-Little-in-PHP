
## About

DDDLite: Just a small project to play with Domain Driven Design (Lite) Concepts: Value Objects, Entities, Aggregates, Domain Services, Events.

"DDD-Lite is a means of picking and choosing a subset of the DDD tactical patterns, but without giving full attention to discovering, capturing, and enhancing the Ubiquitous Language. As well, this technique generally bypasses the use of Bounded Contexts and Context Mapping."

## Installation

### Environment

Create a file named `.env.local` with the following settings and change the `##XXX##` strings:

    APP_ENV=##SET_THE_ENVIRONMENT##    
    JWT_PASSPHRASE=##SET_THE_CERTIFICATE_PASSPHRASE##
    
    DATABASE_URL=mysql://##DB_USER##:##DB_PASSWORD##@##DB_HOST##/##DB_NAME##?serverVersion=5.7

### Set cors policy

Add to env.local file with the following line:

    CORS_ALLOW_ORIGIN='^https?://(localhost|127\.0\.0\.1)(:[0-9]+)?$|http://talentpool-fe.local'

### SSH Keys for JWT Authentication

SSH Keys for the JWT Authentication generation:

    > openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    > openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout

When generator asks for a passphrase use the JWT_PASSPHRASE set in the .env file of our environment.

### Launch dockers for development

Type `docker-compose up -d`

### Create database (if it does not exist) and execute migrations

From inside docker (docker-compose exec apache-php bash )

`php bin/console doctrine:database:create && php bin/console doctrine:migrations:migrate -n`
