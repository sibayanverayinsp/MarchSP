<?php
	// Function to remove folders and files 
	function rrmdir($dir) {
		if(is_dir($dir)) {
			$files=scandir($dir);
			foreach($files as $file) {
				if($file!="." && $file!="..") {
					rrmdir($dir."/".$file);
				}
			}
			rmdir($dir);
		}
		else if(file_exists($dir)) {
			unlink($dir);
		}
	}

    // Function to copy folders and files       
	function rcopy($src, $dst) {
		if(file_exists($dst)) {
			rrmdir($dst);
		}
		if(is_dir($src)) {
			mkdir($dst);
			$files=scandir($src);
			foreach($files as $file) {
				if($file!="." && $file!="..") {
					rcopy($src."/".$file,$dst."/".$file);
				}
			}
		}
		else if(file_exists($src)) {
			copy($src,$dst);
		}
	}
	
	//function to create a zip file
	function createzip($src, $dest, $overwrite="") {
		$zip=new ZipArchive();
		if($zip->open($dest,$overwrite?ZIPARCHIVE::OVERWRITE:ZIPARCHIVE::CREATE)===TRUE) {
			$zip->addFile($src);	// Add the files to the .zip file
			$zip->close(); 			// Closing the zip file
		}
	}
	
	//function to extract a zip file
	function extractzip($source, $destination) {
		$zip=new ZipArchive();				// Call object of ZipArchive
		if($zip->open($source)===TRUE) {	// Open Compressed.zip for extracting all files
			$destination=str_replace("\\","/",realpath($destination));
			// Will extract all files from zip to given path.
			$zip->extractTo($destination);
			$zip->close();
		}
	}
	
	//function to create a zip from a directory
	function createdirzip($source, $destination, $overwrite="") {
		if(!extension_loaded("zip") || !file_exists($source)) {
			return false;
		}
		$zip=new ZipArchive();
		//$source=str_replace('\\', '/', realpath($source));
		// to replace path of path to real path
		if(is_dir($source)===true) {
			$files=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source),RecursiveIteratorIterator::SELF_FIRST);
			foreach($files as $file) {
				$file=str_replace("\\","/",realpath($file));
				$ext=substr(strrchr($file,"."),1);
				// To get extension of files in directory
				if($ext=="zip") {
					unlink($file);
					// if overwrite file,its overwrite zip folder
					// also within a directory so will unlink it
					continue;
				}
			}
			if(!$zip->open($destination,$overwrite?ZIPARCHIVE::OVERWRITE:ZIPARCHIVE::CREATE)) {
				return false;
			}
			if(is_dir($source)===true) {
				$files=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source),RecursiveIteratorIterator::SELF_FIRST);
				//To find all the files(recursively) in a certain directory
				foreach($files as $file) {
					if(is_dir($file)===true) {
						$zip->addEmptyDir(str_replace($source."/","",$file."/"));
					}
					else if(is_file($file)===true) {
						$zip->addFromString(str_replace($source."/","",$file),file_get_contents($file));
						//To add a file to a ZIP archive with the use of its contents
					}
				}
			}
			else if(is_file($source)===true) {
				$zip->addFromString(basename($source),file_get_contents($source));
			}
			return $zip->close();
		}
	}

	function n() {
		echo "<br><br>";
	}
?>