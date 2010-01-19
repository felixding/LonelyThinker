<?php

#   Copyright (C) 2006-2008 Tobias Leupold <tobias.leupold@web.de>
#
#   b8 - a Bayesian spam filter compatible with PHP 4 and 5
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
require_once dirname(__FILE__) . "/shared_functions.php";

# The b8 filter main class

class b8 extends b8SharedFunctions
{

	# This is the storage class variable
	var $storage;

	# This is the lexer class variable
	var $lexer;

	# This is a date in the form YYYYMMDD
	var $today;

	# Constructor
	# Initialize the requested storage class and set the filter up

	function b8()
	{

		# Till now, everything's fine
		# Yes, I know that this is crap ;-)
		$this->constructed = TRUE;

		# Get now
		$this->today = date("ymd", time());

		# This is the directory where this file is located
		$thisDir = dirname(__FILE__);

		# Get and check the configuration

		# The default configuration
		$config[] = array("name" => "lexerType",	"type" => "string",	"default" => "default");
		$config[] = array("name" => "databaseType",	"type" => "string",	"default" => "dba");
		$config[] = array("name" => "useRelevant",	"type" => "int",	"default" => 15);
		$config[] = array("name" => "minDev",		"type" => "float",	"default" => 0.2);
		$config[] = array("name" => "robS",		"type" => "float",	"default" => 0.3);
		$config[] = array("name" => "robX",		"type" => "float",	"default" => 0.5);
		$config[] = array("name" => "sharpRating",	"type" => "bool",	"default" => FALSE);

		# Get the configuration

		if(!$this->loadConfig("config_b8", $config)) {
			$this->echoError("Failed initializing the configuration. Truncating.");
			$this->constructed = FALSE;
		}

		if($this->constructed) {

			# Set up the storage class

			$classFile = "$thisDir/storage/storage_" . $this->config['databaseType'] . ".php";
			$className = "storage_" . $this->config['databaseType'];

			# Check if an argument was passed to b8
			if(func_num_args() > 0) {
				# if so, pass it to the storage class
				$passedArg = func_get_arg(0);
			}
			else {
				# otherwise, just pass FALSE
				$passedArg = FALSE;
			}

			# Load the proper class file and set up the new storage object
			$this->loadClass($classFile, $className, "storage", $passedArg);

			# Check if everything worked smoothly
			if(!$this->storage->constructed) {
				$this->echoError("Could not initialize the storage class. Truncating.");
				$this->constructed = FALSE;
			}

		}

		if($this->constructed) {
			# Check the database version and display a warning if the database should be updated
			if($this->storage->get("bayes*dbversion") != "2") {
				$this->echoError("Please update your database to version 2! Things could break if you use a MySQL table. With a BerkeleyDB, you could at least get warnings about missing variables if your server PHP is configured to be verbose.");
			}

			# Set up the lexer class

			$classFile = "$thisDir/lexer/lexer_" . $this->config['lexerType'] . ".php";
			$className = "lexer_" . $this->config['lexerType'];

			# Load the proper class file and set up the new storage object
			$this->loadClass($classFile, $className, "lexer", FALSE);

			# Check if everything worked smoothly
			if(!$this->lexer->constructed) {
				$this->echoError("Could not initialize the lexer class. Truncating.");
				$this->constructed = FALSE;
			}

		}

	}

	# Check the validity of the category of a request

	function checkCategory($category)
	{

		# There are only "ham" and "spam"

		if($category != "ham" and $category != "spam") {

			# The user passed garbage

			$this->echoError("Unknown category: \"$category\"");
			return FALSE;

		}

		else {
			# Okay
			return TRUE;
		}

	}

	# Save a reference text

	function learn($text, $category)
	{

		# Look if the request is okay
		if(!$this->checkCategory($category))
			return FALSE;

		# Get all tokens from $text
		$tokens = $this->lexer->getTokens($text);
		
	    //debug
	    //print_r($tokens);    
	    //die();		

		# Check if the lexer could work correctly
		if($tokens === FALSE) {
			$this->echoError("Lexing the given data failed. Cannot learn it.");
			return FALSE;
		}

		# Update all tokens

		foreach($tokens as $word => $count) {

			# Check if the token is already in the database; create an empty set otherwise
			
			if($tmp = $this->storage->get($word)) {
				list($count_ham, $count_spam, $lastseen) = explode(" ", $tmp);
				$update = TRUE;
			}
			else {
				$count_ham = 0;
				$count_spam = 0;
				$update = FALSE;
			}

			# Increment the right counter ...

			# We're doing stuff a bit different than the other spam filters out there:
			# tokens that occur more than once also count more the once

			if($category == "ham"){
				$count_ham += $count;}
			else{
				$count_spam += $count;}

			# ... and put it in the database
			if($update){
				$this->storage->update($word, "$count_ham $count_spam " . $this->today);}
			else{
				$this->storage->put($word, "$count_ham $count_spam " . $this->today);}

		}

		# Update the number of texts
		if(!$this->storage->put("bayes*texts.$category", "1")) {
			$texts = $this->storage->get("bayes*texts.$category");
			$texts++;
			$this->storage->update("bayes*texts.$category", $texts);
		}

	}

