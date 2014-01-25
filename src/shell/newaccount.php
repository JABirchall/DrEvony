<?php
/**
 * YaBOB
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    YaBOB
 * @package     YaBOB_Shell
 * @copyright   Copyright (c) 2013 YaBOB. (https://github.com/rootindex/abbob)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * YaBOB NewEvonyAccount Shell Script
 *
 * @category    YaBOB
 * @package     YaBOB_Shell
 * @author      YaBOB Core Team <core@undisclosed>
 */
class YaBOB_Shell_Newaccount extends YaBOB_Shell_Abstract
{
    /**
     * Run script
     *
     */
    public function run()
    {
        $king = $this->getArg('king');
        $pass = $this->getArg('pass');
        $email = $this->getArg('email');

        if($king && $pass && $email){
            $this->_new_account($king, $pass, $email, $this->getArg('sex'), $this->getArg('server'));
        }else{
            echo $this->usageHelp();
        }
    }

    protected function _new_account($king, $pass, $email, $sex=false, $server=false){
        $url = 'aHR0cDovL3d3dy5ldm9ueS5jb20vaW5kZXguZG8/UGFnZU1vZHVsZT1MZHBBY3Rpb24mbWV0aG9kPVVzZXJzUmVnTmV3JnJlZmVyX3VybD0=';

        $sex = !$sex ? '0' : $sex ;
        $server = !$server ? 'na1' : $server;

        $fields = array(
            'king'=>urlencode($king),
            'username'=>urlencode($email),
            'pwd'=>urlencode($pass),
            'sex'=>urlencode($sex),
            'pwd2'=>urlencode($pass),
        );
        
        $fields_string = '';
        foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
        rtrim($fields_string,'&');
       
        //open connection
		$ch = curl_init();
		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,base64_decode($url));
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 

		//execute post
		$result = curl_exec($ch);
		
		// lazy preg and check returned if error message show error.
		$preg = preg_match('/s.html(.*)&adv/', (string)$result, $return);
		if(!$preg){
			$error = preg_match('/alert\(\"(.*)\"\)/', (string)$result, $error_return);
			echo $error_return[1] . "\n";
			exit();
		}
		
		echo "Server: http://{$server}.evony.com/{$return[1]}\n"
			 ."Login: {$email}\n"
			 ."Pass: {$pass}\n";
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f newaccount.php -- [options]

  --king <kingname>             King name on game server
  --pass <password>             Password for global login
  --sex <user_sex>              Sex Male 0 / Female 1 (0 default)
  --email <email_address>       Email address global login
  --server <server>             Game Server (na1 default)

  help                          This help


USAGE;
    }


}

$shell = new YaBOB_Shell_Newaccount();
$shell->run();
