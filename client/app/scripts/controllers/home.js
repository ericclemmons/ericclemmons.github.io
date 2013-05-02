angular
  .module('app')
  .controller('homeController', [
    '$scope',
    'githubService',
    function($scope, github) {
      $scope.articles = github.issues();
    }
  ])
;
