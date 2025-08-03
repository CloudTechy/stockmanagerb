#!/usr/bin/env bash

# Install PostgreSQL PDO extension
apt-get update
apt-get install -y libpq-dev
docker-php-ext-install pdo_pgsql pgsql
