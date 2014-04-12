---
layout: post
title: Angular Autofocus Directive
categories: [angular]
tags: [angular, directive]
---

I've had some issues recently with the `autofocus` attribute not firing consistently in nested Angular views.

Here's a quick directive that's resolved it (so far!):

{% highlight javascript %}
angular
  .module('ec.autofocus', [])
  .directive('autofocus', ['$document', function($document) {
    return {
      link: function($scope, $element, attrs) {
        setTimeout(function() {
          $element[0].focus();
        }, 100);
      }
    };
  }])
;
{% endhighlight %}
