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

# Use a DBA database (BerkeleyDB)

class storage_dba extends b8SharedFunctions
{

	# This is used to reference the DB
	var $db;

	# Constructor
	# Prepares the DB binding and trys to create a new database if requested

	function storage_dba()
	{

		# Till now, everything's fine
		# Yes, I know that this is crap ;-)
		$this->constructed = TRUE;

		# Default values for the configuration
		$config[] = array("name" => "createDB",		"type" => "bool",	"default" => FALSE);
		$config[] = array("name" => "dbFile",		"type" => "path",	"default" => "wordlist.db");
		$config[] = array("name" => "dbVersion",	"type" => "string",	"default" => "db4");

		# Get the configuration

		$configFile = "config_storage";

		if(!$this->loadConfig($configFile, $config)) {
			$this->echoError("Failed initializing the configuration.");
			$this->constructed = FALSE;
		}

		if($this->constructed) {

			# Check if we want to create a new database

			if($this->config['createDB']) {

				# Check if the file already exists

				if($this->checkFile($this->config['dbFile'], "e", FALSE)) {
					$this->echoError("<kbd>" . $this->config['dbFile'] . "</kbd> already exists. Please remove <kbd>createDB = TRUE</kbd> from <kbd>$configFile</kbd> to use this database or delete it to re-create it with no content.");
					$this->constructed = FALSE;
				}

				# Check if we have write permissions on the directory
				# where the database should be created

				$targetDir = dirname($this->config['dbFile']);

				if(!$this->checkFile($targetDir, "dw", TRUE)) {
					$this->echoError("A new database can't be created here. Please fix the permissions of this directory or choose another one.");
					$this->constructed = FALSE;
				}

			}

			else {

				# Check if the requested database exists

				if(!$this->checkFile($this->config['dbFile'], "efrw", TRUE)) {

					if($this->checkFile($this->config['dbFile'], "d", FALSE)) {
						$this->echoError("A directory can't be used as a database. Please fix your config.");
					}
					elseif(!$this->checkFile($this->config['dbFile'], "f", FALSE)) {
						$this->echoError("Please add <kbd>createDB = TRUE</kbd> to <kbd>$configFile</kbd> if you want to create a new database with this path.");
					}
					elseif(!$this->checkFile($this->config['dbFile'], "rw", FALSE)) {
						$this->echoError("The database has the wrong permissions. It has to be readable and writable. Please fix this file's permissions.");
					}

					$this->constructed = FALSE;

				}

			}

		}

		if($this->constructed) {

			# Connect to the database
			# If it doesn't exist, it will be created

			$this->db = dba_open($this->config['dbFile'], "c", $this->config['dbVersion']);

			# Check if the connection is okay
			if($this->db == FALSE) {
				$this->echoError("Could not connect to database <kbd>" . $this->config['dbFile'] . "</kbd>!");
				$this->constructed = FALSE;
			}

		}

		if($this->constructed) {

			# Check if a new database should be created

			if($this->config['createDB']) {

				# It's just necessary to insert the version "2" entry for the database version
				if($this->put("bayes*dbversion", "2") == FALSE) {
					$this->echoError("Error accessing the new database!");
					$this->constructed = FALSE;
				}

				if($this->constructed) {

					# Everything worked smoothly

					# Anyway -- don't let the user use b8 (although it would work now!)
					# before the "create database" flag isn't removed from the config file

					$this->echoError("Successfully created the new database. Please remove <kbd>createDB = TRUE</kbd> from <kbd>$configFile</kbd> to use b8.");

					$this->constructed = FALSE;

				}

			}

		}

	}

	# Get a token from the database

	function get($token)
	{
		return dba_fetch($token, $this->db);
	}

	# Store a token to the database

	function put($token, $count)
	{
		return dba_insert($token, $count, $this->db);
	}

	# Update an existing token

	function update($token, $count)
	{
		return dba_replace($token, $count, $this->db);
	}

	# Remove a token from the database

	function del($token)
	{
		return dba_delete($token, $this->db);
	}

}

?>