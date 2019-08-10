<html>
    <body>

        <center><h1><i> Milllie's Musical Emporium <br></i></h1></center>
        <center><h2><i>The Countries Leading Suppliers of Musical instruments and Associated Media</i></h2></center>
  
        <center>
            <button><a href="Index.php">Home</a></button>
            <button><a href="Admin_Area.php">Back</a></button>
            <button><a href="Index.php">Logout</a></button>
        <center>

        <h2>PRODUCT FACILITY</h2><br>

        <form name='products' action='Manage_products.php' method='post'>

            <!--PRODUCT ID:<br/>
                <input type='text' name='prod_id '/><br/>-->
            PRODUCT NAME  :<br/>
                <input type='text' name='prod_name'/><br/>
            PRODUCT TYPE:<br/>
                <input type='text' name='prod_type'/><br/>
            PRODUCT DECRIPTION  :<br/>
                <input type='text' name='prod_desc'/><br/>
            PRODUCT PRICE  :<br/>
                <input type='text' name='prod_price'/><br/>
                <br/>
            MOVE TO BRANCH:<br>
                <select name='store_name' ><br/>
                    <option value='0'>Select Branch</option>
                    <option value='MME_GAMECITY'>MME_GAMECITY</option>
                    <option value='MME_RIVERWALK'>MME_RIVERWALK</option>
                    <option value='MME_AIRPORTJUNCTION'>MME_AIRPORTJUNCTION</option>
                </select><br><br>
            
                <input type='submit' name='btnSave' value='Save'/>
                <input type='submit' name='btnupdate' value='Update'/>
                <input type='submit' name='btnmove' value='Move Product'/>
                <input type='submit' name='btndelete' value='Delete'/>
        </form>


<?php

require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
    
        //get user data into variables
        //$prod_id=$_REQUEST['product_seq.nextval']; 
        $prod_name=$_REQUEST['prod_name']; 
		$prod_desc=$_REQUEST['prod_desc']; 
		$prod_type=$_REQUEST['prod_type']; 
        $store_name=$_REQUEST['store_name'];
        $prod_price=$_REQUEST['prod_price'];
		
        //validate data
        if(empty($prod_name)){
            echo "<script>alert('Please enter missing data values !!');</script>";
        } else { 
        
            //get button clicked by user -- get operation//
            if(isset($_REQUEST['btnSave'])){ // RECORD BUTTON

                  //execute using exec
                    $sql  = "INSERT INTO PRODUCT (PRODUCT_ID, PRODUCT_NAME, PRODUCT_DESCRIPTION, PRODUCT_TYPE, PRODUCT_LOCATION, PRODUCT_PRICE) VALUES (product_seq.nextval,'$prod_name','$prod_desc','$prod_type','$store_name',$prod_price)"; 
                    $count= $conn->exec($sql);
                
                   //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Record saved successfully !!" : "Record saving failed !!";
                    echo "<script>alert('$msg');</script>";
                
            } elseif (isset($_REQUEST['btnmove'])){ //MOVE BUTTON

                    //execute using exec
                    $sql  = "UPDATE PRODUCT SET PRODUCT_LOCATION='$store_name' WHERE PRODUCT_NAME='$prod_name'"; 
                    $count= $conn->exec($sql);
                
                   //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "PRODUCT SUCESSFULLY MOVED TO A DIFFERENT BRANCH !!" : "MOVING THE PRODUCT FAILED !!";
                    echo "<script>alert('$msg');</script>";

              } elseif (isset($_REQUEST['btnupdate'])){ //UPDATE BUTTON
                //execute using exec
                     $sql  = "UPDATE PRODUCT SET PRODUCT_DESCRIPTION='$prod_desc',PRODUCT_TYPE='$prod_type',PRODUCT_LOCATION='$store_name',PRODUCT_PRICE='$prod_price' WHERE PRODUCT_NAME='$prod_name'"; 
                    $count= $conn->exec($sql);
                
                   //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "PRODUCT SUCESSFULLY UPDATED PRODUCT INFORMATION!!" : "UPDATING THE PRODUCT INFORMATION FAILED !!";
                    echo "<script>alert('$msg');</script>";
                
            } elseif (isset($_REQUEST['btndelete'])){ //DELETE BUTTON

                    $sql  = "DELETE FROM PRODUCT WHERE PRODUCT_NAME='$prod_name'"; 
                    $count= $conn->exec($sql);
                
                   //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Product deleted successfully !!" : "Product deleting failed !!";
                    echo "<script>alert('$msg');</script>";
            }
            
        }
    
	
    
    
   
}  echo "</table>";


} catch (Exception $ex) {
    $msg=$ex->getMessage();
    echo $msg;
    echo "<script>alert('$msg');</script>";
    exit();
}
?>
    <?php
session_start();
require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
 /* SELECT AND DISPLAY DATA */  //NB: - Field Names are case sensitive
    $sql  = "SELECT * FROM PRODUCT ORDER BY PRODUCT_ID ASC"; 
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
	    </body>
</html>
	