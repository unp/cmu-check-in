var checkinApp = angular.module('checkinApp', ['angular-growl', 'ngAnimate']);

checkinApp.config(['growlProvider', function(growlProvider) {
  growlProvider.globalTimeToLive(3000);
}]);

checkinApp.controller('CheckInCtrl', ['$scope', '$http', '$timeout', 'growl',
function($scope, $http, $timeout, growl) {
  
  $scope.checkIns = [];
  $scope.processing = false;
  
  $scope.getSemester = function() {
    var today = new Date();
    var season = today.getMonth() < 7 ? 'Spring' : 'Fall';
    return season + ' ' + today.getFullYear();
  }
  
  $scope.addCheckIn = function() {
    $scope.processing = true;
    $http({
      method: 'GET',
      url: 'lib/cmu_ldap.php',
      params: { andrew_id: $scope.andrew_id }
    })
    .success(function(data, status, headers, config) {
      $scope.checkIns.push(data);
      $scope.andrew_id = '';
      growl.addSuccessMessage('Successfully checked in!');
      $scope.processing = false;
      $timeout(function(){
        document.getElementById('andrew_id').focus();
      }, 0);      
    })
    .error(function(data, status, headers, config) {
      growl.addErrorMessage(data);
      $scope.processing = false;
      $timeout(function(){
        document.getElementById('andrew_id').focus();
      }, 0);
    });
  }
  
}]);