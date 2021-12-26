---
eleventyNavigation:
  key: Home
  url: '/'
  order: 1
permalink: /
layout: base.njk
title: True WYSIWYG for Joomla's tinyMCE Editor
description: The ultimate editing experience for Joomla 4.1+
---

# True WYSIWYG for Joomla's tinyMCE Editor

{% img %}

### Latest Version
{% assign fff = downloads | first%}

Download the latest version: [{{fff.version}}]({{ metainfo.url }}/dist/{{fff.name}})

### What it does

Enables true WYSIWYG for Joomla's tinyMCE Editor. By default it supplies only the required files for the Cassiopeia template but you can use it for any other template as well.

In pictures, it transforms the editor from this:

{% image "./site/images/before.png", "Default Joomla Image Tag", "(min-width: 30em) 50vw, 100vw" %}

...to this:

{% image "./site/images/after.png", "Responsive Images Generated Tag", "(min-width: 30em) 50vw, 100vw" %}

...for some rich pasted content, like:
```html
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Well done!</h4>
  <p>Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.</p>
  <hr>
  <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
</div>
```
