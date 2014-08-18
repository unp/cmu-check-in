<?php

$andrew_id = $_GET["andrew_id"];

$ldapServerLocation = "ldap.andrew.cmu.edu";

$ds = ldap_connect($ldapServerLocation);

if ($ds) { 

    $sr = ldap_search($ds, "dc=cmu,dc=edu", "cmuAndrewId=$andrew_id");

    $info = ldap_get_entries($ds, $sr);
    
    if($info["count"] > 0) {
      
      // For some reason CMU always returns two records for each Andrew ID
      // Use the latter record since it has the less formal name
      $student = $info[$info["count"] - 1];
      
      // Build response object
      $response["first_name"] = $student["givenname"][0];
      $response["last_name"] = $student["sn"][0];
      $response["andrew_id"] = $student["cmuandrewid"][0];
      $response["department"] = $student["cmudepartment"][0];
      $response["grad_class"] = $student["cmustudentclass"][0];
      echo json_encode($response);
      
      saveResponse($response);
      
    }
    else {
      http_response_code(404);
      echo "Unable to find Andrew ID";
    }
    ldap_close($ds);

} else {
  http_response_code(404);
  echo "Unable to connect to LDAP server";
}

function saveResponse($result) {
  
  $mysql_host = "localhost";
  $mysql_db = "checkins";
  $mysql_user = "root";
  $mysql_pass = "root";
  
  $con = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
  
  if (mysqli_connect_errno())
  {
    die('Could not connect: ' . mysqli_connect_error());
  }
  
  $result = sanitizeData($result);

  $query = "INSERT INTO check_ins
            (first_name, last_name, andrew_id, department, grad_class)
            VALUES (
              '$result[first_name]',
              '$result[last_name]',
              '$result[andrew_id]',
              '$result[department]',
              '$result[grad_class]'
            );";
  
  $retval = mysqli_query($con, $query);
  
  if(! $retval )
  {
    die('Could not enter data: ' . mysqli_error($con));
  }

  mysqli_close($con);
}

function sanitizeData($result) {
  foreach ($result as $value){
    $result[$key] = mysqli_real_escape_string($value);
  }
  return $result;
}

?>