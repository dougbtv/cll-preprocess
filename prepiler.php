<?php

$pp = new prepiler();

class prepiler {

	var $infile = "dougtests.txt";
	var $outfile = "tests.txt";

	function prepiler() {

		$file = file_get_contents($this->infile);

		$foo = 1500;

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