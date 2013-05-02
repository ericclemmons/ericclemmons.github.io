angular
  .module('app')
  .service('githubService', [
    '$http',
    'GITHUB_USER',
    'GITHUB_REPO',
    function($http, user, repo) {
      this.context = function() {
        return user + '/' + repo;
      };

      this.issues = function() {
        var url = 'https://api.github.com/repos/' + this.context() + '/issues';

        return $http
          .get(url, {
            params: {
              state: 'open'
            }
          })
          .then(function(response) {
            return response.data;
          })
        ;
      };

      this.markdown = function(markdown) {
        return $http
          .post('https://api.github.com/markdown', {
            text:     markdown,
            mode:     'gfm',
            context:  this.context()
          })
          .then(function(response) {
            return response.data;
          })
        ;
      };
    }
  ])
;
