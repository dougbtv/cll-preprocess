<?php


	$pi = new postInspection($argv);

	class postInspection {

		var $output_pattern = "Output: ";
		var $pad_amount = 10;
		var $do_trace = false;
		var $trace_pattern = "";

		function postInspection($argv) {

			echo "\n\n";
			echo "------------------------------ -\n";
			echo "-- EVM3-ASM inspection ------ -\n";
			echo "---------------------------- -\n";

			$inspectfile = $argv[1];
			if (!file_exists($inspectfile)) {
				die("postInspection: Sorry, I don't know the file: ".$inspectfile);
			}

			if (isset($argv[2])) {
				if (strlen($argv[2])) {
					// That's our pattern for tracing.
					$this->do_trace = true;
					$this->trace_pattern = "/".$argv[2]."/";
				}
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
				$instruction = $b;
				if ($this->do_trace) {
					// echo $this->trace_pattern." | ".$instruction."\n\n";

					if (preg_match($this->trace_pattern, $instruction)) {
						// Ok, we're tracing that.
						// So, let's figure out how much to pad it.
						$pad = $this->pad_amount - strlen($instruction);
						for ($i = 0; $i < $pad; $i++) {
							$instruction .= " ";
						}
						$instruction .= " <-------";
					}
				}
				echo sprintf("%04d",$idx)."\t".$instruction."\n";
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