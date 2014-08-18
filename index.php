<?php require("lib/functions.php") ?>
<!DOCTYPE html>
<html ng-app="checkinApp">
<head>
  <title>CMU AKPsi Rush Check-In</title>
  <link rel="stylesheet" type="text/css" href="css/reset.css">
  <link rel="stylesheet" type="text/css" href="css/growl.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700,600' 
        rel='stylesheet' type='text/css'>
  <script src="js/angular.min.js"></script>
  <script src="js/angular-growl.js"></script>
  <script src="js/app.js"></script>
</head>
<body ng-controller="CheckInCtrl">
  <div id="container">
    <div growl></div>
    <form ng-submit="addCheckIn()">
      <h1><?php echo semester(); ?> Rush Check-In</h1>
      <div class="field">
        <input type="text" ng-model="andrew_id" ng-disabled="processing"
               required placeholder="Andrew ID" autocapitalize="off" 
               autocorrect="off" spellcheck="false" id="andrew_id" />
        <span class="spinner" ng-if="processing"></span>
      </div>
      <input type="submit" value="Check In" class="btn"
             ng-disabled="processing" />
    </form>
    <table ng-if="checkIns.length > 0">
      <tr>
        <th>Name</th>
        <th>Andrew ID</th>
        <th>Department</th>
        <th>Class</th>
      </tr>
      <tr ng-repeat="checkIn in checkIns">
        <td>{{checkIn.first_name}} {{checkIn.last_name}}</td>
        <td>{{checkIn.andrew_id}}</td>
        <td>{{checkIn.department}}</td>
        <td>{{checkIn.grad_class}}</td>
      </tr>
    </table>
  </div>
</body>
</html>