angular
  .module('app', [
    'slugifier'
  ])
  .constant('GITHUB_USER', 'ericclemmons')
  .constant('GITHUB_REPO', 'site')
  .config([
    '$locationProvider',
    '$routeProvider',
    function($location, $router) {
      $location.hashPrefix('!');
      $router
        .when('/', {
          controller:   'homeController',
          templateUrl:  'app/templates/home.html'
        })
        .when('/article/:number/:slug', {
          controller:   'articleController',
          templateUrl:  'app/templates/article.html'
        })
      ;
    }
  ])
;
