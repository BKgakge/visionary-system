
<html>
    <body> 
	
		<center><h1><i> Milllie's Musical Emporium <br></i></h1></center>        
		<center><h2><i>The Countries Leading Suppliers of Musical instruments and Associated Media</i></h2></Center>
               
			<center><button><a href="Index.php">Home</a></button>
			<button> <a href="Registration.php">Registration</a></button></center>

			<center><div><p>PLEASE LOGIN BELOW!!</p></div></center>
                    
        
		<center><form action="Login.php" method="post">
			<fieldset>
				<legend>LOGIN HERE:</legend>
				<table>
					<tr>
						<td><label><b>User_Name</b></label></td>
						<td><input type="text" placeholder="ID" name="ID" required ></td> 
					</tr>
					<tr>
						<td><label><b>Password</b></label></td>
						<td><input type="password" placeholder="Enter Password" name="password" required ></td> 
					</tr>
					<tr>
						<td colspan="2">
							<b>User Type</b>          
							<select name='usertype' ><br/>
								<option value='0'>Select...</option>
								<option value='Admin'>Admin</option>
								<option value='Cust'>Customer</option>
							</select><br/>
						</td>
					</tr>
					<tr>
						<td><button type="reset" name="btncancel" style="float:left;" >Cancel</button></td> <br>
						<td><button type="submit" name="btnlogin" style="float:right;">Login</button></td>
					</tr>
				</table>
			</fieldset>
		</form></center>

	</body>	
</html>

<?php
	session_start();
	require_once("DataAccess.php");

	try {
        
		$conn = (new DataAccess())->GetOracleConnection();

			if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
        
			//get user data into variables
			$ID=$_REQUEST['ID']; 
			$password=$_REQUEST['password']; 
			$usertype=$_REQUEST['usertype']; 
			$_SESSION['ID'] = $ID;
/*                 
                $F_NAME  = "SELECT FIRST_NAME FROM Customer WHERE ACCOUNT_USERNAME='$ID'";
                $count= $conn->exec($sql);
                //END METHOD 2 */
			//validate data
			if(empty($ID) || empty($password) || $usertype=='0' ){
				echo "<script>alert('Please enter missing data values !!');</script>";
			} else { 
			
				//get button clicked by user -- get operation//
				if(isset($_REQUEST['btnlogin'])){ //LOGIN BUTTON
                    
                    //determine TABLENAME AND PAGENAME based on selected USERTYPE
					$sql="";
                    $tablename = "";
                    $pagename  = "";
                    if    ($usertype == 'Admin'){
                	$sql="SELECT * FROM Management WHERE ACCOUNT_USERNAME=? AND PASSWORD=?"; 
					$tablename = 'Management';$pagename='Admin_Area.php';}
					
                    elseif($usertype == 'Cust'){
                    $sql="SELECT * FROM Customer WHERE ACCOUNT_USERNAME=? AND PASSWORD=?";
					$usertype  = 'Customer';  $pagename='store.php';}
                
                    $types  = Array(  'text' , 'text'  );
                    $values = Array($ID,$password);

                    //execute Users
                    $stmt = $conn->prepare($sql, null, MDB2_PREPARE_RESULT); //null AUTO_DETERMINE types
                    $sql = $stmt->execute($values);
                    //check errors
                    if (PEAR::isError($sql)) {
                        die ($sql->getUserInfo()); //getDebugInfo()); //
                    }
                    $arrUsers    = $sql->fetchAll(MDB2_FETCHMODE_ASSOC);
                    $countUsers  = count($arrUsers); //fetch returns Array so use count to get count
                    
                    //found?? or not??
                    if($countUsers>0){ //user successfully logged, redirect to user page determined above
                        //redirect to company
                        echo "<script> location.href='$pagename'; </script>";
                        exit(); //prevents code down from further execution after redirection
                    }else{ //user details not found, give error message
                        $msg = "Invalid username or password !!";
                        echo "<script>alert('$msg');</script>";
                    }

            } elseif (isset($_REQUEST['btncancel'])){ //CANCEL BUTTON  
                    //no need to code reset / cancel button, it simply clears all fields on submission 
            }
            
        }
    
    }
    
} catch (Exception $ex) {
    $msg=$ex->getMessage();
    echo $msg;
    echo "<script>alert('$msg');</script>";
    exit();
}

?>
</center>
