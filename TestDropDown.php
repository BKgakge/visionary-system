
<html>
    <center>
        
        <h3><font color=""> Test DropDown</font></h3>
        
        <form name='TestDropDown' action='TestDropDown.php' method='post' target='_blank'>

<?php
require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
    
    /* get dropdown data */  //NB: - Field Names are case sensitive
    $sql  = "SELECT * FROM Customer";
    $result = $conn->query($sql);
	$sql  = "SELECT * FROM Product";
    $result1 = $conn->query($sql);
    
    // check if the query was executed properly
    if (PEAR::isError($result)) {
        die ($result->getMessage());
	}elseif (PEAR::isError($result1)) {
		die ($result1->getMessage());
    }
	
    // fetch all and free the result and disconnect
    $arrdata = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
    $result->free();
    $arrproducts = $result1->fetchAll(MDB2_FETCHMODE_ASSOC);
    $result1->free();
    $conn->disconnect();
	
	?>
	
	Report ID
	<input type ='text' name ='reportid'><br/>
	Customer ID
	
	<?php
    //-----load dropdown---------
	$row_count = count($arrdata);
	$arrkeys = array_keys($arrdata[0]);
	echo "<select name='customerid'>";
		echo "<option value='0'>Select...</option>";
		if( $row_count > 0 ) { //only display when there is data
			foreach ($arrdata as $row){
				$keyidname=$arrkeys[0];
				$keyvalname=$arrkeys[1];
				$keyid = $row["$keyidname"];  //$keyid = $row['report_id'];
				$keyval = $row["$keyvalname"]; //$keyval = $row['report_name'];
				echo "<option value=$keyid>$keyval</option>";
			}
		}
	echo "</select>";
	
	?>
	<br/>
	Quantity
	<input type ='text' name ='quantity'><br/>
	
	 product 
	<?php
	//-----load dropdown---------
	$row_count = count($arrproducts);
	$arrkeys = array_keys($arrproducts[0]);
	echo "<select name='productid'>";
		echo "<option value='0'>Select...</option>";
		if( $row_count > 0 ) { //only display when there is data
			foreach ($arrproducts as $row){
				$keyidname=$arrkeys[0];
				$keyvalname=$arrkeys[1];
				$keyid = $row["$keyidname"]; 
				$keyval = $row["$keyvalname"]; 
				echo "<option value=$keyid>$keyval</option>";
			}
		}
	echo "</select>";
	
	?>
	
	<br/>
	Total Amount
	<input type ='text' name ='amount'><br/>
     
    
    <?php
    //-----------------

} catch (Exception $ex) {
    $msg=$ex->getMessage();
    echo $msg;
    echo "<script>alert('$msg');</script>";
    exit();
}

?>
        
        </form>
            
    </center>

</html>