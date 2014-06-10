<?php
/*
    socks5 class
    by sergey krivoy(hide@address.com), 2004
 
    methods:
        socks5($ip, $port) - constructor, $ip and $port - ip and port of anonymous socks5 proxy
        connect($host, $port) - make a connection to $host:$port thru socks5 proxy. $host may be hostname or ip address
        send($buffer, $length = 0) - send $buffer of $length bytes to connection. if $length not set - $length=strlen($buffer)
*/
    class socks5 {
        var $socket;
        var $connected;
        var $debug;
    
        function socks5($ip, $port) {
            if ($this->socket = pfsockopen($ip, (int)$port, $errno, $errstr)) {
                $buf["send"] = pack("C3", 0x05, 0x01, 0x00);
                fwrite($this->socket, $buf["send"]);
                $buf["recv"] = "";
                while ($buffer = fread($this->socket, 1024)) {
                    $buf["recv"] .= $buffer;
                }
                $responce = unpack("Cversion/Cmethod", $buf["recv"]);
                if ($responce["version"] == 0x05 and $responce["method"] == 0x00) {
                    return true;
                }
                fclose($this->socket);
            }
            return false;
        }
        
        function connect($host, $port) {
            if ($this->socket) {
                if (ip2long($host) == -1) {
                    $buf["send"] = pack("C5", 0x05, 0x01, 0x00, 0x03, strlen($host)).$host.pack("n", $port);
                } else {
                    $buf["send"] = pack("C4Nn", 0x05, 0x01, 0x00, 0x01, ip2long(gethostbyname($host)), $port);
                }
                fwrite($this->socket, $buf["send"]);
                $buf["recv"] = "";
                while ($buffer = fread($this->socket, 1024)) {
                    $buf["recv"] .= $buffer;
                }
                $responce = unpack("Cversion/Cresult/Creg/Ctype/Lip/Sport", $buf["recv"]);
                if ($responce["version"] == 0x05 and $responce["result"] == 0x00) {
                    $this->connected = true;
                    return true;
                }
            }
            $this->connected = false;
            return false;
        }
        
        function send($buffer, $length = 0) {
            if ($length = 0) {
                $length = strlen($buffer);
            }
            if ($this->socket and $this->connected) {
                fwrite($this->socket, $buffer, $length);
                while ($recieved = fread($this->socket, 1024)) {
                    $output .= $recieved;
                }
                return $output;
            }
            return false;
        }
    }
?>