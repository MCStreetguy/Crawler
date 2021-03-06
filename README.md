# MCStreetguy/Crawler

**A highly configurable, modern web crawler for PHP.**

This library provides a very dynamic environment for all kinds of tasks based on recursive browsing of web pages.
Internally, [Guzzle](http://guzzlephp.org) is used to send requests, and [paquettg's HTML parser](https://github.com/paquettg/php-html-parser) to search server responses for follow-up links.  
The rest of the crawl process is entirely up to you. Apart from the initial configuration of the crawler, this library relies solely on user-defined classes, for example, to check whether a link should be crawled at all or to process the server's response.
These classes are only very roughly pre-defined by interfaces, but usually these only require one function that is used to invoke them.
The crawler does not care about the inner workings of these classes, in case of a processor it doesn't even require any return value.  
Due to this conception the library should be able to be integrated almost seamlessly into most frameworks.

**TOC:**
- [Installation](#installation)
- [Getting Started](#getting-started)
- [Reference](#reference)
  - [Configuration](#configuration)
  - [Validators](#validators)

## Installation

Require the library through Composer:

```
$ composer require mcstreetguy/crawler
```

## Getting Started

**Note:** _This example only covers the very basic requirements to get started. Have a look at the full documentation for more information if you wish._

-----------

First, you need an instance of the crawler class.
This is the "root node" of the library since most interaction takes place with an object of this class.

``` php
$crawler = new \MCStreetguy\Crawler\Crawler();
```

This already suffices to start crawling a webpage, but the crawler would not do anything at the moment.
We still need a processor to do something with the received responses.

### Processor

The corresponding interface is not very complex and only defines one method.
For now let's just create one that simply echoes out each found url:

``` php
use MCStreetguy\Crawler\Processing\ProcessorInterface;

class DebugProcessor implements ProcessorInterface
{
    public function invoke(\MCStreetguy\Crawler\Result\CrawlResult $result)
    {
        echo 'Crawled: ' . $result->getUri() . PHP_EOL;
    }
}
```

The `invoke` method receives an instance of `CrawlResult`, which holds several data concerning the crawled page.
This includes for example it's uri, the server response and further links found on that page.

Now if we add an object of our new class to the crawler, we can already execute it against some url:

``` php
$crawler = new \MCStreetguy\Crawler\Crawler();
$crawler->addProcessor(new DebugProcessor);
$crawler->execute('http://example.com/');
```

### Testing

Copying the above parts together into a script file and executing it on the command line now produces the following output:

> machine:~ testuser$ php test.php  
> Crawled: http://example.com/  
> Crawled: http://www.iana.org/domains/example  
> Crawled: http://www.iana.org/_css/2015.1/screen.css  
> Crawled: http://www.iana.org/_css/2015.1/print.css  
> Crawled: http://www.iana.org/_img/bookmark_icon.ico  
> Crawled: http://www.iana.org/  
> Crawled: http://www.iana.org/domains  
> Crawled: http://www.iana.org/numbers  
> ^C  
> machine:~ testuser$  

_Wait! Why is this even working?_

Well, `example.com` is actually an existing website and it contains exactly one link.
That link leads directly to the Internet Assigned Numbers Authority (IANA), explaining the purpose of the example page.
So we can say for sure that our small test succeeded and the crawler works, as it reached `example.com` and found the link on it.
But is that intentional behavior? Not necessarily, but we have a solution for that, too.

### Validation

To prevent our crawler from happily jumping across webpages and discovering the whole internet we need another custom class: a validator.
A validator works nearly the same as a processor, but it's invoked far earlier in the process loop.
It get's the pending uri handed over as argument and is expected to return a boolean value, indicating if the uri shall be crawled.  
You may define as many validators as you like so you can split complex decisions up in several parts, but keep in mind that this works like a blacklist (i.e. if one validator returns false, the uri is dropped immediately).

``` php
use MCStreetguy\Crawler\Processing\Validation\ValidatorInterface;

class DebugValidator implements ValidatorInterface
{
    protected $baseUri;

    public function __construct(string $baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function isValid(\Psr\Http\Message\UriInterface $target)
    {
        return (substr_compare((string) $target, $this->baseUri, 0, strlen($this->baseUri)) === 0);
    }
}
```

If we now add this validator to our crawler before invocation, it should stop immediately after processing the first page as the link on it leads to another domain.

``` php
$crawler->addValidator(new DebugValidator('http://example.com/'));
```

> machine:~ testuser$ php test.php  
> Crawled: http://example.com/  
> machine:~ testuser$  

### Summary

The basic usage of this library shall be clear at this point.
Have a close look on the documentation, the source code and the `Tests/` folder for more information and some more advanced examples.

## Reference

### Configuration

The crawler can be configured through a configuration class.

(_to be written_)

### Validators

This package ships with several predefined validators that are commonly used.

#### `RobotsTxtValidator`

This validator loads the `robots.txt` from the server to be accessed and matches received uris against these restrictions.
It returns false only if access to the uri is forbidden by these restrictions.
If no `robots.txt`-file could be loaded all uris are considered valid.

See http://www.robotstxt.org/ for more information on `robots.txt`-files.

##### Usage

This validator is not meant to be used directly.
Instead enable it by setting the `$ignoreRobots` property in your configuration.  
(see the section above for more information)

#### `DomainWhitelistValidator`

This validator only allows uris, that are on exactly the same domain as the base uri used to start the crawl.

##### Example

If `http://www.example.com/` was our base uri to crawl, the following uris would be considered valid:

- `http://www.example.com/some-page.html`
- `http://www.example.com/assets/img/my-cool-image.jpg`
- `https://www.example.com/`
- `https://www.example.com/#section`
- `https://www.example.com/?q=somequery`

Counterwise to the before examples, the following will be considered invalid:

- `http://example.com/`
- `http://subdomain.example.com/`
- `http://www.google.com/`

##### Usage

To make use of this validator, create an instance of it and add it to the crawler:

``` php
use MCStreetguy\Crawler\Processing\Validation\Core\DomainWhitelistValidator;

$baseUri = 'http://www.example.com/';
$domainValidator = new DomainWhitelistValidator($baseUri);

$crawler->addValidator($domainValidator);
```

#### `SubDomainWhitelistValidator`

This validator only allows uris, that are on exactly the same domain as the base uri used to start the crawl, or at least on a subdomain of it.

**Please note** that the `www.` preceding most urls is considered not to be part of the domain (in contrast to RFC regulations).
This is due to the fact, that most pages use it as a scheme-designator, even though it's actually a subdomain.
It get's removed from the base uri for host comparison, if it is present.  
This has no reductive effect on uri filtering, instead it enhances the probability that the crawler validates all subdomains properly.

##### Example

If `http://www.example.com/` was our base uri to crawl, the following uris would be considered valid:

- `http://www.example.com/some-page.html`
- `http://www.example.com/assets/img/my-cool-image.jpg`
- `https://www.example.com/`
- `https://www.example.com/#section`
- `https://www.example.com/?q=somequery`
- `https://subdomain.www.example.com/`
- `https://another.subdomain.www.example.com/`
- `https://sub.www.example.com/my/path`

Counterwise to the before examples, the following will be considered invalid:

- `http://example.com/`
- `http://subdomain.example.com/`
- `http://www.subdomain.example.com/`
- `http://www.google.com/`

##### Usage

To make use of this validator, create an instance of it and add it to the crawler (as with the `DomainWhitelistValidator` before):

``` php
use MCStreetguy\Crawler\Processing\Validation\Core\SubDomainWhitelistValidator;

$baseUri = 'http://www.example.com/';
$subdomainValidator = new SubDomainWhitelistValidator($baseUri);

$crawler->addValidator($subdomainValidator);
```
