<?php

namespace AsgeirSH\CURLlib;

### A generic CURL interface which can be used to allow different CURL-implementations being used in projects.
## Deliberately not exhaustive, as not all projects need every setting, like user-agent editing etc. This may be implemented if wanted.
interface CURLInterface {

	### Construct the CURLer object.
	## Takes optional arguments:
	# URL - the URL the request is performed against. This may also be set via setURL (which is called here as well).
	# METHOD - the method to be used. May also be set via setMethod (which is called here as well).
	public function __construct($url = null, $method = null);

	### Sets the request URL.
	public function setURL($url);

	### Sets the port the request is made to.
	public function setPort($port);

	### Sets the request method used.
	## Different implementations may allow only a subset of the standard HTTP methods.
	## Default method should be GET.
	public function setMethod($method);

	### Adds a header to the request.
	public function addHeader($header);

	### Sets the request data, if any. 
	public function setData(array $data);

	### Sets the timeout allowed before control should be returned to the caller.
	public function setTimeout($timeout);

	### Actually runs the request and returns data.
	public function exec();

	### Returns true if an error has occurred during the request.
	public function hasError();

	### Gets an array of errors, if any are defined.
	public function getErrors();

	### Close resources.
	public function close();

}