angular
  .module('app')
  .directive('appMarkdown', function() {
    marked.setOptions({
      breaks:     true,
      smartLists: true,
      sanitize:   true
    });

    return {
      require:    'ngModel',
      restrict:   'EAC',
      scope:      {
        markdown: '=ngModel'
      },
      link:       function($scope, $element, $attrs, model) {
        $scope.$watch('markdown', function(markdown) {
          if (!markdown) {
            return;
          }

          var html = marked(markdown);

          $element.html(html);

          angular.forEach($element.find('pre'), function(pre) {
            var $pre    = angular.element(pre);
            var html    = $pre.html();
            var pretty  = prettyPrintOne(html, undefined, 1);

            $pre
              .addClass('prettyprint linenums')
              .html(pretty)
            ;
          });
        });
      }
    };
  })
;
