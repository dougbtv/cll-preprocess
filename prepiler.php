<?php

$pp = new prepiler($argv);

class prepiler {

	var $outfile = "/tmp/compile.cll";

	function prepiler($argv) {

		$file = file_get_contents($argv[1]);

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

}

?>