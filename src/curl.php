<?php

/**
 * @class	Curl
 * @brief	An object-oriented layer to PHP curl functions
 * @author	Lotfi Bentouati
 * @version	1.0
 * @copyright	Do What The Fuck You Want To Public License
 *
 * Initialising and configuring a cURL handle in PHP can sometimes be a bit
 * tricky or time consuming, mainly when we just need to retrieve a simple page.
 * The goal of the Curl class is to abstract the configuration and error
 * handling, and to offer the user simple methods to do the basic tasks. The
 * last version is always at https://github.com/skorpios/curl-class
 */
class	Curl
{
  /** Constructor.
   * - Initialise all properties
   * - Initialise a cURL handle
   * - Configure the cURL handle
   *
   * @throw Exception cURL extension is not loaded
   * @throw Exception unable to create a cURL handle
   */
  public function __construct()
  {
    $this->initProperties();
    $this->initHandle();
    $this->configureHandle();
  }

  /** Destructor.
   * - Close the cUrl handle
   * - Close the output file if any
   */
  public function __destruct()
  {
    curl_close($this->_ch);
    $this->closeFile();
  }

  /* #################### ACTIONS ########################################### */

  /** Get the content of a URL by performing a HTTP GET request
   *
   * @param string $url
   *  the url to get the content from
   * @retval string
   *  the content issued by the HTTP GET request
   * @exception Exception the request failed
   * @example 1.basic.php
   */
  public function &get($url)
  {
    curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
    return $this->exec($url);
  }

  /** Get the content of a URL by performing a HTTP POST request
   *
   * @param string $url
   *  the url to get the content from
   * @param array $postfields
   *  an array containing the data to be posted
   * @retval string
   *  the content issued by the HTTP POST request
   * @exception Exception the request failed
   * @example 4.post.php
   */
  public function &post($url, $postfields)
  {
    $post = '';
    foreach ($postfields as $parameter => $value)
      $post .= $parameter.'='.$value.'&';
    $post = substr($post, 0, -1);
    curl_setopt($this->_ch, CURLOPT_POST, true);
    curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $post);
    return $this->exec($url);
  }

  /* #################### CONFIGURATION ##################################### */

  /** Set a file where the output will be redirected
   *
   * @param string $filename
   *  the filename of the file to write
   * @exception Exception the file could not be open
   * @example 2.file.php
   */
  public function setFile($filename)
  {
    if (!($this->_fileHandle = fopen($filename, 'w')))
      throw new Exception(ERR_FILE_OPEN);
    curl_setopt($this->_ch, CURLOPT_FILE, $this->_fileHandle);
  }

  /** Stop redirecting the output to a file
   *
   * @param bool $critical_error
   *  if true, the function will consider that a failure on close is critical
   * @exception Exception the file handle could not be closed\
   * @example 2.file.php
   */
  public function unsetFile($critical_error = false)
  {
    curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
    $this->closeFile($critical_error);
  }

  /** Set a specific user-agent instead of the default one
   *
   * @param string $userAgent
   *  the user-agent to use
   */
  public function setUserAgent($userAgent)
  {
    $this->_userAgent = $userAgent;
  }

  /** Set a delay to wait between each request
   *
   * @param float $delay
   *  the number of seconds to wait between each download
   * @throw Exception the specified delay is not numeric
   * @example 3.delay.php
   */
  public function setDelay($delay)
  {
    if (!is_numeric($delay))
      throw new Exception(self::ERR_DELAY_NUM);
    $this->_delay = $delay;
  }

  /** Set a cookie file
   *
   * @param string $filename
   *  the name of the file to use as a cookie
   */
  public function setCookieFile($filename)
  {
    curl_setopt($this->_ch, CURLOPT_COOKIEFILE, $filename);
    curl_setopt($this->_ch, CURLOPT_COOKIEJAR, $filename);
  }

  /* #################### INTERN MECHANISMS ################################# */

  /** Pause the execution for a given delay before performing the request
   *
   * @param string $url
   *  the url to get the content from
   * @retval string
   *  the content issued by the HTTP GET request
   * @exception Exception the request failed
   */
  private function &exec($url)
  {
    curl_setopt($this->_ch, CURLOPT_URL, $url);
    while (microtime(true) - $this->_lastTime < $this->_delay);
    if (!($ret = curl_exec($this->_ch)))
      throw new Exception(self::ERR_EXEC);
    $this->_lastTime = microtime(true);
    return $ret;
  }

  /** Close the file handle if it is open
   *
   * @param bool $critical_error
   *  if true, the function will consider that a failure on close is critical
   * @exception Exception the file handle could not be closed
   */
  private function closeFile($critical_error = false)
  {
    if ($this->_fileHandle != null &&
	!fclose($this->_fileHandle) &&
	$critical_error)
      throw new Exception(self::ERR_FILE_CLOSE);
    $this->_fileHandle = null;
  }

  /* #################### INTERN INITIALISATIONS ############################ */

  /** Initialise the properties of the object
   */
  private function initProperties()
  {
    $this->_ch = null;
    $this->_delay = self::DEFAULT_DELAY;
    $this->_fileHandle = null;
    $this->_lastTime = 0;
    $this->_userAgent = self::DEFAULT_USER_AGENT;
  }

  /** Initialise a cURL handle
   *
   * @throw Exception cURL extension is not loaded
   * @throw Exception unable to create a cURL handle
   */
  private function initHandle()
  {
    if (!extension_loaded('curl'))
      throw new Exception(self::ERR_LOADED);
    if (!($this->_ch = curl_init()))
      throw new Exception(self::ERR_INIT);
  }

  /** Initialise the properties of the cURL handle
   */
  private function configureHandle()
  {
    curl_setopt($this->_ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
  }

  /* #################### MESSAGES ########################################## */

  const ERR_EXEC = 'the request failed';
  const ERR_LOADED = 'cURL extension is not loaded';
  const ERR_INIT = 'a valid cUrl handle could not be created';
  const ERR_DELAY_NUM = 'delay must be a number';
  const ERR_FILE_CLOSE = 'the file could not be closed';
  const ERR_FILE_OPEN = 'the file could not be open';

  /* #################### CONSTANTS ######################################### */

  const DEFAULT_USER_AGENT = 'YourBot/1.0 (+http://yourwebsite.com)';
  const DEFAULT_DELAY = 0;

  /* #################### PROPERTIES ######################################## */

  private $_ch; /** cURL handle */
  private $_delay; /** delay between each request */
  private $_fileHandle; /** handle for outputing in a file management */
  private $_lastTime; /** stores the last time request */
  private $_userAgent; /** current user agent */
};