<?php

$cllpp = new cllPreProcessor($argv);

class cllPreProcessor {

	var $outfile = "/tmp/compile.cll";

	var $skipmultiequal = false;

	function cllPreProcessor($argv) {

		$file = file_get_contents($argv[1]);

		foreach ($argv as $key => $parameter) {
			if ($key > 1) {
				switch ($parameter) {
					case "skipmultiequal":
						$this->skipmultiequal = true;
						break;
					default:
				}
			}
		}

		$file = $this->processDefines($file);

		$result = "";
		$lines = preg_split("/\n/", $file);
		$finding_block = false;
		foreach ($lines as $l) {

			$trimmer = trim($l);

			// Strip blanks
			if (!strlen($trimmer)) {
				continue;
			}

			// Process comments.
			if ($finding_block) {
				// We're finding a block. So, let's see if we found the end.
				if (preg_match("|\*/|", $trimmer)) {
					// That's the end.
					// Keep the rest.
					$l = preg_replace("|^.*?\*/(.*)$|", "$1", $l);
					// And we're not looking for a block anymore.
					$finding_block = false;
				} else {
					// Nope, just dump these lines.
					continue;
				}
			}

			// Single line comments.
			if (preg_match("/\/\//", $trimmer)) {
				// If this is at the beginning of the line, ignore the line.
				if (preg_match("/^\/\//", $trimmer)) {
					continue;
				} else {
					// There's other stuff on the line, so let's keep that.
					$l = trim(preg_replace("/^(.+?)\/\/.+$/", "$1", $trimmer));
				}
			}

			// Block comments.
			if (preg_match("|/\*|", $trimmer)) {
				// Ok, so we found a comment.
				if (preg_match("|\*/|", $trimmer)) {
					// That's an inline comment.
					$l = preg_replace("|^(.*)/\*.*\*/(.*)$|", "$1$2", $l);
				} else {
					// That's a block comment.
					// Keep everyting before the opening.
					$l = preg_replace("|^(.*)/\*|", "$1", $l);
					// Note that we're looking for more blocks.
					$finding_block = true;
				}
			}

			if (!$this->skipmultiequal) {
				// Stop at multi-equal
				if (preg_match("/^\=\=\=/", $trimmer)) {
					break;
				}
			}

			$retrim = trim($l);
			if (strlen($retrim)) {
				$result .= $l."\n";
			}
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