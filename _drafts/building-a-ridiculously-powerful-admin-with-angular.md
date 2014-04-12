---
layout: post
title: Building a Ridiculously Powerful Admin with Angular
categories: [angular]
tags: [angular, admin, express, bookshelf]
image:
    feature: angular/admin.png
---

We've *all* built a lot of admins.  For me, they're a playground of that's a mixture of 50% business and 50% fun, experimenting with a new language or framework.

Still, despite awesome admins existing for [Django][django], [Rails][rails], and [Symfony][symfony], we're still faced with the same core problem:

> Admins tend to focus on making CRUD easier, not on the workflow.

**Of course** this is what you get with a framework that's meant to simplify model management.  Workflow is entirely unique to *those doing the work.*

So, as I'm a big fan of [AngularJS][angular], my team & I set out to create an admin shaped by the work, not the database structure.

## Requirements

- Bulk additions/removals/edits.
- UI should simplify complex database relationships.
- UI should be malleable & cheap to change.
- Separate from existing admin to simplify transition.

Granted, most of these requirements surfaced from our own & others complaints with the current admin.  Nothing new, but this was enough to determine our potential tools.

## Tools

I find it best to work from the client *down* to the server when coming up with a full-stack solution:

- [AngularJS][angular], [AngularUI Router][ui-router], & [Bootstrap][bootstrap], as they are **perfect** for rapid prototyping.
- [deep-diff][diff] for computing changesets.
- [Express][express] for the server-side.
- [Bookshelf][bookshelf] for the ORM, which makes it easy to map an existing relational database without having to worry about the schema (outside of relationships).
- [Bookshelf Manager][bookshelf-manager] to wrap perform bulk changes & makes it easy to save a deeply-nested object.

> Using these tools, we're able to request a deeply-nested object, make 
> changes to it, and **send the *changed* object back** for saving.

*Interestingly enough, this project actually prompted open-sourcing [Bookshelf Manager][bookshelf-manager]!*

## How is it done?

Let's say our user needs to manage a `school`, its `programs`, and a whole slew of other details & relationships around these.  We just then need to connect the following pieces:

1. The state should `resolve` a deep object.
2. The server should respond with the deep object.
3. The controller should `$http.put` the object back to the server upon save.
4. The controller should watch the deep object for changes.
5. The server should save the deep object.
6. The Angular-powered, dynamic views that modify the deep object.

### 1. Create the state to request the deep object

The important part is where we request the url: `/api/schools/:id/?related=...`

{% highlight javascript %}
// client/admin/routes/school.js
angular
  .module('admin.routes.school', [
    'admin.controllers.school'
    'ui.router'
  ])
  .config([
    '$stateProvider',
    function($stateProvider) {
      $stateProvider.state('school', {
        url: '/schools/:id',
        controller: 'admin.controllers.school',
        resolve: {
          school: ['$http', '$stateParams', function($http, $params) {
            var related = [
              ...
              'programs',
              ...
            ].join(',');

            return $http
              .get('/api/schools/' + $params.id + '?related=' + related)
              .then(function(response) {
                return response.data;
              })
            ; 
          }]
        }
      });
    }
  ])
;
{% endhighlight %}

### 2. Create the server-side route to return the deep object

{% highlight javascript %}
// server/lib/api.js
var Bookshelf = require('bookshelf');
var Manager   = require('bookshelf-manager');
...
var bookshelf = new Bookshelf(...);
var manager   = new Manager(Path.join(__dirname, 'models'), bookshelf);

app.get('/api/:slug', function(req, res) {
  var id      = req.params.id;
  var related = (req.params.related || '').split(',');
  var slug    = req.params.slug;

  manager.fetch(slug, { id: id }, related).then(function(model) {
    res.send(model);
  });
});
{% endhighlight %}

### 3. Create the controller to save the changed object

{% highlight javascript %}
// client/admin/controllers/school.js
angular
  .module('admin.controllers.school', [
    'ui.router'
  ])
  .controller('admin.controllers.school', [
    '$scope',
    'school',
    function($scope, school) {
      $scope.school = school;

      $scope.save = function() {
        $http.put('/api/schools/' + school.id, school);
      };
    }
  ])
;
{% endhighlight %}

### 4. Watch the object for changes

To avoid creating too many watchers with Angular, I've actually found it was "cheaper" to calculate changes on a timer:

{% highlight javascript %}
// client/admin/controllers/school.js
...
function($scope, school)
  $scope.school = school;

  var original = angular.copy(school);

  // $interval triggers a $scope.$apply with or without changes
  setInterval(function() {
    if (angular.equals(original, school)) {
      return false;
    }

    $scope.changes = new DeepDiff(original, school);
  }, 1500);
  ...
{% endhighlight %}

Now our view can take advantage of `changes` in our view:

{% highlight html %}
{% raw %}
<button ng-disabled="!changes.length" ng-click="save()" type="submit">
  {{ changes.length ? ('Save ' + changes.length)  : 'No' }}
  Changes
</button>
{% endraw %}
{% endhighlight %}

![no-changes](/images/angular/no-changes.png)
![save-changes](/images/angular/save-changes.png)

### 5. Create the server-side route to save the changed object

{% highlight javascript %}
// server/lib/api.js
...
app.put('/api/:slug', function(req, res) {
  var id      = req.params.id;
  var slug    = req.params.slug;
  var changes = req.body;

  manager.fetch(slug, { id: id }).then(function(model) {
    manager.save(model, changes).then(function(result) {
      res.send(result);
    });
  });
});
{% endhighlight %}

### 6. Create the dynamic views to change the deep object

This is where [AngularJS][angular] & [Bootstrap][bootstrap] shine, and you have complete flexibility with the UI so long as you modify that single object.


[angular]: http://angularjs.org/
[bookshelf]: http://bookshelfjs.org/
[bookshelf-manager]: https://github.com/ericclemmons/bookshelf-manager
[bootstrap]: http://getbootstrap.com/
[diff]: https://github.com/flitbit/diff
[django]: https://docs.djangoproject.com/en/dev/ref/contrib/admin/
[express]: http://expressjs.com/
[rails]: http://activeadmin.info/
[symfony]: http://sonata-project.org/bundles/admin/master/doc/reference/dashboard.html
[ui-router]: http://angular-ui.github.io/ui-router/
