<?php
include("header.php");
?>
<link rel="stylesheet" type="text/css" href="css/tables.css">
<script type="text/javascript">
$(document).ready(function(){
        $("a").removeClass("active");
});
</script>
<h1 class="heading-top">Profile</h1>
<div class="content">
<?php

  $person_id = $_GET['pid'];
  $person_details = json_decode(PersonModel::get($person_id), true);
  if(!$person_details){
    echo '<script type="text/javascript">alert("Invalid profile link!"); </script>';
          PersonModel::redirect("welcome.php");
  }
  $first_name = $person_details["first_name"];
  $last_name = ucwords($person_details["last_name"]);
  $address = $person_details["address"];
  $zip = $person_details["zip_code"];
  $town = $person_details["town"];
  $email = $person_details["email"];
  $mobile = $person_details["mobile_phone"];
  $phone = $person_details["phone"];
  $created = $person_details["created_at"];
  
  print <<<END_FORM
  <center>
    <table class="table-fill">
      <tbody class="table-hover">
      <tr>
      <td class="text-left">Name</td>
      <td class="text-left">$first_name $last_name</td>
      </tr>
      <tr>
      <td class="text-left">Email</td>
      <td class="text-left">$email</td>
      </tr>
      <tr>
      <td class="text-left">Address</td>
      <td class="text-left">$address</td>
      </tr>
      <tr>
      <td class="text-left">Zip Code</td>
      <td class="text-left">$zip</td>
      </tr>
      <tr>
      <td class="text-left">City</td>
      <td class="text-left">$town</td>
      </tr>
      <tr>
      <td class="text-left">Mobile No.</td>
      <td class="text-left">$mobile</td>
      </tr>
      <tr>
      <td class="text-left">Telephone</td>
      <td class="text-left">$phone</td>
      </tr>
      <tr>
      <td class="text-left">Registered</td>
      <td class="text-left">$created</td>
      </tr>
      </tbody>
    </table>
  </center>
END_FORM;
?>
</div>
