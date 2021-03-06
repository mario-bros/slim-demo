# slim-demo

## Description

A simple REST demo using Doctrine ORM.

## Requirements

 * php: ~7.0

## Installation

### Checkout the repository

```{.sh}
git clone https://github.com/dominikzogg/slim-demo.git
```

### Install vagrant

```{.sh}
cd slim-demo
git submodule update --init -- vagrant-php
git submodule update --remote -- vagrant-php
```

### Start vagrant

```{.sh}
cd vagrant-php
vagrant up
```

### Install vendors

```{.sh}
vagrant ssh -c "composer.phar install"
```

### Create MYSQL database

```{.sh}
vagrant ssh -c "echo 'CREATE DATABASE slim_demo;' | mysql"
```

### Initialize or update database schema

```{.sh}
vagrant ssh -c "bin/console doctrine:schema:update --force"
```

### Postman Config

[slim-demo.postman_collection][1]


[1]: slim-demo.postman_collection.json
