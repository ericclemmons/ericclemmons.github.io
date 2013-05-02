angular
  .module('app')
  .controller('articleController', [
    '$filter',
    '$http',
    '$routeParams',
    '$scope',
    'githubService',
    function($filter, $http, $params, $scope, github) {
      var filter = $filter('filter');

      github.issues().then(function(issues) {
        var issue = filter(issues, { number: $params.number })[0];

        if (issue) {
          $scope.article  = issue;
          $scope.comments = $http
            .get(issue.comments_url)
            .then(function(response) {
              return response.data;
            })
          ;
        }
      });
    }
  ])
;
