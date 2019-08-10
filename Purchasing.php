<html>
	<body> 
		<center><h1><i> MME Supplies<br></i></h1></center>
		<center><h2><i>Text</i></h2></center>
	
			<center>
				<button><a href="Index.php">Home</a></button>
			</center>


			<center><h3>AVAILABLE PRODUCTS</h3>

<?php
session_start();
require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
 /* SELECT AND DISPLAY DATA */  //NB: - Field Names are case sensitive
    $sql  = "SELECT * FROM Products ORDER BY ProductID ASC"; 
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
<html>

<form name='Purchasing' action='Purchasing.php' method='post'>

     ProductID:<br/>
    <input type='text' name='prodid'/><br/>
	
    Product Name:<br/>
    <input type='text' name='pname'/><br/>
	
    Quantity:<br/>
    <input type='text' name='Qua'/><br/>
	
	BranchName:<br/>       
    <select name='bname'><br/>
		<option value='0'>Select...</option>
        <option value='MME Gamecity'>MME Gamecity</option>
        <option value='MME RiverWalk'>MME RiverWalk</option>
    </select><br/><br/>
	
    <input type='submit' name='btnsave'   value='Save'  />
	

</form>
        
<?php

require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
        
        //get user data into variables
		$prodid=$_REQUEST['prodid']; 
        $pname=$_REQUEST['pname']; 
        $Qua=$_REQUEST['Qua']; 
        $bname=$_REQUEST['bname'];
//        var_dump($_REQUEST);
//        var_dump($_POST);
        //validate data
        if(empty($pname)){
            echo "<script>alert('Please enter all information needed !!');</script>";
        } else { 
        
            //get button clicked by user -- get operation//
            if(isset($_REQUEST['btnsave'])){ //SAVE BUTTON
                
                if(empty($pname) || empty($Qua)|| empty($bname)){ //validate all other fields for INSERT
                    echo "<script>alert('missing field');</script>";
                    exit();
                }
                //METHOD 2 - USING CONCATENATION
                    //execute using exec
                    $sql  = "INSERT INTO Purchases (PurchaseID,ProductID,ProductName,Quantity,BranchName) VALUES (Purchases_seq.Nextval,$prodid,'$pname',$Qua,'$bname')"; //'$id'  //sq_stud_id.nextval //sequence usage
                    $count= $conn->exec($sql);
                //END METHOD 2
                    
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Purchase successfull !!" : "Purchase failed !!";
                    echo "<script>alert('$msg');</script>";

            } elseif (isset($_REQUEST['btnupdate'])){ //UPDATE BUTTON
                
                if(empty($pname) || empty($Qua) || empty($bname)){ //validate all other fields for UPDATE
                    echo "<script>alert('Please enter missing data values !!');</script>";
                    exit();
                }
               
                //METHOD 2 - USING CONCATENATION
                     //execute using exec
                     $sql  = "UPDATE Purchases SET PurchaseID=Purchases_seq.Nextval,ProductID=$prodid,ProductName='$pname',Quantity='$Qua,'BranchName='$bname' WHERE PurchaseID=Purchase_seq.Nextval";
                     $count= $conn->exec($sql);
                //END METHOD 2
                
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Record updated successfully !!" : "Record updating failed !!";
                    echo "<script>alert('$msg');</script>";

                    //execute
                    $stmt = $conn->prepare($sql, $types, MDB2_PREPARE_RESULT);
                    $result= $stmt->execute($values);
                //END METHOD 1
                
                    // check if the query was executed properly
                    if (PEAR::isError($result)) {
                        die ($result->getMessage()); //getDebugInfo()
                    }

                    // fetch all and free the result and disconnect
                    $arr = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
                    $result->free();
                    $conn->disconnect();
                    
                    $id= $arr[0]["id"];
                    $proid = $arr[0]["proid"];
                    $pname= $arr[0]["pname"];
					$Qua= $arr[0]["Qua"];
					$bname= $arr[0]["bname"];
				
                    
                    echo "<script>document.getElementsByName('id')[0].value    = '$id';</script>";
                    echo "<script>document.getElementsByName('fname')[0].value = '$proid';</script>";
                    echo "<script>document.getElementsByName('sname')[0].value = '$pname';</script>";
					echo "<script>document.getElementsByName('pword')[0].value    = '$Qua';</script>";
                    echo "<script>document.getElementsByName('dob')[0].value = '$bname';</script>";
                   
            }
            
        }
    
    }
    
   
    /* SELECT AND DISPLAY DATA */  //NB: - Field Names are case sensitive
    $sql  = "SELECT * FROM Purchases ORDER BY PurchaseID ASC"; 
    $result = $conn->query($sql);
    
    // check if the query was executed properly
    if (PEAR::isError($result)) {
        die ($result->getMessage());
    }
 
    // fetch all and free the result and disconnect
    $arr = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
    $result->free();
    $conn->disconnect();
    
    //var_dump($arr);
    //print_r($arr);
    
    $row_count = count($arr); //get number of rows returned
    
    if($row_count > 0 ){ //only display when rows exist
    
        //extract column names / keys
        $row=$arr[0]; //first row
        $keys = array_keys($row);

        //load html table data
        echo "<table border='1' cellspacing='0' cellpadding='1'>";

        //create header row
        echo "<tr bgcolor='lightcyan'>";   //TABLE HEADERS // style='background-color:lightcyan;' <font color='blue'>
            for($j=0; $j<count($keys);$j++){
                $colname = strtoupper($keys[$j]);
                echo "<td><font color='blue'> $colname </font></td>"; 
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
</html>

