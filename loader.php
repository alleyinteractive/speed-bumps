<?php 
/* Notes:  
 * The fileName and class shoud have the same name. That's why for example, I changed the class-speed-bumps.php name to SpeedBumps
 * so when I create a new instance $speedBumps = new SpeedBumps, the $file variable from line 27 will construct itself taking
 * the name of the class which matches with the name of the file. 
 * 
*/  

//  This method is responsible for loading any php file dependency automatically within the plugin namespace {SpeedBumps}.
	spl_autoload_register(function ($class){
		// project-specific namespace
		$prefix = "SpeedBumps";

		// base directory for the namespace prefix 
		$base_dir = dirname(__FILE__);

		//length of $prefix 
		$len = strlen($prefix);

		// is the class part of the namespace? NO? Return :(
		// Compares the first n characters
		if(strncmp($prefix, $class, $len) !== 0){
			return ;
		}
		//otherwise...
		//Get the className
		$className = substr($class,$len);
		//Construct the file path
		$file = $base_dir . str_replace('\\', '/', $className) . '.php';
		//If the file exits....
		if (file_exists($file)){
			//Require the file
			require($file);
		}

	});
?>