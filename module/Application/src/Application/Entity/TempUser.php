<?php

namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * StudentDetails
 *
 * @ORM\Table(name="temp_user")
 * @ORM\Entity
 */
class TempUser
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=true)
     */
    private $username;


    /**
     * @var string
     *
     * @ORM\Column(name="userpass", type="string", length=50, nullable=true)
     */
    private $userpass;

    /**
     * @var bigint
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $phonenum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '0';


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get phonenum
     * @return bigint
     */
    public function getPhonenum(){
        return $this->phonenum;
    }

    /**
     * Set phonenum
     * @param bigint $phonenum
     *
     */
     public function setPhonenum($phonenum){
         $this->phonenum = $phonenum;
     }

    /**
     * Get userpass.
     *
     * @return string
     */
    public function getUserpass()
    {
        return $this->userpass;
    }

    /**
     * Set userpass.
     *
     * @param string $userpass
     *
     * @return void
     */
    public function setUserpass($userpass)
    {
        $this->userpass = $userpass;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray() {
        $vars = get_object_vars($this);
        unset($vars['em']);
        return $vars;
    }

    public function exchangeArray($data = array()) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }

    /** @ORM\PrePersist */
    public function prePersist() {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /** @ORM\PreUpdate */
    public function preUpdate() {
        $this->updatedAt = new \DateTime();
    }
}
