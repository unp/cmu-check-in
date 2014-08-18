var checkinApp = angular.module('checkinApp', ['angular-growl', 'ngAnimate']);

checkinApp.config(['growlProvider', '$animateProvider', function(growlProvider, $animateProvider) {
  // Only animate the growl notifications
  $animateProvider.classNameFilter(/growl-item/);
  growlProvider.globalTimeToLive(3000);
}]);

checkinApp.controller('CheckInCtrl', ['$scope', '$http', '$timeout', 'growl',
function($scope, $http, $timeout, growl) {
  
  $scope.checkIns = [];
  $scope.error = "";
  $scope.processing = false;
  
  $scope.getSemester = function() {
    var today = new Date();
    var season = today.getMonth() < 7 ? 'Spring' : 'Fall';
    return season + ' ' + today.getFullYear();
  }
  
  $scope.addCheckIn = function() {
    $scope.processing = true;
    $scope.error = "";
    $http({
      method: 'GET',
      url: 'lib/cmu_ldap.php',
      params: { andrew_id: $scope.andrew_id }
    })
    .success(function(data, status, headers, config) {
      $scope.processing = false;
      $timeout(function(){
        document.getElementById('andrew_id').focus();
      }, 0);
      $scope.checkIns.push(data);
      $scope.andrew_id = '';
      growl.addSuccessMessage('Successfully checked in!');
    })
    .error(function(data, status, headers, config) {
      $scope.processing = false;
      $scope.error = data;
      $timeout(function(){
        document.getElementById('andrew_id').focus();
      }, 0);
      growl.addErrorMessage(data);
    });
  }
  
}]);