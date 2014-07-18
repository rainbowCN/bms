<?PHP
/**
 * The fat-free autoloader supplied by ADC that can help you far away from the ugly requires. Only needs to 
 * require 'classesautoloader.php' only once in portal page, such as 'index.php'. If give up the default floder 
 * struct, you still need to implement&regist static *_loader method by yourself.
 *    
 * @author James.Yu
 * @created 2013-07-11
 * @updated 2013-08-08
 */

class ClassesAutoLoader {   
	
	private static $persistenceMode = "mysql";
	
	// Loader for middleware entity.
	public static function middleware_loader($classname) {
		$classname = strtolower($classname);
		$middleware_class_file = '../data/core/middleware/'.$classname.".php";
		if (file_exists($middleware_class_file)){
			require_once($middleware_class_file);
		}
	}	
	  
	// Loader for the diff type of persistence.
	public static function data_loader($classname) {
		$data_class_file = '../data/source/'.ClassesAutoLoader::$persistenceMode.'/'.$classname.".php";
		if (file_exists($data_class_file)){
			require_once($data_class_file);
		}
	}
		
	// Loader for hight-level interface&abstractfor entity.  
	public static function interface_loader($classname) {
		$interface_class_file = '../data/interface/'.strtolower($classname).".php"; 
		 
		if (file_exists($interface_class_file)){
			require_once($interface_class_file);    
		}     
	}       
		
	// Loader for hight-level super class which be implemented by some types. Such as mysql, mongo, php extension etc.
	public static function model_loader($classname) {
		$model_class_file = '../data/impl/'.strtolower($classname).".php";
		if (file_exists($model_class_file)){
			require_once($model_class_file);
		}
	}
		
	// Loader for model&sub entity.
	public static function module_loader($classname) {
		try {
			$classname = strtolower($classname);
			$user_module_class_file = '../data/module/'.ClassesAutoLoader::$persistenceMode.'/'.$classname.".php";
			if (file_exists($user_module_class_file)){
				require_once($user_module_class_file);
			} else {
				throw new Exception("ClassesAutoLoader::module_loader::not found ".$classname);
			}
		} catch(Exception $e) {
			echo $e->getMessage();
		}
	} 
	

 	
} 

spl_autoload_register('ClassesAutoLoader::interface_loader');
spl_autoload_register('ClassesAutoLoader::middleware_loader');
spl_autoload_register('ClassesAutoLoader::data_loader');
spl_autoload_register('ClassesAutoLoader::model_loader');
spl_autoload_register('ClassesAutoLoader::module_loader');