	# Delete a reference text

	function unlearn($text, $category)
	{

		# Look if the request is okay
		if(!$this->checkCategory($category))
			return FALSE;

		# Get all tokens from $text
		$tokens = $this->lexer->getTokens($text);

		# Check if the lexer could work correctly
		if($tokens === FALSE) {
			$this->echoError("Lexing the given data failed. Cannot unlearn it.");
			return FALSE;
		}

		# Update all tokens

		foreach($tokens as $word => $count)
		{

			# Check if there IS anything to unlearn; continue otherwise
			if($tmp = $this->storage->get($word))
				list($count_ham, $count_spam, $lastseen) = explode(" ", $tmp);
			else
				continue;

			# Decrement the right counter

			if($category == "ham") {

				$count_ham -= $count;

				if($count_ham < 0)
					$count_ham = 0;

			}

			else {

				$count_spam -= $count;

				if($count_spam < 0)
					$count_spam = 0;

			}

			# If the token is still there, update it; delete it otherwise
			if($count_ham > 0 or $count_spam > 0)
				$this->storage->update($word, "$count_ham $count_spam $lastseen");
			else
				$this->storage->del($word);

		}

		# Update the number of texts

		$texts = $this->storage->get("bayes*texts.$category");

		# If the number of texts is > 0, update it, leave it to be 0 otherwise
		if($texts > 0) {
			$texts--;
			$this->storage->update("bayes*texts.$category", $texts);
		}

	}

	# Classify a text

	function classify($text)
	{

		# Get the number of ham and spam texts so that the spam
		# probability can be calculated in relation to them
		$texts_ham  = $this->storage->get("bayes*texts.ham");
		$texts_spam = $this->storage->get("bayes*texts.spam");

		# At least, one ham and one spam text has to be saved
		if(!$texts_ham or !$texts_spam) {
			$this->echoError("Cannot categorize this text: at least one ham and one spam text has to be saved to be able to categorize!");
			return FALSE;
		}

		# Get the spamminess for each token

		# Get all tokens
		$tokens = $this->lexer->getTokens($text);
		
	    //debug
	    //print_r($tokens);    
	    //die();		

		# Check if the lexer could work correctly
		if($tokens === FALSE) {
			$this->echoError("Lexing the given data failed. Cannot classify it.");
			return FALSE;
		}

		foreach($tokens as $word => $count) {

			# How often is it there?
			$word_count[$word] = $count;

			# Spamminess
			$rating[$word] = $this->getProbability($word, $texts_ham, $texts_spam);

			# Importance (distance to 0.5)
			$importance[$word] = abs(0.5 - $rating[$word]);

		}

		# Order by importance
		arsort($importance);
		reset($importance);

		# Get the most interesting tokens (use all if we have less than the given number)

		$relevant = array();

		for($i = 0; $i < $this->config['useRelevant']; $i++) {

			if($tmp = each($importance)) {

				# Important tokens remain

				# If the token's rating is relevant enough, use it

				if(abs(0.5 - $rating[$tmp['key']]) > $this->config['minDev']) {

					# Tokens that appear more than once also count more than once

					for($x = 0; $x < $word_count[$tmp['key']]; $x++)
						$relevant[] = $rating[$tmp['key']];

				}

			}

			else {
				# We have less than words to use, so we already
				# use what we have and can break here
				break;
			}

		}

		# Calculate the spamminess of the text (thanks to Mr. Robinson ;-)

		# We set both hamminess and Spamminess to 1 for the first multiplying
		$hamminess  = 1;
		$spamminess = 1;

		# Consider all relevant ratings

		foreach($relevant as $value) {
			$hamminess  *= (1.0 - $value);
			$spamminess *= $value;
		}

		# If no token was good for calculation, we really don't know how to rate
		# this text; so we assume a spam and ham probability of 0.5

		if($hamminess == 1 and $spamminess == 1) {
			$hamminess = 0.5;
			$spamminess = 0.5;
			$n = 1;
		}

		else {
			# Get the number of relevant ratings
			$n = count($relevant);
		}

		# Calculate the combined rating

		# The actual hamminess and spamminess
		$hamminess  = 1 - pow($hamminess,  (1 / $n));
		$spamminess = 1 - pow($spamminess, (1 / $n));

		# Calculate the combined indicator
		$probability = ($hamminess - $spamminess) / ($hamminess + $spamminess);

		# We want a value between 0 and 1, not between -1 and +1, so ...
		$probability = (1 + $probability) / 2;

		# Alea iacta est
		return $probability;

	}

	# Calculate the spamminess of a single token

