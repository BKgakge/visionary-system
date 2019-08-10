<html>
    <body> 
        <center><h1><i> Milllie's Musical Emporium <br></i></h1></center>
		<center><h2><i>The Countries Leading Suppliers of Musical instruments and Associated Media</i></h2></center>
	
        <center>
            <button><a href="Index.php">Home</a></button>
            <button><a href="Login.php">Login</a></button>
        </center>
				
        <center>
            <p>Milllie's Musical Emporium is a carefully crafted online musical instrument provider, that provides amazing and unique musical instruments such as and not limited to: <br>
            Hamer Sunburst,Gemeinhardt 2SP Flute with Straight Headjoint,Pearl Drum Set Satin Black Burst <br>
            and we offer everything required to make your own high class orchestra. <br></p>
        </center>

        <center>
            <h3>PURCHASE PRODUCTS</h3>
        </center>
        
        <center>
<?php
session_start(); 
	echo 'Welcome:'.$_SESSION['ID'];
	require_once("DataAccess.php");
//session start

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
                <br>
                    <div>
	                   <form method= "post" action="Customer_purchase.php">

                            <center><b>PLEASE PROVIDE NEEDED DETAILS!!</b ></center>
                                <br><br>
                           
                                    <table>				
                                        <tr>
                                            <td>
                                            
                                            </td>
						
                                            <td>
                                                                       </td>
                                        </tr>
                                                <center><?php
		                                                  //prepare data
		                                                  $sel_product_id    = isset($sel_product_id)   ? $sel_product_id    : ''; 
		                                                  $sel_product_name  = isset($sel_product_name) ? $sel_product_name  : ''; 
		                                                  $sel_product_price = isset($sel_product_price)? $sel_product_price : ''; 
                                                ?>
                                                                         <?php 

// SELECT A PRODUCT AND PUT DATA IN TETBOXES
    
if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
        
        //--EXTRACT REPORT_ID FROM PREVIEW BUTTON : [rpt_id=1]
        $postkeys = array_keys($_POST);
        foreach($postkeys as $arrkey){
            if(strstr($arrkey, "=")){   $p_id= explode("=", $arrkey)[1];  }
        }
     
			if( !empty($p_id) ){ 
			
			//extract data for display
			foreach($arr_products as $rowdata){
				if($rowdata['product_id'] == $p_id){
					$sel_product_id=$rowdata['product_id']; 
					$sel_product_name=$rowdata['product_name']; 
					$sel_product_price=$rowdata['price']; 
				}
			}

		}
}
?>
	
                                                    productID:<br/>
                                                <input type='text'    value="<?php echo $sel_product_id ?>"  name='numID' required placeholder="Enter product id"/><br/>
	
                                                    product_name  :<br/>
                                                <input type='text' name='productname' value="<?php echo $sel_product_name ?>" /><br/>

                                                    price   :<br/>
                                                <input type='text' name='price'  value="<?php echo $sel_product_price ?>" /><br/>
	
	                                               Quantity :<br/>
                                                <input type='text' name='quantity'/><br/>

                                                    Total  :<br/>
                                                <input type='text' name='Total'/><br/></center>

                                        <tr>
                                            <td>
                                                
                                            </td>
						
                                            <td>
                                                
                                            </td>
                                        </tr>
                                               <center>
                                                <b>BRANCH NAME</b><br>
                                                <select name='b_name' ><br/>
								                    <option value='0'>Select...</option>
								                    <option value='MME_GAMECITY'>MME_GAMECITY</option>
								                    <option value='MME_RIVERWALK'>MME_RIVERWALK</option>
                                                    <option value='MME_AIRPORTJUNCTION'>MME_AIRPORTJUNCTION</option>
                                                </select>
                                            </center><br><br>
                                        
					                                <center><button type="submit" name='btnbuy'>PURCHASE</button></center>
                                        <tr>
                                            <td>
                                                <a href ="http://sechaba:8080/cis15-014/BRIAN_KGAKGE_PARTA/store.php">
                                                    <button type = "button">
                                                        RETURN
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
					
                                    </table>
                           </form>
			
                    </div>

