<?php
	error_reporting(0);

	$filename = 'upload/' . $_GET['f'];
	$counter_filename = 'count.txt';

	if (file_exists($filename)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));
		readfile($filename);

		unlink($filename);
		file_put_contents($counter_filename, @file_get_contents($counter_filename)+1);

		exit;
	}
?>