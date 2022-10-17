<?php
header('Content-Type: application/json');

/**
* Class downloaded from the {@link php.net#uniqid()} manual
*/

class UUID
{
	/**
	 * Generate v3 UUID
	 *
	 * Version 3 UUIDs are named based. They require a namespace (another 
	 * valid UUID) and a value (the name). Given the same namespace and 
	 * name, the output is always the same.
	 * 
	 * @param	uuid	$namespace
	 * @param	string	$name
	 */
	public static function v3($namespace, $name)
	{
		if(!self::is_valid($namespace)) return false;

		// Get hexadecimal components of namespace
		$nhex = str_replace(array('-','{','}'), '', $namespace);

		// Binary Value
		$nstr = '';

		// Convert Namespace UUID to bits
		for($i = 0; $i < strlen($nhex); $i+=2) 
		{
			$nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
		}

		// Calculate hash value
		$hash = md5($nstr . $name);

		return sprintf('%08s-%04s-%04x-%04x-%12s',

		// 32 bits for "time_low"
		substr($hash, 0, 8),

		// 16 bits for "time_mid"
		substr($hash, 8, 4),

		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 3
		(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000,

		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

		// 48 bits for "node"
		substr($hash, 20, 12)
		);
	}

	/**
	 * 
	 * Generate v4 UUID
	 * 
	 * Version 4 UUIDs are pseudo-random.
	 */
	public static function v4() 
	{
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

		// 32 bits for "time_low"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),

		// 16 bits for "time_mid"
		mt_rand(0, 0xffff),

		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand(0, 0x0fff) | 0x4000,

		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand(0, 0x3fff) | 0x8000,

		// 48 bits for "node"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
		);
	}

