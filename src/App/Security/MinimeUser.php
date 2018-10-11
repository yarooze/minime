<?php
namespace App\Security;

/**
 *
 * @author jb
 */
Class MinimeUser
{
  protected $id         = null;
  protected $authenticated = null;
  protected $keepForever = 0;
  protected $credential = array();
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
      return $this->credential;
  }

  /**
   *
   * @param $credentials
   */
  public function setCredentials($credential)
  {
      $this->credential = $credential;
  }

  /**
   *
   * @param $credential
   */
  public function setCredential($credential)
  {
      $this->credential[] = $credential;
      $this->credential = array_unique($this->credential);
  }

  /**
   *
   * @return
   */
  public function hasCredential($credential)
  {
    return in_array($credential, $this->credential);
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

