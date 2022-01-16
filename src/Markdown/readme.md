### Markdown Helper for Laravel

Process markdown via function and Blade directives.

YouTube embed functionality a modified version of code from zoonru/commonmark-ext-youtube-iframe.

### Usage

* `markdown($string)` converts markdown to HTML
* `@markdown($string)` Blade directive
* Includes GFM functionality with soft line-breaks as `<br>` and autolinking URLs.
* Full-syntax YouTube links (e.g. `[](https://www.youtube.com/watch?v=wJzNZ1c5C9c)`) are converted into Bootstrap-compatible responsive embeds. 
