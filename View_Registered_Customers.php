<html>
	<body> 
		<center><h1><i> Milllie's Musical Emporium <br></i></h1></center>
		<center><h2><i>The Countries Leading Suppliers of Musical instruments and Associated Media</i></h2></center>
	
			<center>
				<button><a href="Index.php">Home</a></button>
				<button><a href="Login.php">Login</a></button>
				<button><a href="Registration.php"> Registration</a></button>
			</center>
				
			<center>
				<p>Milllie's Musical Emporium is a carefully crafted online musical instrument provider, that provides amazing and unique musical instruments such as and not limited to: <br>
				Hamer Sunburst,Gemeinhardt 2SP Flute with Straight Headjoint,Pearl Drum Set Satin Black Burst <br>
				and we offer everything required to make your own high class orchestra. <br></p>
			</center>


			<center><h3>VIEW REGISTERED CUSTOMERS</h3>

<?php
session_start();
require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
 /* SELECT AND DISPLAY DATA */  //NB: - Field Names are case sensitive
    $sql  = "SELECT * FROM Customer ORDER BY CUSTOMER_ID ASC"; 
    $result = $conn->query($sql);
    
    // check if the query was executed properly
    if (PEAR::isError($result)) {
        die ($result->getMessage());
    }
 
    // fetch all and free the result and disconnect
    $arr = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
    $result->free();
    $conn->disconnect();
    
    $row_count = count($arr); //get number of rows returned
    
    if($row_count > 0 ){ //only display when rows exist
    
        //extract column names / keys
        $row=$arr[0]; //first row
        $keys = array_keys($row);

        //load html table data
        echo "<table border='1' cellspacing='0' cellpadding='1'>";

        //create header row
        echo "<tr style='background-color:lightcyan;'>";   //TABLE HEADERS
            for($j=0; $j<count($keys);$j++){
                $colname = strtoupper($keys[$j]);
                echo "<td> $colname </td>"; 
            }
        echo "</tr>";

        //add rows of data
        foreach($arr as $row){
          
            echo "<tr>";
                for($j=0; $j<count($keys);$j++){
                    $colname = $keys[$j];
                    $colvalue = (strtoupper($colname)=='DOB')? $dob_format : $row[$colname];
                    echo "<td> $colvalue </td>";
                }
            echo "</tr>";
        }

        echo '</table>';
   
    } //closing if($row_count>0)
} catch (Exception $ex) {
    $msg=$ex->getMessage();
    echo $msg;
    echo "<script>alert('$msg');</script>";
    exit();
}
?>
                
                
            </center>
    </body>
</html>