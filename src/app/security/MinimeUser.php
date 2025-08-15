<?php
namespace app\security;

/**
 *
 * @author jb
 */
Class MinimeUser
{
  protected $id         = null;
    /**
     * @var authentication level
     */
  protected $authenticated = null;
    /**
     * @var timestamp of the athentication
     */
  protected $authenticatedAt = null;
    /**
     * @var 0|1 - to logaout after some time or not
     */
  protected $keepForever = 0;
  protected $credentials = array();
  protected $user = null;

  /**
   *
   * @return
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   *
   * @param $id
   */
  public function setId($id)
  {
      $this->id = $id;
  }
  /**
   *
   * @return
   */
  public function getAuthenticated()
  {
      return $this->authenticated;
  }

  /**
   *
   * @param $authenticated
   */
  public function setAuthenticated($authenticated)
  {
      $this->authenticated = $authenticated;
  }

    /**
     * @return timestamp
     */
    public function getAuthenticatedAt()
    {
        return $this->authenticatedAt;
    }

    /**
     * @param timestamp $authenticatedAt
     */
    public function setAuthenticatedAt($authenticatedAt)
    {
        $this->authenticatedAt = $authenticatedAt;
    }

    /**
     * @return int
     */
    public function getKeepForever()
    {
        return $this->keepForever;
    }

    /**
     * @param int $keepForever
     */
    public function setKeepForever($keepForever)
    {
        $this->keepForever = $keepForever;
    }

  /**
   *
   * @return
   */
  public function getCredentials()
  {
      return $this->credentials;
  }

  /**
   *
   * @param $credentials
   */
  public function setCredentials($credentials)
  {
      $this->credentials = array_map('strtoupper', $credentials);
  }

  /**
   *
   * @param $credential
   */
  public function setCredential($credential)
  {
      $this->credentials[] = strtoupper($credential);
      $this->credentials = array_unique($this->credentials);
  }

  /**
   *
   * @return
   */
  public function hasCredential($credential)
  {
    return in_array(strtoupper($credential), $this->credentials);
  }

    /**
     * @return null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param null $backendUser
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}

