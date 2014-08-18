var checkinApp = angular.module('checkinApp', ["angular-growl"]);

checkinApp.config(['growlProvider', function(growlProvider) {
  //growlProvider.globalTimeToLive(5000);
}]);

checkinApp.controller('CheckInCtrl', ['$scope', '$http', '$timeout', 'growl',
function($scope, $http, $timeout, growl) {
  
  $scope.checkIns = [];
  $scope.processing = false;
  
  $scope.addCheckIn = function() {
    $scope.processing = true;
    $http({
      method: 'GET',
      url: 'lib/cmu_ldap.php',
      params: { andrew_id: $scope.andrew_id }
    }).
    success(function(data, status, headers, config) {
      $scope.checkIns.push(data);
      $scope.andrew_id = "";
      $scope.processing = false;
      $timeout(function(){
        document.getElementById('andrew_id').focus();
      }, 0);
      growl.addSuccessMessage("Successfully checked in!");
      
    }).
    error(function(data, status, headers, config) {
      $scope.processing = false;
      growl.addErrorMessage(data);
    });
  }
  
}]);