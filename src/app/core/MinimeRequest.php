<?php
namespace app\core;

/**
 *
 * @author jb
 */
Class MinimeRequest
{
  /**
   * @var \app\Application
   */
  protected $app = null;
  /**
   * Request method (lowercase)
   * @var string
   */
  protected $method = null;
  /**
   * Request uri (lowercase)
   * @var string
   */
  protected $uri = null;
  /**
   *
   * @var string
   */
  protected $current_format = null;

  /**
   * 'http' or 'https'
   * @var null|string
   */
  protected $request_scheme = null;

  /**
   * request parameters
   * @var array
   */
  protected $parameters = array();

  public function __construct(\app\Application $app)
  {
    $this->app        = $app;
    $this->uri        = strtolower($_SERVER['REQUEST_URI']);
    $this->method     = strtolower($_SERVER['REQUEST_METHOD']);
    $this->remoteHost = $this->get_client_ip_server('0.0.0.0'); // key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    $this->parameters = $_REQUEST;

    if ((!empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
          (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
          (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')) {
        $this->request_scheme = 'https';
    } else {
        $this->request_scheme = 'http';
    }

    //set in Router::matchRoute()
    //$this->findFormat();
  }

    // Function to get the client ip address
    public function get_client_ip_server($default = 'UNKNOWN') {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = $default;

        return $ipaddress;
    }

  /**
   *
   * @return string
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * finds format (html|json|..) in the REQUEST_URI
   */
  protected function findFormat()
  {
    $request_uri = $this->getUri();
    if(strpos($request_uri, '?'))
    {
      $request_uri = substr($request_uri, 0, strpos($request_uri, '?'));
    }
    if(strpos($request_uri, '.'))
    {
      $dotpos         = strrpos($request_uri, '.');
      $current_format = substr($request_uri, $dotpos+1);
      $request_uri    = substr($request_uri, 0, $dotpos);
      $this->setFormat($current_format);
    }
  }

  /**
   * .html|json|xml
   * @param string $format
   */
  public function setFormat($format)
  {
    $this->current_format = $format;
  }

  /**
   *
   * @return string
   */
  public function getFormat()
  {
    return $this->current_format;
  }

  /**
   *
   * @return string
   */
  public function getUri()
  {
    return $this->uri;
  }

  /**
   *
   * @return string
   */
  public function getRemoteHost()
  {
      return $this->remoteHost;
  }

  /**
   *
   * @param string $p_name
   * @param string $p_name
   */
  public function setParameter($p_name, $p_value)
  {
    $this->parameters[$p_name] = $p_value;
  }

  /**
   *
   * @param string $p_name
   * @param string $default
   * @return Ambigous <string, multitype:>
   */
  public function getParameter($p_name, $default = null)
  {
    return (array_key_exists($p_name, $this->parameters)) ? $this->parameters[$p_name] : $default;
  }

  /**
   *
   * @return array:
   */
  public function getParameters()
  {
    return $this->parameters;
  }

    /**
     * @return string
     */
    public function getRequestScheme()
    {
        return $this->request_scheme;
    }
}