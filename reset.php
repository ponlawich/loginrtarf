<!DOCTYPE html><html lang="en">
<title>RTARF Account</title>
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style>
body {
 padding: 25px;
 vertical-align: middle;
 font-size: large;
}
.btnbig {
  font-size: 40px;
  margin-right: 5px;
}
img {
 width: 256px;
}

</style>

<?php
function personget($id)
{
 # code...
 header('Access-Control-Allow-Origin: *');
 $server = "10.104.117.50";
 $port = "1522";
 $serverName = "ORA10G";
 
 $user = "rtarfmail";
 $password = "tf>sDuJGAdBT7,9K";
 
 try {
  #echo $id;
  $conn =  new PDO("oci:dbname=$server:$port/$serverName;charset=UTF8", $user, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  switch ($id) {
   case (preg_match("/^\d+\.?\d*$/",$id) && strlen($id)==13) :
    # code...
     $stmt = $conn->prepare("SELECT * FROM REGISTER_TAB WHERE REG_CID = :CID");
    break;
   
   case (preg_match("/^\d+\.?\d*$/",$id) && strlen($id)==10) :
    # code...
     $stmt = $conn->prepare("SELECT * FROM REGISTER_TAB WHERE REG_MID = :CID");
    break;
    
   default:
    # code...
     $id = "%$id%";
     $stmt = $conn->prepare("SELECT * FROM REGISTER_TAB WHERE REG_FULLNAME LIKE :CID");
    break;
  }
  $stmt->bindParam(':CID', $id, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
  $conn = null;
 } catch(PDOException $e) {
  #echo 'ERROR: ' . $e->getMessage();
 }
 
 #var_dump($result);

 return $result;
}

function PassENC($A)
{
 # code...
    $ADPasswd = "$A";
    $ADpasswd = "\"".$ADPasswd."\"";
    $len = strlen($ADpasswd);
    for ($i = 0; $i < $len; $i++) $ADNewPass .= "{$ADpasswd{$i}}\000";
    return $ADNewPass;
}

function adcode($username,$newpassword)
{
 # code...

 $result = false;

 $ADServer = "ldaps://10.104.117.190";
 putenv("LDAPTLS_CIPHER_SUITE=NORMAL:!VERS-TLS1.2");
    $ADConn = ldap_connect($ADServer);
 ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 0);
    ldap_set_option($ADConn, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ADConn, LDAP_OPT_REFERRALS, 0);
  if ($ADConn){

   $ADBind = ldap_bind($ADConn ,"rtarf\itsupport.app3","UJwFG5n6pDLs9C9vB4HnbDG");
   
   if ($ADBind){
    $Filter = "(samaccountname=".$username.")";
    $ADSearch = ldap_search($ADConn, "DC=rtarf,DC=local", $Filter);

    if ($ADSearch){                    
     $ADGetRecord = ldap_get_entries($ADConn, $ADSearch);
                    $ADGetDN = $ADGetRecord[0]["distinguishedname"][0];
                    $ADNewRecord = array();
                    $ADNewRecord["unicodePwd"] = PassENC($newpassword);
                    $ADNewRecord["lockouttime"][0] = '0';
                    if(ldap_modify($ADConn, $ADGetDN, $ADNewRecord)){
      $result = true;
      print '<h1><p style="color:green;">Success.</p>';
      $Loginname = "rtarf\\".$username;
                     $ADBind = ldap_bind($ADConn , $Loginname, $newpassword);
                     $Filter = "(samaccountname=".$username.")";
                     $ADSearch = ldap_search($ADConn, "DC=rtarf,DC=local", $Filter);
                     $ADGetRecord = ldap_get_entries($ADConn, $ADSearch);
                     if($ADGetRecord){
       print '<h3><p style="color:green;">Test Login Pass.</p>';
      }
      else{
       print '<h3><p style="color:red;">Test Login Fail.</p>';
      }
     }
     else{
      print '<h1><p style="color:red;">Fail.</p></h1>';
     }

                }

            }

        }
        ldap_close($ADConn);

        return $result;
}

function SearchBox($TextHolder)
{
 print '<form id="form" name="form" method="post" action="" autocomplete="off" class="form-inline">
   <div class="input-group form-group-lg">
    <input type="text" id="txt0" name="txt0" class="form-control" pattern=".{1,}" autofocus required placeholder="'.$TextHolder.'">
    <div class="input-group-btn">
     <button id="btn0" class="btn btn-lg btn-info" type="submit" name="CommitSearch">
      <i class="glyphicon glyphicon-search"></i>
     </button>
    </div>
   </div>
  </form>';
}

function LogsApp()
{
 
}

?>
<div class="container text-center">
 <div class="row">
 
<?php
if($_POST){
 if(isset($_POST['CommitReset'])){
  print "<h2><p>Reset Password</p>";
  print "<h1><p>".$_POST['hidA']."@rtarf.mi.th</p>";
  if (adcode($_POST['hidA'],$_POST['hidB'])) {
   # code...
   print "<h2><p>Password is</p>";
   print "<h1><p>".$_POST['hidB']."</p>";
  
  }

  print '<a href="" class="btn btn-lg btn-info btnbig" role="button">Close<a>';
 }
 if(isset($_POST['CommitSearch'])){
  #echo "<pre>";
  #var_dump(personget($_POST['txt0']));
  #echo "</pre>";

  $PersonData = personget($_POST['txt0']);

  if($PersonData){

   SearchBox(sizeof($PersonData)." Record");

   foreach ($PersonData as $key => $value) {
    # code...
    print '<form id="form'.$key.'" name="form'.$key.'" method="post" action="" autocomplete="off" >';
    print '<br><img src="person.png">';
    print '<input type="hidden" id="hidX" name="hidX" value="'.$key.'">';
    #print '<input type="hidden" id="hidA'.$key.'" name="hidA'.$key.'" value="'.$PersonData[$key]["REG_USERNAME"].'">';
    #print '<input type="hidden" id="hidB'.$key.'" name="hidB'.$key.'" value="'.$PersonData[$key]["REG_CID"].'">';
    print '<input type="hidden" id="hidA" name="hidA" value="'.$PersonData[$key]["REG_USERNAME"].'">';
    print '<input type="hidden" id="hidB" name="hidB" value="'.$PersonData[$key]["REG_CID"].'">';
    print "<h2><p>".$PersonData[$key]["REG_FULLNAME"]."</p>";
    print "<p>".$PersonData[$key]["REG_USERNAME"]."@rtarf.mi.th</p></h2>";
    print '<button type="submit" id="btn'.$key.'" class="btn btn-danger btn-lg btnbig" name="CommitReset">Reset</button>';
    print '<a href="" class="btn btn-lg btn-info btnbig" role="button">Close</a></form><br>';
   }
  }
  else{
   print '<img src="person.png">';
   print "<h2><p>Invalid ID.</p>";
   print '<a href="" class="btn btn-lg btn-info btnbig" role="button">Close<a></form>';
  }
 }
}
else{
 SearchBox("Search");
}
?>