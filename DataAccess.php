<?php


class DataAccess {
    //put your code here

    function GetOracleConnection(){
        try{
            //database connection settings WITH Pear Oracle
	    ini_set('include_path', 'C:/wamp64/bin/php/php5.6.25/pear');
            require_once 'MDB2.php';

            //database connection settings WITH Pear Oracle
	    $dsn = array(
		'phptype' => 'oci8',
		'hostspec' => '10.0.0.57:1521/trn.bac.ac.bw',
		'username' => 'btkgakge',
		'password' => 'a1m4SUCC3$$',
	    );

	    $conn = MDB2::factory($dsn);

            return $conn;  
            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    
    //----
}
