---
layout: post
title: Simple Node Cluster
categories: [node]
tags: [node, cluster, express]
---

After much testing of our internal API, we made it available for several sites to use & got *terrible* performance.

Of course, we got better performance with Node than we did with PHP, thanks to `async` parallelizing functions, but we didn't think *too much* about Node's
single-threaded nature.

We already had several sites using the API with more being added daily, all causing performance to quickly dwindle.

- - -

In a rush, we discovered Node's [`cluster`][cluster] module, which I don't remember from `v0.6`, but is pretty reliable in `v0.10`.

Per the [`cluster`][cluster] documentation:

> A single instance of Node runs in a single thread. To take advantage of
> multi-core systems the user will sometimes want to launch a cluster of
> Node processes to handle the load.
>
> The cluster module allows you to easily create child processes that all
> share server ports.

Implementation was *dead easy*.

### 1. Setup our Express application for easy use via `require`:

{% highlight javascript %}
// app/server.js
var app   = require('./app');
var http  = require('http');

http.createServer(app).listen(app.get('port'), function(){
  console.log('Express server listening on port ' + app.get('port'));
});
{% endhighlight %}

For development, we will run `node app/server`:

~~~ bash
$ node app/server.js 
Express server listening on port 3000
~~~

### 2. Create `cluster.js`

{% highlight javascript %}
// app/cluster.js

var cluster = require('cluster');

if (cluster.isMaster) {
  // Count the machine's CPUs
  var cpuCount = require('os').cpus().length;

  // Create a worker for each CPU
  for (var i = 0; i < cpuCount; i += 1) {
    cluster.fork();
  }

  // Listen for dying workers & start 'em back up!
  cluster.on('exit', function () {
    cluster.fork();
  });
} else {
  require('./server');
}
{% endhighlight %}

### 3. Update `package.json` to use `cluster.js`

{% highlight json %}
// package.json
{
  ...
  "scripts": {
    "start": "node app/cluster"
  },
  ...
{% endhighlight %}

### 4. Done!

Now on production, `npm start` will run `node app/cluster` and spawn up 4+ instances:

~~~ bash
$ npm start

> api@1.4.2 start /Users/deploy/api
> node app/cluster

Express server listening on port 3000
Express server listening on port 3000
Express server listening on port 3000
~~~

> ![cluster](/images/node/cluster.png)


[cluster]: http://nodejs.org/api/cluster.html
