<?php
/**
 * BjyAuthorize Module (https://github.com/bjyoungblood/BjyAuthorize)
 *
 * @link https://github.com/bjyoungblood/BjyAuthorize for the canonical source repository
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
 
namespace Application\Entity;

use BjyAuthorize\Provider\Role\ProviderInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use ZfcUser\Entity\UserInterface;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class User implements UserInterface, ProviderInterface
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
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    protected $displayName;
    
    /**
     * @var bigint
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $phonenum;

    /**
     * @var string
     * @ORM\Column(type="string", length=128)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    protected $refcode;

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
     * @var int
     */
    protected $state;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Application\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;
    
    /**
     * @ORM\OneToMany(targetEntity="\Student\Entity\StudentDetails", mappedBy="userId", cascade={"persist","remove"})
     * */
    private $student;
    
    /**
     * @ORM\OneToMany(targetEntity="\Admin\Entity\ExamQuestions", mappedBy="createdBy", cascade={"persist","remove"})
     * */
    private $examquestion;
    
    /**
     * @ORM\OneToMany(targetEntity="\Student\Entity\StudentExamPaper", mappedBy="userId", cascade={"persist","remove"})
     * */
    private $studentexampaper;

    /**
     * @ORM\OneToMany(targetEntity="\Student\Entity\StudentExamStarted", mappedBy="userId", cascade={"persist","remove"})
     * */
    private $studentexamstarted;

    /**
     * @ORM\OneToMany(targetEntity="\Student\Entity\StudentReferralInfo", mappedBy="userId", cascade={"persist","remove"})
     * */
    private $studentreferralinfo;
    
    /**
     * Initialies the roles variable.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

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
     * Set id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = (int) $id;
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
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     *
     * @return void
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
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
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set refcode.
     *
     * @param string $refcode
     *
     * @return void
     */
    public function setRefcode($refcode)
    {
        $this->refcode = $refcode;
    }

    /**
     * Get refcode.
     *
     * @return string
     */
    public function getRefcode()
    {
        return $this->refcode;
    }

    /**
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     *
     * @return void
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }
    
    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return StudentDetails
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return StudentDetails
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray() {
        $vars = get_object_vars($this);
        if (array_key_exists('password', $vars)) {
            unset($vars['password']);
        }
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
