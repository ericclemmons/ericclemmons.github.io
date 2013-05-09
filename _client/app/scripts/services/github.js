angular
  .module('app')
  .service('githubService', [
    '$http',
    'GITHUB_USER',
    'GITHUB_REPO',
    function($http, user, repo) {
      this.comments = function(issue) {
        return this.get(issue.comments_url);
      };

      this.context = function() {
        return user + '/' + repo;
      };

      this.issues = function() {
        return this.get('https://api.github.com/repos/' + this.context() + '/issues', {
          params: {
            state: 'open'
          }
        });
      };

      this.get = function(url, config) {
        config = angular.extend({
          headers: {
            Accept: "application/vnd.github.full+json"
          }
        }, config);

        return $http
          .get(url, config)
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
