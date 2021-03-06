<?php
class Router
{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(IRequest $request) {
        $this->request = $request;
    }

    /**
     * Dynamic Method
     * @param  [string] $name [HTTP Method]
     * @param  [Array] $args
     */
    function __call($name, $args) {
        list($route, $method) = $args; 
        if(!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }
        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }
    
    /**
     * Removes trailing forward slashes from the right of the route.
     * @param route (string)
     */
    private function formatRoute($route) {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    /**
     * Handle invalid request
     * @return [type] [description]
     */
    private function invalidMethodHandler() {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler() {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        // echo strtolower($this->request->requestMethod); 
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        
        // print_r($methodDictionary);

        $formatedRoute = $this->formatRoute($this->request->requestUri);

        // print_r($formatedRoute);

        $method = $methodDictionary[$formatedRoute];

        // print_r($method);
        
        if(is_null($method)) {
            $this->defaultRequestHandler();
            // return;
        }
        echo call_user_func_array($method, array($this->request));
    }

    function __destruct(){
        $this->resolve();
    }
}