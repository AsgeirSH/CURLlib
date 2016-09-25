CURLlib
=======

Like pretty much everyone else who uses CURL regularly, I've made a wrapper for it. But unlike most people, I've based it on an interface that anyone can extend. This means that for most of my other projects, the actual CURL-wrapper used can be changed on a whim.

The CURLInterface is deliberately designed to not be exhaustive, but allow very sparse implementations. Let's face it, I don't have the time to implement every little thing from the get-go, so I designed my interface and my CURL-wrapper to implement what I needed, and allow simple extension when that is needed later.

Installation
------------
Install via composer:
Add this to `composer.json`:
```
"repositories": [
	{
		"url": "https://github.com/AsgeirSH/curllib"
		"type": "git"
	}
],
```
and run
```
composer require asgeirsh/curllib:dev-master
``` 

Usage
-----
The actual library is also designed to be simple to use, yet still allow flexibility for those occasions where you need more power. This means that a very simple usecase can be reduced to two lines:
```
 $curl = new CURL('http://example.com/');
 $result = $curl->exec();
```
This is a GET-request, with a standard timeout of 12 seconds, that returns an object if the result is JSON or serialized data. Otherwise it returns the full HTML returned from CURL.

It is also possible to set headers, timeouts and port to use:
```
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts/1')
	->setTimeout(5)
	->addHeader("X-Auth-Key:xxxxxx")
	->addHeader("X-Auth-Email:example@example.com")
	->setPort(80)
	->exec();
```

To post data, just use `setData()`. `setData()` adds POST-data if the request-type is POST, or builds a querystring for any other request-type (i.e. `setData()` can also be used to add GET-parameters to a standard request).
```
$curl = new CURL();
$result = $curl->setURL('http://jsonplaceholder.typicode.com/posts')
	->setMethod('POST')
	->setData(array('title' => 'foo', 'body' => 'bar', 'userId' => 1))
	->exec();
```
All of the above sourcecode can be run as-is (infact, they run like that in my [Example](examples/Curl_basic.php)). Thanks to the fabulous [Typicode](https://github.com/typicode) for hosting the JSON Placeholder Fake REST API website!

License
-------
This project uses the [MIT License](LICENSE), which basicly means that you can do pretty much whatever you want with this (`to deal in the Software without restriction`). If you have suggestions, feel free to add an [issue](issues/) or a [Pull Request](pulls/)!

