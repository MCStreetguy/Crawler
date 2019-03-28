# MCStreetguy/Crawler

## Table of Contents

* [ContentTooLargeException](#contenttoolargeexception)
* [Crawler](#crawler)
    * [__construct](#__construct)
    * [execute](#execute)
    * [getTarget](#gettarget)
    * [getConfiguration](#getconfiguration)
    * [setConfiguration](#setconfiguration)
    * [getCrawlQueue](#getcrawlqueue)
    * [setCrawlQueue](#setcrawlqueue)
    * [getProcessors](#getprocessors)
    * [addProcessor](#addprocessor)
    * [addProcessors](#addprocessors)
    * [setProcessors](#setprocessors)
    * [getValidators](#getvalidators)
    * [addValidator](#addvalidator)
    * [addValidators](#addvalidators)
    * [setValidators](#setvalidators)
* [CrawlerException](#crawlerexception)
* [CrawlQueue](#crawlqueue)
    * [__construct](#__construct-1)
    * [add](#add)
    * [has](#has)
    * [hasAny](#hasany)
    * [revoke](#revoke)
    * [hasNext](#hasnext)
    * [getNext](#getnext)
    * [getAll](#getall)
    * [getAllPending](#getallpending)
    * [getAllFinished](#getallfinished)
    * [clear](#clear)
    * [clearPending](#clearpending)
    * [clearFinished](#clearfinished)
    * [finish](#finish)
* [CrawlResult](#crawlresult)
    * [__construct](#__construct-2)
    * [getIdentifier](#getidentifier)
    * [getUri](#geturi)
    * [getResponse](#getresponse)
    * [getLinks](#getlinks)
    * [success](#success)
    * [failed](#failed)
* [DefaultCrawlConfiguration](#defaultcrawlconfiguration)
    * [getMaximumCrawlCount](#getmaximumcrawlcount)
    * [getMaximumDepth](#getmaximumdepth)
    * [getMaximumResponseSize](#getmaximumresponsesize)
    * [validateResponseSize](#validateresponsesize)
    * [getRequestDelay](#getrequestdelay)
    * [getRequestTimeout](#getrequesttimeout)
    * [buildGuzzleRequestOptions](#buildguzzlerequestoptions)
* [NullStream](#nullstream)
    * [close](#close)
    * [detach](#detach)
    * [getSize](#getsize)
    * [tell](#tell)
    * [eof](#eof)
    * [isSeekable](#isseekable)
    * [seek](#seek)
    * [rewind](#rewind)
    * [isWritable](#iswritable)
    * [write](#write)
    * [isReadable](#isreadable)
    * [read](#read)
    * [getContents](#getcontents)
    * [getMetadata](#getmetadata)
* [ResultSet](#resultset)
    * [__construct](#__construct-3)
    * [toArray](#toarray)
    * [getStats](#getstats)
    * [getUrisCrawled](#geturiscrawled)
    * [getUrisSucceeded](#geturissucceeded)
    * [getFailedUris](#getfaileduris)
    * [getDroppedUris](#getdroppeduris)
    * [toNodeTree](#tonodetree)
* [Seeker](#seeker)
    * [browse](#browse)

## ContentTooLargeException

The content of the response is too large to be downloaded.

This exception signals the crawler to continue with the process but drop the response body.

* Full name: \MCStreetguy\Crawler\Exceptions\ContentTooLargeException
* Parent class: \MCStreetguy\Crawler\Exceptions\CrawlerException


## Crawler

The main class of the web-crawler.



* Full name: \MCStreetguy\Crawler\Crawler


### __construct

Constructs a new instance.

```php
Crawler::__construct( \MCStreetguy\Crawler\Config\CrawlConfigurationInterface|null $config = null, \MCStreetguy\Crawler\Queue\CrawlQueueInterface|null $queue = null, array&lt;mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface&gt; $processors = array(), array&lt;mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface&gt; $validators = array() ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **\MCStreetguy\Crawler\Config\CrawlConfigurationInterface&#124;null** | The configuration object to use for the crawler |
| `$queue` | **\MCStreetguy\Crawler\Queue\CrawlQueueInterface&#124;null** | The crawl queue to use for the crawler |
| `$processors` | **array<mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface>** | The processors to use for the crawl-results |
| `$validators` | **array<mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface>** | The validators to use for the crawler |




---

### execute

Execute the crawler for the given target.

```php
Crawler::execute( string|\Psr\Http\Message\UriInterface $target ): \MCStreetguy\Crawler\Result\ResultSet
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$target` | **string&#124;\Psr\Http\Message\UriInterface** | The target to crawl |




---

### getTarget

Get the base target uri of the crawler.

```php
Crawler::getTarget(  ): \Psr\Http\Message\UriInterface|null
```







---

### getConfiguration

Get the configuration object of the crawler.

```php
Crawler::getConfiguration(  ): \MCStreetguy\Crawler\Config\CrawlConfigurationInterface
```







---

### setConfiguration

Set the configuration object of the crawler.

```php
Crawler::setConfiguration( \MCStreetguy\Crawler\Config\CrawlConfigurationInterface $config ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$config` | **\MCStreetguy\Crawler\Config\CrawlConfigurationInterface** |  |




---

### getCrawlQueue

Get the crawl queue.

```php
Crawler::getCrawlQueue(  ): \MCStreetguy\Crawler\Queue\CrawlQueueInterface
```







---

### setCrawlQueue

Set the crawl queue to use.

```php
Crawler::setCrawlQueue( \MCStreetguy\Crawler\Queue\CrawlQueueInterface $queue ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$queue` | **\MCStreetguy\Crawler\Queue\CrawlQueueInterface** |  |




---

### getProcessors

Get the registered processors.

```php
Crawler::getProcessors(  ): array&lt;mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface&gt;
```







---

### addProcessor

Add a processor instance to the crawler.

```php
Crawler::addProcessor( \MCStreetguy\Crawler\Processing\ProcessorInterface $processor ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$processor` | **\MCStreetguy\Crawler\Processing\ProcessorInterface** | The processor to add |




---

### addProcessors

Add multiple processors to the crawler.

```php
Crawler::addProcessors( array&lt;mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface&gt; $processors ): void
```

Duplicate processors will be overridden by the new one.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$processors` | **array<mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface>** | The processors to add |




---

### setProcessors

Set the processor array directly.

```php
Crawler::setProcessors( array&lt;mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface&gt; $processors ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$processors` | **array<mixed,\MCStreetguy\Crawler\Processing\ProcessorInterface>** | The processors to set |




---

### getValidators

Get the registered validators.

```php
Crawler::getValidators(  ): array&lt;mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface&gt;
```







---

### addValidator

Add a validator instance to the crawler.

```php
Crawler::addValidator( \MCStreetguy\Crawler\Processing\ProcessorInterface $validator ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$validator` | **\MCStreetguy\Crawler\Processing\ProcessorInterface** | The validator to add |




---

### addValidators

Add multiple validators to the crawler.

```php
Crawler::addValidators( array&lt;mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface&gt; $validators ): void
```

Duplicate validators will be overridden by the new one.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$validators` | **array<mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface>** | The validators to add |




---

### setValidators

Set the validator array directly.

```php
Crawler::setValidators( array&lt;mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface&gt; $validators ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$validators` | **array<mixed,\MCStreetguy\Crawler\Processing\Validation\ValidatorInterface>** | The validators to set |




---

## CrawlerException

A general exception class.



* Full name: \MCStreetguy\Crawler\Exceptions\CrawlerException
* Parent class: 


## CrawlQueue

The default crawl queue.



* Full name: \MCStreetguy\Crawler\Queue\CrawlQueue
* This class implements: \MCStreetguy\Crawler\Queue\CrawlQueueInterface


### __construct

Constructs a new instance

```php
CrawlQueue::__construct(  ): void
```

Constructs a new instance





---

### add

Adds an uri to the queue.

```php
CrawlQueue::add( \Psr\Http\Message\UriInterface $uri ): void
```

Adds an uri to the queue.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri to add |




---

### has

Check if the given uri is already in the queue.

```php
CrawlQueue::has( \Psr\Http\Message\UriInterface $uri ): boolean
```

Check if the given uri is already in the queue.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri to check for |


**Return Value:**

If the uri has been found



---

### hasAny

Check if the given uri is already in the queue, ignoring if it is finished already.

```php
CrawlQueue::hasAny( \Psr\Http\Message\UriInterface $uri ): boolean
```

Check if the given uri is already in the queue, ignoring if it is finished already.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri to check for |


**Return Value:**

If the uri has been found



---

### revoke

Revoke the given uri from the queue if possible.

```php
CrawlQueue::revoke( \Psr\Http\Message\UriInterface $uri ): void
```

Revoke the given uri from the queue if possible.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri to revoke |




---

### hasNext

Check if there is another uri in the queue.

```php
CrawlQueue::hasNext(  ): boolean
```

Check if there is another uri in the queue.



**Return Value:**

If there is a next element



---

### getNext

Get the next uri in this queue.

```php
CrawlQueue::getNext(  ): null|\Psr\Http\Message\UriInterface
```

Get the next uri in this queue.



**Return Value:**

The next uri element (if available)



---

### getAll

Get all uris in this queue.

```php
CrawlQueue::getAll(  ): array&lt;mixed,\Psr\Http\Message\UriInterface&gt;
```

Get all uris in this queue.



**Return Value:**

The uris in this queue



---

### getAllPending

Get all pending uris in this queue.

```php
CrawlQueue::getAllPending(  ): array&lt;mixed,\Psr\Http\Message\UriInterface&gt;
```

Get all pending uris in this queue.



**Return Value:**

The uris in this queue



---

### getAllFinished

Get all finished uris in this queue.

```php
CrawlQueue::getAllFinished(  ): array&lt;mixed,\Psr\Http\Message\UriInterface&gt;
```

Get all finished uris in this queue.



**Return Value:**

The uris in this queue



---

### clear

Clear all uris from the queue.

```php
CrawlQueue::clear(  ): void
```

Clear all uris from the queue.





---

### clearPending

Clear all pending uris from the queue.

```php
CrawlQueue::clearPending(  ): void
```

Clear all pending uris from the queue.





---

### clearFinished

Clear all finished uris from the queue.

```php
CrawlQueue::clearFinished(  ): void
```

Clear all finished uris from the queue.





---

### finish

Mark an uri as finished.

```php
CrawlQueue::finish( \Psr\Http\Message\UriInterface $uri ): void
```

Mark an uri as finished.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri to mark as finished |




---

## CrawlResult

Represents a crawl result.



* Full name: \MCStreetguy\Crawler\Result\CrawlResult


### __construct

Constructs a new instance.

```php
CrawlResult::__construct( \Psr\Http\Message\UriInterface $uri, \Psr\Http\Message\ResponseInterface $response, null|array&lt;mixed,\Psr\Http\Message\UriInterface&gt; $links = array() ): void
```

Constructs a new instance.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri of this CrawlResult |
| `$response` | **\Psr\Http\Message\ResponseInterface** | The response of the request to the given uri |
| `$links` | **null&#124;array<mixed,\Psr\Http\Message\UriInterface>** | All links found within the response |




---

### getIdentifier

Get the UUID representing this result

```php
CrawlResult::getIdentifier(  ): string
```

Get the UUID representing this result





---

### getUri

Get the uri of this result.

```php
CrawlResult::getUri(  ): \Psr\Http\Message\UriInterface
```

Get the uri of this result.





---

### getResponse

Get the response of the request to the uri of this result

```php
CrawlResult::getResponse(  ): \Psr\Http\Message\ResponseInterface
```

Get the response of the request to the uri of this result





---

### getLinks

Get the links found within the response.

```php
CrawlResult::getLinks(  ): array&lt;mixed,\Psr\Http\Message\UriInterface&gt;
```

Get the links found within the response.





---

### success

Check if the bound request was successful.

```php
CrawlResult::success(  ): boolean
```

Check if the bound request was successful.





---

### failed

Check if the bound request was a failure.

```php
CrawlResult::failed(  ): boolean
```

Check if the bound request was a failure.





---

## DefaultCrawlConfiguration

The default crawl configuration class.



* Full name: \MCStreetguy\Crawler\Config\DefaultCrawlConfiguration
* This class implements: \MCStreetguy\Crawler\Config\CrawlConfigurationInterface


### getMaximumCrawlCount

Get the maximum crawl count.

```php
DefaultCrawlConfiguration::getMaximumCrawlCount(  ): integer
```

Get the maximum number of crawl requests that may be performed.





---

### getMaximumDepth

Get the maximum depth.

```php
DefaultCrawlConfiguration::getMaximumDepth(  ): integer
```

Get the maximum depth of pages that the crawler may follow.





---

### getMaximumResponseSize

Get the maximum response size.

```php
DefaultCrawlConfiguration::getMaximumResponseSize(  ): integer
```

Get the maximum size in bytes that is allowed for crawl-responses.





---

### validateResponseSize

Validate the given response in terms of content size.

```php
DefaultCrawlConfiguration::validateResponseSize( \Psr\Http\Message\ResponseInterface $response ): void
```

Validate the given response in terms of content size.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$response` | **\Psr\Http\Message\ResponseInterface** |  |




---

### getRequestDelay

Get the delay in milliseconds before each request.

```php
DefaultCrawlConfiguration::getRequestDelay(  ): float
```

Get the delay in milliseconds before each request.





---

### getRequestTimeout

Get the timeout in seconds before aborting the request.

```php
DefaultCrawlConfiguration::getRequestTimeout(  ): integer
```

Get the timeout in seconds before aborting the request.





---

### buildGuzzleRequestOptions

Build up the guzzle request options array.

```php
DefaultCrawlConfiguration::buildGuzzleRequestOptions(  ): array
```

Build up the guzzle request options array.





---

## NullStream

A class that emulates a normal readable and writeable stream.

A class that emulates a normal readable and writeable stream, but does not write to or read from anywhere.
Writing to the stream causes it to confirm the operation but drop the data.
Reading from the stream returns nothing as it is always at the end (eof).
Seeking in the stream will raise an \RuntimeException as this is technically not possible.

* Full name: \MCStreetguy\Crawler\Miscellaneous\NullStream
* This class implements: \Psr\Http\Message\StreamInterface


### close

Closes the stream and any underlying resources.

```php
NullStream::close(  ): void
```

As the NullStream is a mocking object, this won't do anything.





---

### detach

Separates any underlying resources from the stream.

```php
NullStream::detach(  ): resource|null
```

After the stream has been detached, the stream is in an unusable state.

As the NullStream is a mocking object, this won't do anything.



**Return Value:**

Underlying PHP stream, if any



---

### getSize

Get the size of the stream if known.

```php
NullStream::getSize(  ): integer|null
```

As the NullStream is a mocking object, this will always return '0'.



**Return Value:**

Returns the size in bytes if known, or null if unknown.



---

### tell

Returns the current position of the file read/write pointer

```php
NullStream::tell(  ): integer
```

As the NullStream is a mocking object, this will always return '0'.



**Return Value:**

Position of the file pointer



---

### eof

Returns true if the stream is at the end of the stream.

```php
NullStream::eof(  ): boolean
```

As the NullStream is a mocking object, this will always return 'true'.





---

### isSeekable

Returns whether or not the stream is seekable.

```php
NullStream::isSeekable(  ): boolean
```

As the NullStream is a mocking object, this will always return 'false'.





---

### seek

Seek to a position in the stream.

```php
NullStream::seek( integer $offset, integer $whence = SEEK_SET )
```

As the NullStream is a mocking object, this will always throw a \RuntimeException.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$offset` | **integer** | Stream offset |
| `$whence` | **integer** | Specifies how the cursor position will be calculated
    based on the seek offset. Valid values are identical to the built-in
    PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
    offset bytes SEEK_CUR: Set position to current location plus offset
    SEEK_END: Set position to end-of-stream plus offset. |



**See Also:**

* http://www.php.net/manual/en/function.fseek.php 

---

### rewind

Seek to the beginning of the stream.

```php
NullStream::rewind(  )
```

If the stream is not seekable, this method will raise an exception;
otherwise, it will perform a seek(0).

As the NullStream is a mocking object, this will always throw a \RuntimeException.




**See Also:**

* \MCStreetguy\Crawler\Miscellaneous\NullStream::seek() * http://www.php.net/manual/en/function.fseek.php 

---

### isWritable

Returns whether or not the stream is writable.

```php
NullStream::isWritable(  ): boolean
```

As the NullStream is a mocking object, this will always return 'true'.





---

### write

Write data to the stream.

```php
NullStream::write( string $string ): integer
```

As the NullStream is a mocking object, this will always return the byte length of the input-string, as if the write was successful.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$string` | **string** | The string that is to be written. |


**Return Value:**

Returns the number of bytes written to the stream.



---

### isReadable

Returns whether or not the stream is readable.

```php
NullStream::isReadable(  ): boolean
```

As the NullStream is a mocking object, this will always return 'true'.





---

### read

Read data from the stream.

```php
NullStream::read( integer $length ): string
```

As the NullStream is a mocking object, this will always return an empty string.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$length` | **integer** | Read up to $length bytes from the object and return
    them. Fewer than $length bytes may be returned if underlying stream
    call returns fewer bytes. |


**Return Value:**

Returns the data read from the stream, or an empty string
    if no bytes are available.



---

### getContents

Returns the remaining contents in a string

```php
NullStream::getContents(  ): string
```

As the NullStream is a mocking object, this will always return an empty string.





---

### getMetadata

Get stream metadata as an associative array or retrieve a specific key.

```php
NullStream::getMetadata( string $key = null ): array|mixed|null
```

The keys returned are identical to the keys returned from PHP's
stream_get_meta_data() function.

As the NullStream is a mocking object, this will always return 'null'.


**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | **string** | Specific metadata to retrieve. |


**Return Value:**

Returns an associative array if no key is
    provided. Returns a specific key value if a key is provided and the
    value is found, or null if the key is not found.


**See Also:**

* http://php.net/manual/en/function.stream-get-meta-data.php 

---

## ResultSet

A set of CrawlResult objects.



* Full name: \MCStreetguy\Crawler\Result\ResultSet
* This class implements: \Iterator


### __construct

Constructs a new instance

```php
ResultSet::__construct(  $contents = array() ): void
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$contents` | **** |  |




---

### toArray

Get the contents of this ResultSet as an array.

```php
ResultSet::toArray(  ): array&lt;mixed,\MCStreetguy\Crawler\Result\CrawlResult&gt;
```

Get the contents of this ResultSet as an array.





---

### getStats

Get the stats of this ResultSet as an associative array.

```php
ResultSet::getStats(  ): array
```

Get the stats of this ResultSet as an associative array.





---

### getUrisCrawled

Get the number of uris that have been crawled totally.

```php
ResultSet::getUrisCrawled(  ): integer
```

Get the number of uris that have been crawled totally.





---

### getUrisSucceeded

Get the number of uris that have been crawled successfully.

```php
ResultSet::getUrisSucceeded(  ): integer
```

Get the number of uris that have been crawled successfully.





---

### getFailedUris

Get the number of uris that failed crawling.

```php
ResultSet::getFailedUris(  ): integer
```

Get the number of uris that failed crawling.





---

### getDroppedUris

Get the number of invalid uris that have been dropped.

```php
ResultSet::getDroppedUris(  ): integer
```

Get the number of invalid uris that have been dropped.





---

### toNodeTree

Convert this ResultSet to a Node tree structure.

```php
ResultSet::toNodeTree(  ): \Tree\Node\NodeInterface
```

Convert this ResultSet to a Node tree structure.



**Return Value:**

The root node of the tree



---

## Seeker

The seeker class is responsible for searching links within response bodies.



* Full name: \MCStreetguy\Crawler\Seeker


### browse

Browse the given input for further links.

```php
Seeker::browse( \Psr\Http\Message\UriInterface $uri, string|\Psr\Http\Message\ResponseInterface $input ): array&lt;mixed,\Psr\Http\Message\UriInterface&gt;
```




**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$uri` | **\Psr\Http\Message\UriInterface** | The uri belonging to the given response |
| `$input` | **string&#124;\Psr\Http\Message\ResponseInterface** | The input to search through |




---



--------
> This document was automatically generated from source code comments on 2019-03-28 using [phpDocumentor](http://www.phpdoc.org/) and [cvuorinen/phpdoc-markdown-public](https://github.com/cvuorinen/phpdoc-markdown-public)
