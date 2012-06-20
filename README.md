# Static Site Generator

Create & publish a static content blog using [Vagrant][1] & [Symfony2][2] in
an isolated VM without mucking up your local machine!


## Description

Like [Octopress][3], this small project utilizes the familiarity of
PHP (via [Symfony2][2]), [Markdown][3], [Vagrant][1] & [Github Pages][5]
for the creation & publishing of content.

After previewing the content at http://localhost:8080/, I can publish
content directly to a github page: http://ericclemmons.github.com/


## Installation

This project depends on [ComposerPHP][4], [Vagrant][1] for previewing and
a local `~/.gitconfig` to piggy-back on for deployments.

Luckily, the steps below are 1-time operations.

1. Install [Composer][4] (`$ curl -s http://getcomposer.org/installer | php`).
2. Install [Vagrant][1].
3. Run `$ php composer.phar install` to setup dependencies.
4. Run `$ vagrant up` to download the base VM & install all dependencies.


## Usage

**Ensure your local machine is running via `$ vagrant up` first!**


### Edit Content

Content is stored under `/content/pages` and `/content/posts`.

**Posts** follow the chronological naming scheme of `2012-05-06-some-slug.md`
for simple creation of URLs, slugs, and determining order.

**Pages** are more like "About Me" and "Projects", where the date is not
significant.  Additionally, pseudo categories can be created by simply placing
files in a sub-folder (e.g. `/content/pages/projects/new-site.md`).


### Preview

Open http://localhost:8080/ in your browser.  *The port may be changed by
Vagrant if there is a conflict!*


### Publish

The bulk of the work is handled in `/bin/publish`:

```
    $ vagrant ssh --c "./bin/publish"
```

### Done!


[1]: http://vagrantup.com/
[2]: http://symfony.com/
[3]: http://octopress.org/
[4]: http://getcomposer.org/
[5]: http://pages.github.com/