	function getProbability($word, $texts_ham, $texts_spam)
	{

		if($data = $this->storage->get($word)) {

			# The token is found in the database

			# Calculate the spamminess of this token ...
			$rating = $this->calcProbability($data, $texts_ham, $texts_spam);

			# ... and update its lastseen parameter
			$this->updateLastseen($word, $data);

		}

		else {

			# The token is NOT found in the database,
			# so we try to find similar ones by "degenerating" it

			# Add different version of upper and lower case

			# Lower case version
			$degenerate[] = strtolower($word);

			# Upper case version
			$degenerate[] = strtoupper($degenerate[0]);

			# Version with the first letter in upper case
			$degenerate[] = ucfirst($degenerate[0]);

			# Degenerate all versions

			foreach($degenerate as $alt_word) {

				# Look for stuff like !!! and ???

				if(preg_match("/[!?]$/", $alt_word)) {

					# Add versions with different !s and ?s

					if(preg_match("/[!?]{2,}$/", $alt_word)) {
						$tmp = preg_replace("/([!?])+$/", "$1", $alt_word);
						$degenerate[] = $tmp;
					}

					$tmp = preg_replace("/([!?])+$/", "", $alt_word);
					$degenerate[] = $tmp;

				}

				# Look for ... at the end of the word

				$alt_word_int = $alt_word;

				while(preg_match("/[\.]$/", $alt_word_int)) {
					$alt_word_int = substr($alt_word_int, 0, strlen($alt_word_int) - 1);
					$degenerate[] = $alt_word_int;
				}

			}

			# Create an empty array for the degenerated ratings
			$deg_rating = array();

			# Look up all degenerated tokens in the database

			foreach($degenerate as $tmp) {

				if($tmp == $word) {

					# Do nothing if a degenerated token is the same
					# as the initial token not found in the database

					continue;

				}

				if($data = $this->storage->get($tmp)) {

					# A similar token was found in the database, so ...

					# ... get its spamminess and add it to the list ...
					$deg_rating[] = $this->calcProbability($data, $texts_ham, $texts_spam);

					# ... and update this token's lastseen parameter
					$this->updateLastseen($tmp, $data);

				}

			}

			if(count($deg_rating) > 0) {

				# We have found some similar tokens in the database

				# The default rating is 0.5 simply saying nothing
				$rating = 0.5;

				# Choose the rating which is the farthest from 0.5

				foreach($deg_rating as $tmp) {

					# Check all degenerated token ratings

					if(abs(0.5 - $tmp) > abs(0.5 - $rating)) {
						# We have a more interesting rating than
						# the current one, so use this one!
						$rating = $tmp;
					}

				}

			}

			else {

				# The token is really unknown, so choose the default
				# rating for unknown tokens

				$rating = $this->calcProbability("0 0 0", 0, 0);

			}

		}

		# Believe it or not -- now we have the rating for this token ;-)
		return $rating;

	}

	# Do the actual spamminess calculation of a single token

	function calcProbability($data, $texts_ham, $texts_spam)
	{

		# Get the token's data
		list($count_ham, $count_spam, $lastseen) = explode(" ", $data);

		# Let's see what we have

		$rating = FALSE;

		# Should we use sharp ratings?

		if($this->config['sharpRating']) {

			if($count_spam == 0 and $count_ham != 0) {

				# Definitely ham, never occured in spam
				# So we assume use a quite low rating

				if($count_ham > 10)
					$rating = 0.0001;
				else
					$rating = 0.0002;

			}

			elseif($count_ham == 0 and $count_spam != 0) {

				# Definitely spam, never occured in ham
				# So we assume use a quite high rating

				if($count_spam > 10)
					$rating = 0.9999;
				else
					$rating = 0.9998;

			}

		}

		# Check if we have to calculate Robinson's rating

		if($rating === FALSE) {

			# Occured both in ham and in spam, is completely unknown
			# or we don't want to use sharp ratings.

			# ... so we have to do some math :-)

			if($count_ham == 0 and $count_spam == 0) {
				# This prevents a division by 0
				$rating_word = 1;
			}

			else {

				# Calculate the basic probability by Mr. Graham

				# But: consider the number of ham and spam texts saved instead of the
				# number of entrys where the token appeared to calculate a relative spamminess
				# because we count tokens appearing multiple times not just once but
				# as often as threy appear in the learned texts

				$rel_ham  = $count_ham / $texts_ham;
				$rel_spam = $count_spam / $texts_spam;
				$rating_word = $rel_spam / ($rel_ham + $rel_spam);

			}

			# Calculate the better probability proposed by Mr. Robinson
			# This strips down to the robX value if the token is completely unknown

			$all = $count_ham + $count_spam;

			$rating = (($this->config['robS'] * $this->config['robX']) + ($all * $rating_word)) / ($this->config['robS'] + $all);

		}

		# Here we have our single rating for the given data
		return $rating;

	}

	# Update the lastseen parameter of a token

	function updateLastseen($word, $data)
	{

		# Get the token's data to preserve it ...
		list($count_ham, $count_spam, $lastseen) = explode(" ", $data);

		# And store it with the new date
		$this->storage->update($word, "$count_ham $count_spam " . $this->today);

	}

}

?>