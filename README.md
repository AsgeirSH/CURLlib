CURLlib
=======

Like pretty much everyone else who uses CURL regularly, I've made a wrapper for it. But unlike most people, I've based it on an interface that anyone can extend. This means that for most of my other projects, the actual CURL-wrapper used can be changed on a whim.

The CURLInterface is deliberately designed to not be exhaustive, but allow very sparse implementations. Let's face it, I don't have the time to implement every little thing from the get-go, so I designed my interface and my CURL-wrapper to implement what I needed, and allow simple extension when that is needed later.

Installation
------------
To install via composer, simply run:
```
composer require asgeirsh/curllib:1.*
``` 

Usage
-----
The actual library is also designed to be simple to use, yet still allow flexibility for those occasions where you need more power. This means that a very simple usecase can be reduced to two lines:
```
 $curl = new CURL('http://example.com/');
 $result = $curl->exec();
```

This is a GET-request, with a standard timeout of 12 seconds, that returns an object if the result is JSON or serialized data. Otherwise it returns the full response returned from CURL.

As always, remember to add the namespace at the top of the file:
```
 use AsgeirSH\CURLlib\CURL;
```

It is also possible to set headers, timeouts and which port to use:
```
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts/1')
	->setTimeout(5)
	->addHeader("X-Auth-Key:xxxxxx")
	->addHeader("X-Auth-Email:example@example.com")
	->setPort(80)
	->exec();
```

To post data, just use `setData()`. `setData()` adds POST-data if the request-type is POST, or builds a querystring for any other request-type (i.e. `setData()` can also be used to add GET-parameters to a standard request). You can also add JSON here, as a string. JSON can also be added by `setJson()`.
```
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts')
	->setMethod('POST')
	->setData(array('title' => 'foo', 'body' => 'bar', 'userId' => 1))
	->exec();
```
All of the above sourcecode can be run as-is (infact, they run like that in my [Example](examples/Curl_basic.php)). Thanks to the fabulous [Typicode](https://github.com/typicode) for hosting the JSON Placeholder Fake REST API website!

For more examples of use, check out my repository [SBBapi](https://github.com/AsgeirSH/SSBapi) (in Norwegian). This project provides a very clean way of interacting with the [Norwegian Bureau of Statistics](http://ssb.no) open API in PHP.

Extension
---------
So you want to make your own CURL-wrapper based on this? Maybe you want methods for each method, like `->get($url)` or `->put($url)`? Either extend the CURL-class itself or make a totally different one which implements [CURLInterface](src/CURLInterface.php). 

All required methods are described in detail in the source code.

TODO
----
Things not yet implemented that I want to add:
- Checking the return code of a response.

License
-------
This project uses the [MIT License](LICENSE), which basicly means that you can do pretty much whatever you want with this (`to deal in the Software without restriction`). If you have suggestions, please feel free to add an [issue](https://github.com/AsgeirSH/CURLlib/issues/) or a [Pull Request](https://github.com/AsgeirSH/CURLlib/pulls/)!

