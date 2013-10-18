#/bin/sh


php app/console doctrine:mapping:convert xml ./src/Peds/EntitiesBundle/Resources/config/doctrine/metadata/orm --from-database --force
php app/console doctrine:mapping:import PedsEntitiesBundle yml
php app/console doctrine:generate:entities PedsEntitiesBundle
