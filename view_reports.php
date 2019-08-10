<html>
    <body> 
        <center>   
            <h1><i> Milllie's Musical Emporium <br></i></h1></center>
		<center><h2><i>The Countries Leading Suppliers of Musical instruments and Associated Media</i></h2></Center>
	
        <center>
            <button><a href="Index.php">Home</a> </button>
            <button><a href="login.php">Logout</a></button>
            <button><a href="Index.php">Available Products</a></button>
            <button><a href="login.php">Back</a></button>
        </center>
        
        <center>
            <div><p>List Of Purchased Products!!</p></div>

<?php
	session_start();
	echo 'Your ID:'.$_SESSION['ID'];
	require_once("DataAccess.php");
//session start

	try {
        
    $conn = (new DataAccess())->GetOracleConnection();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
        
       //get user data into variables
        $purchase_id=  $_REQUEST['purchase_id']; 
        $prod_id=$_REQUEST['prod_id']; 
        $purchase_date=(new DateTime($_REQUEST['purchase_date']))->format('Y-M-d'); 
		$store_id=$_REQUEST['store_id'];
        $quantity=$_REQUEST['quantity']; 
		$total_price=$_REQUEST['total_price'];
      //format date into ORACLE format

        //validate data
        if(empty($prod_id)){
            echo "<script>alert('Please enter missing data values !!');</script>";
        } else { 
        
            //get button clicked by user -- get operation//
            if(isset($_REQUEST['btnsave'])){ //SAVE BUTTON

              
                    //execute using exec
                    $sql  = "INSERT INTO purchase (purchase_id,prod_id,purchase_date,store_id,quantity,total_price) VALUES (purchase_seq.nextval,'$cust_id','$bran_id','$prod_id','$pur_date','$quantity','$total_price')"; 
                    $count= $conn->exec($sql);
                //END METHOD 2
                    
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Record saved successfully !!" : "Record saving failed !!";
                    echo "<script>alert('$msg');</script>";

            } elseif (isset($_REQUEST['btnupdate'])){ //UPDATE BUTTON
                
              
                     //execute using exec
                     $sql  = "UPDATE Purchase SET PURCHASE_ID='$purchase_id',BRANCH_ID='$bran_id',PRODUCT_ID='$prod_id',PURCHASE_DATE='$pur_date',quantity='$quantity',total_price='$purchase_price' WHERE CUSTOMER_ID='$id'";
                     $count= $conn->exec($sql);
                //END METHOD 2
                
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Record updated successfully !!" : "Record updating failed !!";
                    echo "<script>alert('$msg');</script>";

            } elseif (isset($_REQUEST['btndelete'])){ //DELETE BUTTON
                
                //METHOD 1 - USING PARAMETERS
                    $sql  = "DELETE FROM Customer WHERE CUSTOMER_ID=?"; 
                    $types = array('integer');
                    $values = Array($id);

                    //execute
                    $stmt = $conn->prepare($sql, $types, MDB2_PREPARE_MANIP);
                    $count= $stmt->execute($values);
                //END METHOD 1
                
                //METHOD 2 - USING CONCATENATION
//                    //execute using exec
//                    $sql  = "DELETE FROM STUDENTS WHERE STUDENT_ID='$id'";
//                    $count = $conn->exec($sql);
                //END METHOD 2
                    
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Record deleted successfully !!" : "Record deleting failed !!";
                    echo "<script>alert('$msg');</script>";
            }
            
        }
    
    }
    
   
    /* SELECT AND DISPLAY DATA */  //NB: - Field Names are case sensitive
    $sql  = "SELECT * FROM Purchase ORDER BY PURCHASE_ID ASC"; 
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
        echo "<tr style='background-color:lightcyan;'>";   //TABBLE HEADERS
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
                    $colvalue = (strtoupper($colname)=='PURCHASE_DATE')? $purchase_date_format : $row[$colname];
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