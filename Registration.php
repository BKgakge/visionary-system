<html>
<body> 
		<center><h1><i> Milllie's Musical Emporium <br></i></h1></center>
		<center><h2><i>The Countries Leading Suppliers of Musical instruments and Associated Media</i></h2></center>
		
			<center><button><a href="Index.php">Home</a> </button>
			<button><a href="Login.php">Login</a></button><br>
			<!--<button><a href="p_products.php"> Our Products</a></button></center>-->
		<br>
		<form name='Customer' action='Registration.php' method='post'>
			<fieldset>
			<legend>REGISTER HERE:</legend>
				<!--User_Name:<br/>
				<input type='text' name='id' placeholder='User Name'/><br/>-->
				First_Name:<br/>
				<input type='text' name='fname' placeholder='First Name'/><br/>
				Surname:<br/>
				<input type='text' name='sname' placeholder='Surname'/><br/>
				Gender:<br/>
				<select name='gender' style="width:155px;"><br/>
					<option value='0'>Select...</option>
					<option value='Male'>Male</option>
					<option value='Female'>Female</option>
				</select><br/>
				Date Of Birth:<br/>
				<input type="date" name='dob' placeholder='Birth Date'/><br/>
				<br/>
				Residential_Address:<br/>
				<input type='text' name='raddress' placeholder='Address'/><br/>
				Telephone_NO:<br/>
				<input type='text' name='tnumber' placeholder='Telephone_NO'/><br/>
				E-mail:<br/>
				<input type='text' name='email' placeholder='E_mail'/><br/>
				Account_Username:<br/>
				<input type='text' name='accuser'required placeholder='Account Username'/><br/>
				Password:<br/>
				<input type="password" name="pass" required placeholder='Password'/><br/>
				<br/>
				<center><input type='submit' name='btnsave' value='Save'/>
				<input type='submit' name='btnupdate' value='Update'/>
				<input type='submit' name='btndelete' value='Delete'/>
				<input type='submit' name='btnsearch' value='Search'/></center>
				<br/>
			</fieldset>
		</form>
    
<?php

require_once("DataAccess.php");

try {
        
    $conn = (new DataAccess())->GetOracleConnection();
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){  //was form submitted
        
        //get user data into variables
        //$id=  $_REQUEST['id']; 
        $fname=$_REQUEST['fname']; 
        $sname=$_REQUEST['sname']; 
		$gender=$_REQUEST['gender']; 
		$dob=(new DateTime($_REQUEST['dob']))->format('Y-M-d'); //format date into ORACLE format
		$raddress=$_REQUEST['raddress'];
		$tnumber=$_REQUEST['tnumber'];
		$email=$_REQUEST['email'];
		$accuser=$_REQUEST['accuser'];
		$pass=$_REQUEST['pass'];
//        var_dump($_REQUEST);
//        var_dump($_POST);
        //validate data
        if( empty($accuser) ){
            echo "<script>alert('Please enter the Account Username !!');</script>";
        } else { 
        
            //get button clicked by user -- get operation//
            if(isset($_REQUEST['btnsave'])){ //SAVE BUTTON
                
                if(empty($fname) || empty($sname) || empty($gender) || empty($dob) || empty($raddress) || empty($tnumber) || empty($email) || empty($accuser) || empty($pass)){ //validate all other fields for INSERT
                    echo "<script>alert('missing field');</script>";
                    exit();
                }
                //METHOD 2 - USING CONCATENATION
                    //execute using exec
                    $sql  = "INSERT INTO Customer (CUSTOMER_ID,FIRST_NAME,SURNAME,GENDER,DATE_OF_BIRTH,RESIDENTIAL_ADDRESS,TELEPHONE_NO,EMAIL,ACCOUNT_USERNAME,PASSWORD) VALUES (customer_seq.nextval,'$fname','$sname','$gender','$dob','$raddress',$tnumber,'$email','$accuser','$pass')"; //'$id'  //sq_stud_id.nextval //sequence usage
                    $count= $conn->exec($sql);
                //END METHOD 2
                    
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Registration successfull !!" : "Record saving failed !!";
                    echo "<script>alert('$msg');</script>";

            } elseif (isset($_REQUEST['btnupdate'])){ //UPDATE BUTTON
                
                if(empty($fname) || empty($sname) || empty($gender) || empty($dob) || empty($raddress) || empty($tnumber) || empty($email) || empty($accuser) || empty($pass) ){ //validate all other fields for UPDATE
                    echo "<script>alert('Please enter missing data values !!');</script>";
                    exit();
                }
               
                //METHOD 2 - USING CONCATENATION
                     //execute using exec
                     $sql  = "UPDATE UserRegistration SET FullName='$fname',Surname='$sname',Password='$pword',DOB='$dob',Address='$padress',Telephone='$tel',Email='$email',AccNumb='$accnumb' WHERE UserID='$id'";
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
                
               //METHOD 2 - USING CONCATENATION
                    //execute using exec
                    $sql  = "DELETE FROM STUDENTS WHERE ACCOUNT_USERNAME='$accuser', PASSWORD='$pass'";
                    $count = $conn->exec($sql);
                //END METHOD 2
                    //check errors
                    if (PEAR::isError($count)) {
                        die ($count->getUserInfo()); //getDebugInfo()); //
                    }
                    //confirm
                    $msg = ($count>0) ? "Record deleted successfully !!" : "Record deleting failed !!";
                    echo "<script>alert('$msg');</script>";
                    
            } elseif (isset($_REQUEST['btnsearch'])){ //SEARCH BUTTON
                
                //METHOD 1 - USING PARAMETERS
                    $sql  = "SELECT * FROM Customer WHERE ACCOUNT_USERNAME=?"; 
                    $types = array('integer');
                    $values = Array($accuser);

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
                    
                    $id    = $arr[0]["UserID"];
                    $fname = $arr[0]["FullName"];
                    $sname = $arr[0]["Surname"];
					$pword= $arr[0]["Password"];
					$dob= $arr[0]["DOB"];
					$padress= $arr[0]["Address"];
					$tel= $arr[0]["Telephone"];
					$email= $arr[0]["Email"];
					$accnumb= $arr[0]["AccNumb"];
                    
                    echo "<script>document.getElementsByName('id')[0].value    = '$id';</script>";
                    echo "<script>document.getElementsByName('fname')[0].value = '$fname';</script>";
                    echo "<script>document.getElementsByName('sname')[0].value = '$sname';</script>";
					echo "<script>document.getElementsByName('pword')[0].value    = '$pword';</script>";
                    echo "<script>document.getElementsByName('dob')[0].value = '$dob';</script>";
                    echo "<script>document.getElementsByName('padress')[0].value = '$padress';</script>";
					echo "<script>document.getElementsByName('tel')[0].value    = '$tel';</script>";
                    echo "<script>document.getElementsByName('email')[0].value = '$email';</script>";
                    echo "<script>document.getElementsByName('accnumb')[0].value = '$accnumb';</script>";
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
	</body>
</html>