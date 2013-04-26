<?
//Delete directory
function delete($file) {
	if (file_exists($file)) {
		chmod($file,0777);
		if (is_dir($file)) {
			$handle = opendir($file); 
			while($filename = readdir($handle))
			{
				if ($filename != "." && $filename != "..") {
					delete($file."/".$filename);
				}
			}
			closedir($handle);
			rmdir($file);
		} 
		else {
			unlink($file);
		}
	}
}
delete('templates_c');
?>