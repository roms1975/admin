<?php 

  namespace roms;
  
  require_once(__DIR__ . "/../vendor/autoload.php");

  class Installer
  {
    static public function install() 
    {
		echo __DIR__ . "\n";
		file_put_contents(__DIR__ . "/install.log", "It work!");
    }
  
  }

?>
