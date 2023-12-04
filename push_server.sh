#!/bin/bash

sudo rm -rf /var/www/covoiturage_du_campus/*
sudo cp -r src/* /var/www/covoiturage_du_campus/
sudo mkdir /var/www/covoiturage_du_campus/sql/
sudo cp sql/* /var/www/covoiturage_du_campus/sql/

echo "Server updated"