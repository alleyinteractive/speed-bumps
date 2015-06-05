<?php 
// anthony2727/speed-bumps
// forked from fusioneng/speed-bumps
// AnthonyRodriguez.itt@gmail.com
// www.github.com/anthony2727
//  
// 
//  This class is responsible for loading any php file dependency automatically in the plugin namespace.
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
		
		$className = substr($class,$len);

		$file = $base_dir . str_replace('\\', '/', $className) . '.php';

		if (file_exists($file)){

			require($file);
		}

	});
?>