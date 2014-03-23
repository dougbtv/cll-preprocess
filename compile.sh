#!/bin/bash
TEMP_FILE=/tmp/inspect.asm
echo $1
if [[ -e $1 ]]; then
	php -q cllPreProcessor.php $1
	eval "python docompile.py | tee $TEMP_FILE"
	# php -q postInspection.php $TEMP_FILE
else
	echo "The file $1 doesn't exist. Pass in your file.cll as the first argument."
fi
printf "\n\n\n"
