#!/bin/bash

usage()
{
cat << EOF
usage: $0 inputfile.cll {options}

This script will compile CLL into EVM3-ASM code for Ethereum

OPTIONS:
   -h      
      Show this message
   
   -i      
      Enable inspection (shows assembled output, line-by-line)
   
   -t "regex"
      Used with -i. Trace lines in the assembled piece using "regex".

   -s 
      Skips "====" style lines, and compiles each 
      block between "====" as independant programs.

EOF
}

DO_INSPECT=0
DO_TRACE=0
TRACE_REGEX=
SKIP_MULTIEQUAL=
# This skips the first argument, so we can use it as the input file name (always required)
while getopts “hit:s” OPTION ${@:2}; 
do
     case $OPTION in
         h)
             usage
             exit 1
             ;;
         i)
             DO_INSPECT=1
             ;;
         t)
             DO_TRACE=1
             TRACE_REGEX=$OPTARG
             ;;
         s)
             SKIP_MULTIEQUAL=skipmultiequal
             ;;

         ?)
             usage
             exit
             ;;
     esac
done

TEMP_FILE=/tmp/inspect.asm
echo $1
if [[ -e $1 ]]; then
	php -q cllPreProcessor.php $1 $SKIP_MULTIEQUAL
	eval "python docompile.py | tee $TEMP_FILE"
	if [ $DO_INSPECT == 1 ]; then
		if [ $DO_TRACE == 1 ]; then
			php -q postInspection.php $TEMP_FILE "$TRACE_REGEX"
		else
			php -q postInspection.php $TEMP_FILE
		fi
	fi
else
	echo "The file $1 doesn't exist. Pass in your file.cll as the first argument."
fi
printf "\n\n\n"
