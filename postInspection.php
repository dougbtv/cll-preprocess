<?php


	$pi = new postInspection($argv);

	class postInspection {

		var $output_pattern = "Output: ";

		function postInspection($argv) {

			echo "\n\n";
			echo "------------------------------ -\n";
			echo "-- EVM-ASM inspection ------- -\n";
			echo "---------------------------- -\n";

			$inspectfile = $argv[1];
			if (!file_exists($inspectfile)) {
				die("postInspection: Sorry, I don't know the file: ".$inspectfile);
			}
			
			// Grep the part of the file that we want.
			$asm = $this->syscall("grep -P '^".$this->output_pattern."' ".$inspectfile);
			$asm = trim($asm);

			// Remove the piece in front of it.
			$asm = preg_replace("/^".$this->output_pattern."/", "", $asm);

			// Now split it into pieces by whitespace.
			$bytes = preg_split("/\s+/", $asm);

			$idx = 0;
			foreach ($bytes as $b) {
				$idx++;
				echo sprintf("%04d",$idx)."\t".$b."\n";
			}

			echo "\n\n";


		}

		function syscall($command){
		    $result = "";
		    if ($proc = popen("($command)2>&1","r")){
		         while (!feof($proc)) $result .= fgets($proc, 1000);
		         pclose($proc);
		         return $result; 
		        }
		}

	}

?>