#!/bin/sh
php bin/console doctrine:generate:entities AppBundle
php bin/console doctrine:generate:entities AdminBundle
php bin/console doctrine:schema:update --force
