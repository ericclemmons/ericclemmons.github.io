---
layout: post
title: Angular Trust Filter
categories: [angular]
tags: [angular, filter]
---

# Angular Trust Filter

It seems every Angular project I write, at some point, sees the error:

> `Attempting to use an unsafe value in a safe context.`

This is because of Angular's [$sce][1] service, which _enabled_ by default:

> SCE assists in writing code in way that (a) is secure by default and (b) makes auditing for security vulnerabilities such as XSS, clickjacking, etc. a lot easier.

Unfortunately, it can be cumbersome, especially when working with dynamic objects.


### The View

Suppose you're writing the front-end for a blog where the content is consumed via an AJAX request:


{% highlight jinja %}
{% raw %}
<h1 ng-bind-html="response.title"></h1>

<section ng-bind-html="response.content"></section>
{% endraw %}
{% endhighlight %}


When your view attempts to use the raw `response` content, you'll get an error
because it's _unsafe_.


### The Controller

To get around this, you can have your controller "whitelist" the content:

{% highlight javascript %}
angular
  .module('example', [])
  .controller('ExampleController', [
    '$sce',
    '$scope',
    function($sce, $scope) {
      ...
      // Assume there's a previous `$http` response
      response['title']   = $sce.trustAsHtml(response['title']);
      response['content'] = $sce.trustAsHtml(response['content']);

      $scope.response = response;
    }
  ])
;
{% endhighlight %}


### The Filter

Instead, **create a `trust` filter.**


{% highlight javascript %}
angular
  .module('example', [])
  .filter('trust', [
    '$sce',
    function($sce) {
      return function(value, type) {
        // Defaults to treating trusted text as `html`
        return $sce.trustAs(type || 'html', text);
      }
    }
  ])
;
{% endhighlight %}


Now your view an simply read:

{% highlight jinja %}
{% raw %}
<h1 ng-bind-html="response.title | trust"></h1>

<section ng-bind-html="response.content | trust"></section>
{% endraw %}
{% endhighlight %}


As with all things Angular, your view will contain your rendering logic and your controllers
can stay as thin as possible.


[1]: https://docs.angularjs.org/api/ng/service/$sce
