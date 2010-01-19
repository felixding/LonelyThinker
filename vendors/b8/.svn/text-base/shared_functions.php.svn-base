<?php

#   Copyright (C) 2006-2008 Tobias Leupold <tobias.leupold@web.de>
#
#   This file is part of the b8 package
#
#   This program is free software; you can redistribute it and/or
#   modify it under the terms of the GNU General Public License
#   as published by the Free Software Foundation in version 2
#   of the License.
#
#   This program is distributed in the hope that it will be useful,
#   but WITHOUT ANY WARRANTY; without even the implied warranty of
#   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#   GNU General Public License for more details.
#
#   You should have received a copy of the GNU General Public License along
#   with this program; if not, write to the Free Software Foundation, Inc.,
#   59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.

# This is the base class with functions used by all b8 components

class b8SharedFunctions
{

	# This contains a variable indicating if the constructor worked so far.
	# This is a _real_ crappy way to do it and a damn hack -- but we can't
	# do it in a "clean" way, as we want to keep this compatible with PHP 4
	var $constructed;

	# This contains the configuration
	var $config;

	# Print an error message

	function echoError($message)
	{
		//echo "<b>b8 (" . get_class($this) . ")</b>: $message<br />\n";
	}

	# Check if a file to read or include is okay

	function checkFile($fileName, $toDo, $beVerbose)
	{

		# Do all tests requested in $toDo

		for($i = 0; $i < strlen($toDo); $i++) {

			switch($toDo{$i}) {

				case "e":

					# Check if the file exists

					if(!file_exists($fileName)) {

						if($beVerbose)
							$this->echoError("<kbd>$fileName</kbd> does not exist.");

						return FALSE;
					}

					break;

				case "f":

					# Check if the file is a file

					if(!is_file($fileName)) {

						if($beVerbose)
							$this->echoError("<kbd>$fileName</kbd> is not a file.");

						return FALSE;
					}

					break;

				case "d":

					# Check if the file is a directory

					if(!is_dir($fileName)) {

						if($beVerbose)
							$this->echoError("<kbd>$fileName</kbd> is not a directory.");

						return FALSE;
					}

					break;

				case "r":

					# Check if the file is readable

					if(!is_readable($fileName)) {

						if($beVerbose)
							$this->echoError("<kbd>$fileName</kbd> is not readable.");

						return FALSE;
					}

					break;

				case "w":

					# Check if the file is writabe

					if(!is_writable($fileName)) {

						if($beVerbose)
							$this->echoError("<kbd>$fileName</kbd> is not writable.");

						return FALSE;
					}

					break;

				default:
					$this->echoError("Unknown file test: \"" . $toDo{$i} . "\"");
					return FALSE;
					break;

			}

		}

		return TRUE;

	}

	# Function to get and check a configuration file

	function loadConfig($configFile, $defaultConfig)
	{

		# Get the real path of the config file
		$configFile = dirname(__FILE__) . "/etc/$configFile";

		# Set the default set of configuration values

		foreach($defaultConfig as $val) {
			$this->config[$val['name']] = $val['default'];
			$dataType[$val['name']] = $val['type'];
		}

		# Check if the files exists

		if(!$this->checkFile($configFile, "fr", FALSE)) {
			# We don't have a config file, so just exit
			return TRUE;
		}

		# Get the whole content of the configuration file and parse it

		$lineNo = 0;

		foreach(file($configFile) as $line) {

			$lineNo++;

			$line = trim($line);
			$line = preg_replace("/#.*/", "", $line);

			if($line == "")
				continue;

			$data = preg_split("/\s*=\s*/", $line, 2);

			# Check if we do have a value

			if(count($data) != 2) {

				# No value was set

				$this->echoError("Error parsing configuration file on line $lineNo (<kbd>" . htmlentities($line) . "</kbd>)");

				return FALSE;

			}

			# Check if we want to have this value

			if(!isset($this->config[$data[0]])) {

				# We have garbage here

				$this->echoError("Error parsing configuration file on line $lineNo (<kbd>" . htmlentities($line) . "</kbd>): unknown value: <kbd>{$data[0]}</kbd>");

				return FALSE;

			}

			# Overwrite the default value

			switch($dataType[$data[0]])
			{

				case "bool":

					if($data[1] == "TRUE")
						$this->config[$data[0]] = TRUE;
					elseif($data[1] == "FALSE")
						$this->config[$data[0]] = FALSE;

					break;

				case "int":
					$this->config[$data[0]] = (int) $data[1];
					break;

				case "float":
					$this->config[$data[0]] = (float) $data[1];
					break;

				case "string":
					$this->config[$data[0]] = (string) $data[1];
					break;

				case "path":

					$tmp = (string) $data[1];

					if($tmp{0} != "/")
						$data[1] = dirname(__FILE__) . "/{$data[1]}";

					$this->config[$data[0]] = $data[1];

					break;

			}

		}

		# The function arrived here, so everything seems to be okay ;-)
		return TRUE;

	}

	# Function to load class files and add new classes

	function loadClass($classFile, $className, $targetVarName, $args)
	{

		# Check if the file to include is okay
		if(!$this->checkFile($classFile, "fr", TRUE)) {
			$this->echoError("Could not include the class file for <kbd>$className</kbd> (<kbd>$classFile</kbd>).");
			return FALSE;
		}

		# Include it
		require $classFile;

		# Set up the new class

		if($args !== FALSE)
			$this->$targetVarName = new $className($args);
		else
			$this->$targetVarName = new $className();

		return;

	}

}

?>