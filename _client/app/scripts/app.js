angular
  .module('app', [
    'slugifier'
  ])
  .constant('GITHUB_USER', 'ericclemmons')
  .constant('GITHUB_REPO', 'ericclemmons.github.io')
  .filter("slugify", ['Slug', function(Slug) {
    return function(input) {
      return Slug.slugify(input);
    };
  }])
  .config([
    '$locationProvider',
    '$routeProvider',
    function($location, $router) {
      $location
        .html5Mode(true)
        .hashPrefix('!')
      ;

      $router
        .when('/article/:number/:slug', {
          controller:   'articleController',
          templateUrl:  'app/templates/article.html'
        })
        .when('/', {
          controller:   'homeController',
          templateUrl:  'app/templates/home.html'
        })
      ;
    }
  ])
;
