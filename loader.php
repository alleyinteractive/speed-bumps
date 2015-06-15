<?php 
//  This method is responsible for loading any php-class file dependency 
//  automatically within the plugin's namespace {Speed_Bumps} following WP Naming Convention.
//  
	spl_autoload_register(function ($class){

		// project-specific namespace
		$prefix = "Speed_Bumps";

		// base directory for the namespace prefix 
		$base_dir = dirname(__FILE__). '/inc';

		//length of $prefix 
		$len = strlen($prefix);

		// is the class part of the namespace? NO? Return :(
		// Compares the first n characters
		if(strncmp($prefix, $class, $len) !== 0){
			return ;
		}
		//otherwise...
		//Get the full class name
		$full_class_name = substr($class,$len);

		// get corresponding file name
		$file_name = standard_filename_resolver($full_class_name);
		
		//Construct the file path
		$file = $base_dir . str_replace('\\', '/', $file_name);
		
		//If the file exits....
		if (file_exists($file)){
			//Require the file
			require($file);
		}

	});

	// This function will enforce wp naming convention 
	// when resolving the filename using merely the class name.
	// 
	function standard_filename_resolver($full_class_name){
		$pos = strrpos($full_class_name, "\\");
		$class_name = substr($full_class_name, $pos + 1);
		$hyphen_separated_class_name = str_replace('_', '-', $class_name);
		$standard_filename = strtolower(str_replace($class_name, 'class-'.$hyphen_separated_class_name, $full_class_name));
		return $standard_filename . '.php';
	}

?>