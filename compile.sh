#!/bin/bash
echo $1
if [[ -e $1 ]]; then
	php -q cllPreProcessor.php $1
	cd compiler
	python docompile.py
	cd ../
else
	echo "The file $1 doesn't exist. Pass in your file.cll as the first argument."
fi
printf "\n\n\n"