<?php
	//session_start();
	//echo $_SESSION['$F_NAME'];
	require_once("DataAccess.php");
        
    $conn = (new DataAccess())->GetOracleConnection();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
        
        //get user data into variables
		
												

        $CUSTOMERID=$_SESSION['ID'];
        $PRODUCTID=$_REQUEST['numID'];
        $BRANCHNAME=$_REQUEST['b_name'];
        //$TRASACTIONID=$_REQUEST['']; 
        $TRASACTION_TYPE= 'Purchase'; 
	   //$COST=$_REQUEST['product_cost']; 
        $DATE=date("Y.m.d"); 
        if(isset($_REQUEST['btnbuy'])){ //SAVE BUTTON
													
													
           /* $costsql="SELECT PRODUCT_PRICE FROM PRODUCT WHERE PRODUCT_ID = $PRODUCTID";
            $values=$conn->exec($costsql);
	/* 		//execute
                    $stmt = $conn->prepare($sql, $types, MDB2_PREPARE_RESULT);
                    $result= $stmt->execute($values);
                //END METHOD 1
			
     // fetch all and free the result and disconnect
                    $arr = $productcost->fetchAll(MDB2_FETCHMODE_ASSOC);
                    $productcost->free();
                    $conn->disconnect();
					
					                //METHOD 1 - USING PARAMETERS*/
                    /*$custsql="SELECT FIRST_NAME FROM Customer WHERE ACCOUNT_USERNAME = $CUSTOMERID";
                    $types = array('integer');
                    $values = Array($CUSTOMERID);

                    //execute
                    $stmt = $conn->prepare($custsql, $types, MDB2_PREPARE_RESULT);
                    $result= $stmt->execute($values);*/
                /*//END METHOD 1
                
                    // check if the query was executed properly
                    if (PEAR::isError($result)) {
                        die ($result->getMessage()); //getDebugInfo()
                    }

                    // fetch all and free the result and disconnect
                    $arr = $result->fetchAll(MDB2_FETCHMODE_ASSOC);
                    $result->free();
                    $conn->disconnect();
                    
                    $id    = $arr[0]["UserID"]; */
					
            /*$custsql="SELECT FIRST_NAME FROM Customer WHERE ACCOUNT_USERNAME = $CUSTOMERID";
            $custsid=$conn->exec($custsql);
            
            $branchsql="SELECT BRANCH_ID FROM BRANCH WHERE BRANCH_NAME = $BRANCHNAME";
            $branchid=$conn->exec($branchsql);*/
            
            $sql  = "INSERT INTO Purchase(PURCHASE_ID,CUSTOMER_ID,BRANCH_ID,PRODUCT_ID,PURCHASE_DATE,QUANTITY,TOTAL_PRICE)
            VALUES (purchase_seq.nextval,2,3,1,'$DATE',1,5000)"; 
            $count= $conn->exec($sql);
															
            if (PEAR::isError($count)) {
                die ($count->getUserInfo()); //getDebugInfo()); //
            }
																
            //confirm              
            $msg = ($count>0) ? "Transaction successfully !!" : "Transaction failed !!";
            echo "<script>alert('$msg');</script>";	
            
            //Display
            try {
        
    $conn = (new DataAccess())->GetOracleConnection();
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
            //End Display
            
													
        }
													
        /*if(isset($_REQUEST['btnbuy'])){ //SAVE BUTTON
													
		//METHOD 1 - USING PARAMETERS
            $sqlp  = "DELETE FROM PRODUCT WHERE PRODUCT_ID=?"; 
            $types = array('integer');
            $values = Array($PRODUCTID);

        //execute
            $stmt = $conn->prepare($sqlp, $types, MDB2_PREPARE_MANIP);
            $countt= $stmt->execute($values);
        //END METHOD 1
																
	
																	
        //check errors
            if (PEAR::isError($countt)) {
                die ($countt->getUserInfo()); //getDebugInfo()); //
            }
        //confirm
            $msg = ($countt>0) ? "Update successfully !!" : "Update failed !!";
            echo "<script>alert('$msg');</script>";

            header("Location:cpurchase.php");
        }*/
        
	   }	
?>	
	
	
 
	</body>
</html>

