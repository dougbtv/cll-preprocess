<?php

$pp = new prepiler($argv);

class prepiler {

	var $outfile = "/tmp/compile.cll";

	function prepiler($argv) {

		$file = file_get_contents($argv[1]);

		$file = $this->processDefines($file);

		$result = "";
		$lines = preg_split("/\n/", $file);
		foreach ($lines as $l) {

			$trimmer = trim($l);

			// Strip blanks
			if (!strlen($trimmer)) {
				continue;
			}

			// Ignore comments.
			if (preg_match("/^\/\//", $trimmer)) {
				continue;
			}

			// Stop at multi-equal
			if (preg_match("/^\=\=/", $trimmer)) {
				break;
			}
			$result .= $l."\n";
		}

		file_put_contents($this->outfile, $result);

	}

	function processDefines($file) {

		$defines = array();

		$result = "";
		$lines = preg_split("/\n/", $file);
		$linenumber = 0;
		foreach ($lines as $l) {
			$linenumber++;
			$trimmer = trim($l);

			// Collect each define
			if (preg_match("/^\#define/", $trimmer)) {
				// Great, it's a define. Now let's make sure it has all it's parts.
				if (preg_match("/^\#define\s+[^\s]+\s[^\s]+$/",$trimmer)) {
					// Great that has all it's parts.
					$variable_name = preg_replace("/^\#define\s+([^\s]+)\s+[^\s]+$/", "$1", $trimmer);
					$variable_value = preg_replace("/^\#define\s+[^\s]+\s+([^\s]+)$/", "$1", $trimmer);
					
					if (!preg_match("/^[A-Z_]+$/", $variable_name)) {
						echo "WARNING: Your define of '".$variable_name."' isn't all uppercase or underscores. Which is OK, but, I don't recommend it.\n";
					}

					$defines[$variable_name] = $variable_value;

				} else {
					echo "In line number ".$linenumber." with the define statement of '".$trimmer."' I can't parse it properly. Sorry\n";
					die();
				}
			} else {
				$result .= $l."\n";
			}
		}

		// Now, go and replace the result.
		foreach ($defines as $def => $val) {
			$result = preg_replace("/".$def."/", $val, $result);
		}

		// print_r($defines);

		// echo $result."\n\n";

		return $result;

	}

}

?>