	/**
	 * Generate v5 UUID
	 * 
	 * Version 5 UUIDs are named based. They require a namespace (another 
	 * valid UUID) and a value (the name). Given the same namespace and 
	 * name, the output is always the same.
	 * 
	 * @param	uuid	$namespace
	 * @param	string	$name
	 */
	public static function v5($namespace, $name) 
	{
		if(!self::is_valid($namespace)) return false;

		// Get hexadecimal components of namespace
		$nhex = str_replace(array('-','{','}'), '', $namespace);

		// Binary Value
		$nstr = '';

		// Convert Namespace UUID to bits
		for($i = 0; $i < strlen($nhex); $i+=2) 
		{
			$nstr .= chr(hexdec($nhex[$i].$nhex[$i+1]));
		}

		// Calculate hash value
		$hash = sha1($nstr . $name);

		return sprintf('%08s-%04s-%04x-%04x-%12s',

		// 32 bits for "time_low"
		substr($hash, 0, 8),

		// 16 bits for "time_mid"
		substr($hash, 8, 4),

		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 5
		(hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		(hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

		// 48 bits for "node"
		substr($hash, 20, 12)
		);
	}

	public static function is_valid($uuid) {
		return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?'.
                      '[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
	}
}

/**
* Function downloaded from {@link stackoverflow.com}
* The purpose of it is to decode any html entities that may be within the
* given string, then convert the encoding to UTF-8 from a Windows specific
* format
*
* @param text The string to be converted to UTF-8
*/
function w1250_to_utf8($text) {
    // map based on:
    // http://konfiguracja.c0.pl/iso02vscp1250en.html
    // http://konfiguracja.c0.pl/webpl/index_en.html#examp
    // http://www.htmlentities.com/html/entities/
    $map = array(chr(0x8A) => chr(0xA9),chr(0x8C) => chr(0xA6),chr(0x8D) => chr(0xAB),chr(0x8E) => chr(0xAE),chr(0x8F) => chr(0xAC),chr(0x9C) => chr(0xB6),chr(0x9D) => chr(0xBB),chr(0xA1) => chr(0xB7),chr(0xA5) => chr(0xA1),chr(0xBC) => chr(0xA5),chr(0x9F) => chr(0xBC),chr(0xB9) => chr(0xB1),chr(0x9A) => chr(0xB9),chr(0xBE) => chr(0xB5),chr(0x9E) => chr(0xBE),chr(0x80) => '&euro;',chr(0x82) => '&sbquo;',chr(0x84) => '&bdquo;',chr(0x85) => '&hellip;',chr(0x86) => '&dagger;',chr(0x87) => '&Dagger;',chr(0x89) => '&permil;',chr(0x8B) => '&lsaquo;',chr(0x91) => '&lsquo;',chr(0x92) => '&rsquo;',chr(0x93) => '&ldquo;',chr(0x94) => '&rdquo;',chr(0x95) => '&bull;',chr(0x96) => '&ndash;',chr(0x97) => '&mdash;',chr(0x99) => '&trade;',chr(0x9B) => '&rsquo;',chr(0xA6) => '&brvbar;',chr(0xA9) => '&copy;',chr(0xAB) => '&laquo;',chr(0xAE) => '&reg;',chr(0xB1) => '&plusmn;',chr(0xB5) => '&micro;',chr(0xB6) => '&para;',chr(0xB7) => '&middot;',chr(0xBB) => '&raquo;');
	return html_entity_decode(mb_convert_encoding(strtr($text, $map), 'UTF-8', 'ISO-8859-2'), ENT_QUOTES, 'UTF-8');
}

/**
* This function is a modified function taken from {@link stackoverflow.com}
* The purpose is to remove all WordPress image captions from a given string,
* along with their associated image.
* 
* @param text		The post's text to remove captions from.
* @param tags		An array of HTML tags to leave; this isn't used in this
*					implementation but is left in for compatibility.
* @param invert		Whether to invert the process; this isn't used
*					in this implementation but is left in for compatibility.
*/
function strip_caption_content($text, $tags = '', $invert = FALSE) 
{ 
	// Search for all [caption][/caption] elements, removing tags inputted by $tags
	// Leave $tags expression blank for removing captions
	preg_match_all('/\[caption(.+?)[\s]*\/?[\s]*\[\/caption]/', trim($tags), $tags); 
	// Makes sure tags appear only once within the array
	$tags = array_unique($tags[1]); 
	
	if(is_array($tags) AND count($tags) > 0) 
	{ 
		if($invert == FALSE) 
		{ 
			// Removes the [caption][/caption] elements and everything in between them, leaving $tags
			// in place; $tags expression should be blank for removing [caption] elements
			return preg_replace('@\[(?!(?:'. implode('|', $tags) .')\b)(\w+)\b.*?].*?\[/\1]@si', '', $text); 
		} 
		else 
		{ 
			// Removes the [caption][/caption] elements and everything in between them
			return preg_replace('@\[\b.*?>.*?\[/caption]@si', '', $text); 
		} 
	} 
	elseif($invert == FALSE) 
	{ 
		// Removes the [caption][/caption] elements and everything in between them
		return preg_replace('@\[(\w+)\b.*?].*?\[/\1]@si', '', $text); 
	} 
	// Return the post's text without [caption][/caption] elements
	return $text; 
}

/**
* This function was written to replace a youtube link in an article with
* the verbage "Watch the video on our website".
* Note the space at the end of the replacement text (middle parameter of
* the preg_replace function); this is due to the regex expression removing
* the space as well as the link.
* 
* @param text		The post's text to replace a youtube link in.
*/
function replace_youtube_link($text) {
	$tmp = $text;
	$tmp = preg_replace('/https:\/\/www.you(.+?)[\s]/i', 'Watch the video on our website. ',$tmp);
	$tmp = preg_replace('/https:\/\/you(.+?)[\s]/i', 'Watch the video on our website. ',$tmp);
	return $tmp;
}

/**
* This function was written to replace a Twitter link in an article with
* the verbage "See the picture on our website".
* Note the space at the end of the replacement text (middle parameter of
* the preg_replace function); this is due to the regex expression removing
* the space as well as the link.
* 
* @param text		The post's text to replace a youtube link in.
*/
function replace_twitter_link($text) {
	$tmp = $text;
	$tmp = preg_replace('/https:\/\/www.twitter(.+?)[\s]/i', 'View the picture on our website. ',$tmp);
	$tmp = preg_replace('/https:\/\/twitter(.+?)[\s]/i', 'View the picture on our website. ',$tmp);
	return $tmp;
}
/**
* This function sanitizes a post that may have embedded twitter content.
* Due to the nature of these posts, it's not feasible to initially strip out
* the HTML tags, and one must manually remove the <a></a> links (the first
* two preg_replace functions) before removing the rest of the tags from the
* post. The function then replaces the @ and # symbols with "at " and "hash tag "
* respectively. Please note the space after each replacement; this is for
* ease of reading for Alexa.
* Finally, the function removes the left over "pic.twitter" shortened URL
* from the post. Due to the possibility of variables changing, the link to
* remove has only been semi-hard coded in, and the prefix as well as the actual
* shortened URL are being searched via a regex rule.
* 
* @param text		The post's text to replace a youtube link in.
*/
function fix_twitter($text) {
	// Assign the input to a temporary variable
	$tmp = $text;
	// Replace the start of a Twitter post with a blurb denoting that we're about
	// to read a post.
	$tmp = preg_replace("/<blockquote(.+?)[>]/i","The post on Twitter said: ",$tmp);
	// Strip the opening <a> tag from the links
	$tmp = preg_replace('/<a\shref=(.+?)[\s]*\/?[\s]*>/i','',$tmp);
	// Strip the closing </a> tag from the links
	$tmp = preg_replace('/<\/a>/i','',$tmp);
	// Strip the remaining HTML tags from the string
	$tmp = strip_tags($tmp);
	// Replace the weird HTML apostrophes with a '
	$tmp = preg_replace("/&#39;/i","'",$tmp);
	// Replace the ***.twitter.com/<shortened url> string with nothing
	$tmp = preg_replace('(\s[a-z{3}]*+.twitter\.com[^\s]+)','',$tmp);
	// Remove all the remaining HTTP links from the article.
	$tmp = preg_replace('(http[a-z{1}]*+.[^\s]+)','',$tmp);
	// Change @ symbol to read "at" with a trailing space for Alexa
	$tmp = str_replace("@","at ",$tmp);
	// Change # symbol to read "hash tag " with a trailing space for Alexa
	$tmp = str_replace("#", "hash tag ",$tmp);
	// Return the temporary variable to our calling code.
	return $tmp;
}

/**
* Connects to the MySQL database using the OOP {@link mysqli} object
* 
* @param	hostname	The name of the host to connect to
* @param	username	The username to use in connecting to the database
* @param	password	The password matched to username, used to the database
* @param	database	The schema we wish to connect to
*/
$context = new mysqli('localhost','scifirad_wp','scifirad_wp','scifirad_wp');
$context->set_charset("utf8mb4");

//$mysqli->set_charset('utf8mb4');
$jsonOutput = [];

// A connect_errno exists, so the connection failed
if($context->connect_errno)
{
	// echo "Error connecting to mysql" . $mysqli->connect_errno;
	// Because this is to be read by Alexa, we don't want any form of error message showing up.
	// In other words, if there was an error connecting to the database, it will silently fail.
	exit;
}

// The SQL statement to send to the database, selecting the appropriate fields from
// the WordPress posts table.
$sql= "SELECT ID, post_date, post_date_gmt, post_content, post_title, post_status, guid FROM mDroxjKmP_posts WHERE post_status='publish' AND post_type='post' AND length(post_content) < 4300 ORDER BY post_date DESC LIMIT 5;";
// echo $sql;
// We weren't able to return any records, which means we have a problem
// if(!$result = $context->query($sql))
// {
// 	echo "unable to execute query: " . $sql;
// 	// Because this is to be read by Alexa, we don't want any form of error message showing up.
// 	// In other words, if there was an error retrieving records, it will silently fail.
// 	exit;
// }

if(!$stmt = $context->query($sql)) {
	echo "unable to execute query";
}
while($row = $stmt->fetch_assoc()) {
	// Check to see if the current record includes an embedded video
	$copyOutput = $row['post_content'];

	if(stristr($copyOutput, "https://you") || stristr($copyOutput, "https://www.you")) {
		$copyOutput = replace_youtube_link($row['post_content']);
	}
	if(stristr($copyOutput, "https://twitter") || stristr($copyOutput, "https://www.twitter")) {
		$copyOutput = replace_twitter_link($row['post_content']);
	}

	/**
	* Takes the PST posted time and converts it into a human-readable date string.
	* Format is:
	* 	Long day name (e.g. Monday)
	* 	Long month name (e.g. January)
	* 	Day without leading zero, and with proper suffix
	* 	Four digit year (e.g. 2017)
	* 
	* @see php.net#date(format,timestamp)
	* @see php.net#strtotime(timestamp)
	*/
	$fDate = date('l F jS, Y', strtotime($row['post_date']));

	/**
	* Generates a psuedo-random but valid UUID for our namespace
	* 
	* @see UUID::v4()
	* @see UUID::is_valid(uuid)
	* @see UUID::v5(namespace,name)
	*/
	$tmpUid = UUID::v4();
	if(UUID::is_valid($tmpUid)) {
		// If the above namespace UUID is valid, use it to create our final
		// unique UUID based off the post's ID number from the database
		$uid = UUID::v5($tmpUid,$row['ID']);
	} else {
		// If the above namespace UUID isn't valid for some strange reason,
		// use an static arbitrary namespace UUID for the generation.
		$uid = UUID::v5("1546058f-5a25-4334-85ae-e68f2a44bbaf",$row['ID']);
	}
	// Assigns the UUID to the final variable, with the added stuff Amazon seems to
	// want at the start.
	$UUID = "urn:uuid:" . strtoupper($uid);

	/**
	* Explodes the date into an array so we can add the appropriate Amazon-required letters to it.
	* 
	* @see php.net#explode(delimiter,string,limit)
	* @see php.net#implode(glue,pieces)
	*/
	$gmtDate = explode(" ",$row['post_date_gmt']);
	// Add the "Z" to the end of the time field, as per the Amazon Alexa documentation
	$gmtDate[1] = $gmtDate[1] . "Z";
	// Implodes the array back into a string, joining the Date and Time fields with a "T", as
	// per the Amazon Alexa documentation
	$amazonDate = implode("T", $gmtDate);

	/**
	* Strips any images from the post that have a [caption][/caption] tag from WordPress
	*
	* @see #strip_caption_content(text)
	*/
	$tmp = strip_caption_content($copyOutput);
	/**
	* Strips the remaining HTML tags out of the post's body.
	* This has been commented out, but left inline for future
	* reference. fix_twitter(text) now 
	*
	* @see php.net#strip_tags(string)
	*/
	//$tmp = strip_tags($tmp);
	/**
	* Sanitizes a post from all embedded Twitter information,
	* changing @ and # to the more TTS readable "at" and "hash
	* tag".
	*
	* @see #strip_caption_content(text)
	*/
	$tmp = fix_twitter($tmp);
	/**
	* Removes the -30- end code from the bottom of the post.
	*
	* @see php.net#str_replace(search,replace,subject)
	*/
	$tmp = str_replace("-30-",'',$tmp);
	/**
	* Takes the weirdly formatted post, and converts it to UTF-8.
	*
	* @see #w1250_to_utf8(text)
	*/
	$text = w1250_to_utf8($tmp);
	
	// Create the opening byline for Alexa
	$strStart = "Here's the news from Krypton Radio for " . $fDate . ". ";
	// Add a period to the end of the title, for inclusion in the main body text.
	$strTitle =$row['post_title'] . ". ";
	// Add the boiler plate output to the end.
	$strBoiler = "For more geek news, visit scifi radio at scifi dot radio. It's Sci-fi, for your Wifi.";
	// Combine the above three into the finalOutput variable; this can be used with the title as it is
	// now, or without the title by commenting the line two lines below, and then uncommenting the line directly
	// below.
	//$finalOutput = $strStart . $text . $strBoiler;
	$finalOutput = $strTitle . $text . $strBoiler;
	
	// Takes all our created/gathered variables and adds them to an array for output to JSON format
	$jsonOut = Array("uid"=>$UUID,"updateDate"=>$amazonDate,"titleText"=>$row['post_title'],"mainText"=>$finalOutput,"redirectionUrl"=>$row['guid']);
	
	/**
	* Removes any \n character strings that may have been accidently added to our text
	* in this process, then strips any remaining HTML entities from our formatted post
	* body.
	*
	* @see php.net#str_replace(search,replace,subject)
	* @see php.net#htmlentities(string)
	*/
	$jsonOut["mainText"] = str_replace("\n"," ",$jsonOut["mainText"]);
	$jsonOut["mainText"] = htmlentities($jsonOut["mainText"]);
	
	/*
	This is a list of the REGEX expressions we use below, to strip out the
	extra information that's been added through all the conversion processes.
	It's been placed here for reference only.
	
	/&nbsp;/i
	/&rsquo;/i
	/&ndash;/i
	/&mdash;/i
	/&emdash;/i
	/&ldquo;/i
	/&lsquo;/i
	/&rdquo;/i
	/&quot;/i
	/&hellip;/i
	
	/(http|https)(:\\\\\/\\\\\/scifi.radio\\\\\/)/i
	/(    )/i
	/(   )/i
	
	/\[\b.*?]/i
	
	(\\")
	/\\\\\//i
	
	*/
	
	/**
	* Encode the finalized array to JSON format
	* 
	* @see php.net#json_encode(string)
	*/
	$jString = json_encode($jsonOut);
	
	/**
	* Using regex expressions, Search for any left over non-breaking space tags and
	* replace them with a normal space, then search for any abnormal "right-quote"
	* apostrophes and replace them with a single quote (') character.
	* Take the escaped URL that was created by the {@link #json_encode()} function
	* and replace it with a normally formatted URL
	* Finally look for an odd number of spaces created during the encoding process,
	* and change them to a single space.
	*
	* @see php.net#preg_replace(pattern,replacement,string)
	*/
	$jString = preg_replace("/&nbsp;/i"," ",$jString);
	$jString = preg_replace("/&rsquo;/i","'",$jString);
	$jString = preg_replace("/&ndash;/i","-",$jString);
	$jString = preg_replace("/&mdash;/i","-",$jString);
	$jString = preg_replace("/&emdash;/i","-",$jString);
	$jString = preg_replace("/&ldquo;/i","",$jString);
	$jString = preg_replace("/&lsquo;/i","",$jString);
	$jString = preg_replace("/&rdquo;/i","",$jString);
	$jString = preg_replace("/&quot;/i","",$jString);
	$jString = preg_replace("/&hellip;/i","...",$jString);
	$jString = preg_replace("/&amp;/i","&",$jString);
	$jString = preg_replace("/(http|https)(:\\\\\/\\\\\/scifi.radio\\\\\/)/i","https://scifi.radio/",$jString);
	$jString = preg_replace("/(    )/i"," ",$jString);
	$jString = preg_replace("/(   )/i"," ",$jString);
	$jString = preg_replace("/\[\b.*?]/i","",$jString);
	$jString = preg_replace('(\\\")',"",$jString);
	$jString = preg_replace("/\\\\\//i","/",$jString);
	
	
	// Output the final JSON object to the screen.
	echo $jString;
	// We have our article to be read by Amazon Alexa, so leave this function.
	break;
}

	
// Close off the database object to free up resources.
$context->close();
?>
