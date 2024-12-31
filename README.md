#### Zprovoznění aplikace


## Spuštění docker kontejnerů

ve složce CrmAsistent spusťte následující příkaz

``` CLI
docker-compose up -d
```


## Vytvoření databáze

``` CLI
docker exec crm_asistent_php bin/console doctrine:migrations:migrate
```


## Spuštění phpstan

``` CLI
docker exec crm_asistent_php vendor/bin/phpstan analyse src
```