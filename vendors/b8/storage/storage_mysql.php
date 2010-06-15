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

# Get the shared functions class file (if not already loaded)
require_once dirname(__FILE__) . "/../shared_functions.php";

# Use a MySQL table

class storage_mysql extends b8SharedFunctions
{

	# This contains the connection ID
	var $mysqlRes;

	# Constructor
	# Looks if the MySQL binding is working and trys to create a new table if requested

	function storage_mysql()
	{

		# Till now, everything's fine
		# Yes, I know that this is crap ;-)
		$this->constructed = TRUE;

		# Default values for the configuration
		$config[] = array("name" => "createDB",		"type" => "bool",	"default" => FALSE);
		$config[] = array("name" => "tableName",	"type" => "string",	"default" => "b8_wordlist");
		$config[] = array("name" => "host",		"type" => "string",	"default" => "localhost");
		$config[] = array("name" => "user",		"type" => "string",	"default" => "");
		$config[] = array("name" => "pass",		"type" => "string",	"default" => "");
		$config[] = array("name" => "db",		"type" => "string",	"default" => "");

		# Get the configuration

		$configFile = "config_storage";

		if(!$this->loadConfig($configFile, $config)) {
			$this->echoError("Failed initializing the configuration.");
			$this->constructed = FALSE;
		}

		if($this->constructed) {

			# Get the MySQL link resource to use

			$arg = FALSE;

			if(func_num_args() > 0)
				$arg = func_get_arg(0);

			if($arg != FALSE) {

				if(!is_array($arg)) {
					# Only a resource was passed, so use this one (behave like in older versions)
					$this->mysqlRes = $arg;
				}

				else {

					# An array was passed, so look what we have

					if(isset($arg['mysqlRes'])) {
						# We have a 'resource' entry
						$this->mysqlRes = $arg['mysqlRes'];
					}

					if(isset($arg['tableName'])) {

						# We have a 'tableName' entry

						# Check if it's really a string
						if(!is_string($arg['tableName'])) {
							$this->echoError("The 'tableName' argument passed to b8 is not a string (passed variable: \"" . gettype($arg['tableName']) . "\"). Please be sure to pass a string containing the MySQL table's name that should be used instead of the tableName variable set in the config file.");
							$this->constructed = FALSE;
						}
						else
							$this->config['tableName'] = $arg['tableName'];

					}

				}

				# Check if the resource to use is really a MySQL-link resource

				$argType = gettype($this->mysqlRes);

				if(!is_resource($this->mysqlRes)) {
					$this->echoError("The argument passed to b8 is not a resource (passed variable: \"$argType\"). Please be sure to pass a MySQL-link resource to b8 or pass nothing and make sure that all of the following values are set in <kbd>$configFile</kbd>: <i><kbd>host</kbd></i>, <i><kbd>user</kbd></i>, <i><kbd>pass</kbd></i> and <i><kbd>db</kbd></i> so that a separate MySQL connection can be set up by b8.");
					$this->constructed = FALSE;
				}

				$resType = get_resource_type($this->mysqlRes);

				if($resType != "mysql link" and $resType != "mysql link persistent" and $this->constructed) {
					$this->echoError("The passed resource is not a MySQL-link resource (passed resource: \"$resType\"). Please be sure to pass a MySQL-link resource to b8 or pass nothing and make sure that all of the following values are set in <kbd>$configFile</kbd>: <i><kbd>host</kbd></i>, <i><kbd>user</kbd></i>, <i><kbd>pass</kbd></i> and <i><kbd>db</kbd></i> so that a separate MySQL connection can be set up by b8.");
					$this->constructed = FALSE;
				}

			}

			else {

				# No resource was passed, so we want to set up our own connection

				# Set up the MySQL connection
				$this->mysqlRes = mysql_connect($this->config['host'], $this->config['user'], $this->config['pass']);
				
				//modified by handaoliang.fix utf-8 charset bug.
				@mysql_query("SET NAMES 'utf8'");

				# Check if it's okay
				if($this->mysqlRes == FALSE) {
					$this->echoError("Could not connect to MySQL.");
					$this->constructed = FALSE;
				}

				if($this->constructed) {
					# Open the database where the wordlist is/will be stored
					if(!mysql_select_db($this->config['db'])) {
						$this->echoError("Could not select the database \"$db\".");
						$this->constructed = FALSE;
					}
				}

			}

		}

		if($this->constructed) {

			# Here, we should have a working MySQL connection, so ...

			# Check if we want to create a new database

			if($this->config['createDB']) {

				# Check if the wordlist table already exists
				if(mysql_query("DESCRIBE " . $this->config['tableName'], $this->mysqlRes)) {
					$this->echoError("The table <kbd>" . $this->config['tableName'] . "</kbd> already exists in the selected database. Please remove <kbd>createDB = TRUE</kbd> from <kbd>$configFile</kbd> to use this table or drop it to re-create it with no content.");
					$this->constructed = FALSE;
				}

				else {

					# If not, create it

					if(mysql_query(
						"CREATE TABLE " . $this->config['tableName'] . " (
							token VARCHAR(255) BINARY PRIMARY KEY,
							count VARCHAR(255)
						)", $this->mysqlRes)) {

						$this->echoError("Successfully created the table <kbd>" . $this->config['tableName'] . "</kbd>.");

						# Try to put in the "version 2" tag
						if($this->put("bayes*dbversion", "2") == FALSE) {
							$this->echoError("Error accessing the new table.");
							$this->constructed = FALSE;
						}

						else {

							# Everything worked smoothly

							# Anyway -- don't let the user use b8 (although it would work now!)
							# before the "create database" flag isn't removed from the config file

							$this->echoError("Successfully created the new database. Please remove <kbd>createDB = TRUE</kbd> from <kbd>$configFile</kbd> to use b8.");

							$this->constructed = FALSE;

						}

					}

				}

			}

		}

		if($this->constructed) {

			# Check if the table is accessible
			if(!mysql_query("DESCRIBE " . $this->config['tableName'], $this->mysqlRes)) {
				$this->echoError("The table <kbd>" . $this->config['tableName'] . "</kbd> does not exist in the selected database. Please add <kbd>createDB = TRUE</kbd> to <kbd>$configFile</kbd> to create this table of select another one.");
				$this->constructed = FALSE;
			}

		}

		# If the above query worked, we now shoule be able to use b8.

	}

	# Get a token from the database

	function get($token)
	{

		$res = mysql_fetch_assoc(mysql_query("
			SELECT count
			FROM " . $this->config['tableName'] . "
			WHERE token='" . mysql_real_escape_string($token, $this->mysqlRes) . "'
			", $this->mysqlRes));

		if($res)
			return $res['count'];
		else
			return FALSE;

	}

	# Store a token to the database

	function put($token, $count)
	{
		return mysql_query("
			INSERT INTO " . $this->config['tableName'] . " (
				token,
				count
				)
			VALUES(
				'" . mysql_real_escape_string($token, $this->mysqlRes) . "',
				'$count'
				)
			", $this->mysqlRes);
	}

	# Update an existing token

	function update($token, $count)
	{
		return mysql_query(
			"UPDATE " . $this->config['tableName'] . "
			SET count='$count'
			WHERE token='" . mysql_real_escape_string($token, $this->mysqlRes) . "'
			", $this->mysqlRes);
	}

	# Remove a token from the database

	function del($token)
	{
		return mysql_query("
			DELETE FROM " . $this->config['tableName'] . "
			WHERE token='" . mysql_real_escape_string($token, $this->mysqlRes) . "'
			", $this->mysqlRes);
	}

}

?>