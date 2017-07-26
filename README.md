php
===

A Symfony project

# Database Commands

## Generate Entity
$ php bin/console doctrine:generate:entity

## Generate Getters and Setters For Class
$ php bin/console doctrine:generate:entities AppBundle/Entity/User

## Generate Getters and Setters For all Classes in a namespace
$ php bin/console doctrine:generate:entities AppBundle/Entity

## Update DB Schema
$ php bin/console doctrine:schema:update --force

## Generate CRUD from Entity
$ php bin/console generate:doctrine:crud

## Generate Migration from Schema
$ php bin/console doctrine:migrations:diff

## Generate Empty Migration
$ php bin/console doctrine:migrations:generate

## Execute Migration
$ php bin/console doctrine:migrations:migrate

## Execute fixture load ( kills all data in db! )
$ php bin/console doctrine:fixtures:load
