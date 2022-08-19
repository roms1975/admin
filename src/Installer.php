<?php 

	namespace roms;

//    require_once(__DIR__ . "/../vendor/autoload.php");

class Installer
{
	static public function install() 
	{
		$config = __DIR__ . "/../config/db.php";

		if (file_exists($config)) {
			echo "File " . $config . " exist. Please rename it and start again.\n";
			exit();
		}

		echo "Enter DB name (or type n to skip): ";
		$db = trim(fgets(STDIN));

		if ($db == 'n') {
			echo "Nothing to do. By\n";
			exit();
		}

		error_log("Enter DB login: ");
		$user = trim(fgets(STDIN));

		error_log("Enter DB password: ");
		$pass = trim(fgets(STDIN));

		$str = "<?php\n\n" .
				"return [\n" .
				"\t'class' => 'yii\db\Connection',\n" .
				"\t'dsn' => 'mysql:host=localhost;dbname=" . $db . "',\n" .
				"\t'username' => '" . $user . "',\n" .
				"\t'password' => '" . $pass . "',\n" .
				"\t'charset' => 'utf8',\n" .
			"];\n\n";
		
		if (!file_put_contents($config, $str)) {
			error_log("Unable create config file " . $config);
			exit();
		}

		error_log("Done");

	}
}

?>
