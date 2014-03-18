#!/bin/bash
echo $1
php -q prepiler.php
./runtests.py
printf "\n\n\n"
