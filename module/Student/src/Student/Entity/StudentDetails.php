<?php
namespace Student\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * StudentDetails
 *
 * @ORM\Table(name="student_details")
 * @ORM\Entity
 */
class StudentDetails
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="\Application\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id",  unique=true)
     **/ 
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=20, nullable=true)
     */
    private $gender;
    
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=100, nullable=true)
     */
    private $middleName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private $lastName;

    /**
     * @var \Date
     *
     * @ORM\Column(name="birthdate", type="date", nullable=true)
     */
    private $birthdate;

    
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=12, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="mothername", type="string", length=100, nullable=true)
     */
    private $mothername;

    /**
     * @var string
     *
     * @ORM\Column(name="fathername", type="string", length=100, nullable=true)
     */
    private $fathername;
    
    /**
     * @var string
     *
     * @ORM\Column(name="religion", type="string", length=50, nullable=true)
     */
    private $religion;
    
    /**
     * @var string
     *
     * @ORM\Column(name="caste", type="string", length=50, nullable=true)
     */
    private $caste;
    
    /**
     * @var string
     *
     * @ORM\Column(name="aadhaarnum", type="string", length=50, nullable=true)
     */
    private $aadhaarnum;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", length=65535, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=80, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=50, nullable=true)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="pin", type="integer", nullable=true)
     */
    private $pin;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=50, nullable=true)
     */
    private $country = 'India';

    /**
     * @var integer
     *
     * @ORM\Column(name="class_grade", type="integer", nullable=true)
     */
    private $classGrade;

    /**
     * @var string
     *
     * @ORM\Column(name="school_colg", type="string", length=100, nullable=true)
     */
    private $schoolColg;
    
    /**
     * @var string
     *
     * @ORM\Column(name="schcol_addr", type="string", length=255, nullable=true)
     */
    private $schcolAddr;

    /**
     * @var integer
     *
     * @ORM\Column(name="passyear", type="integer", nullable=true)
     */
    private $passyear;

    /**
     * @var string
     *
     * @ORM\Column(name="board_univ", type="string", length=100, nullable=true)
     */
    private $boardUniv;

    /**
     * @var string
     *
     * @ORM\Column(name="scmedium", type="string", length=30, nullable=true)
     */
    private $scmedium;

    /**
     * @var string
     *
     * @ORM\Column(name="strefcode", type="string", length=20, nullable=true)
     */
    private $strefcode;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paystatus", type="boolean", nullable=false)
     */
    private $paystatus = '0';
    
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return StudentDetails
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return StudentDetails
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return StudentDetails
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return StudentDetails
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set phone
     *
     * @param integer $phone
     *
     * @return StudentDetails
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return integer
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set mothername
     *
     * @param string $mothername
     *
     * @return StudentDetails
     */
    public function setMothername($mothername)
    {
        $this->mothername = $mothername;

        return $this;
    }

    /**
     * Get mothername
     *
     * @return string
     */
    public function getMothername()
    {
        return $this->mothername;
    }

    /**
     * Set fathername
     *
     * @param string $fathername
     *
     * @return StudentDetails
     */
    public function setFathername($fathername)
    {
        $this->fathername = $fathername;

        return $this;
    }

    /**
     * Get fathername
     *
     * @return string
     */
    public function getFathername()
    {
        return $this->fathername;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return StudentDetails
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return StudentDetails
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return StudentDetails
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     *
     * @return StudentDetails
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return integer
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return StudentDetails
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set classGrade
     *
     * @param integer $classGrade
     *
     * @return StudentDetails
     */
    public function setClassGrade($classGrade)
    {
        $this->classGrade = $classGrade;

        return $this;
    }

    /**
     * Get classGrade
     *
     * @return integer
     */
    public function getClassGrade()
    {
        return $this->classGrade;
    }

    /**
     * Set schoolColg
     *
     * @param string $schoolColg
     *
     * @return StudentDetails
     */
    public function setSchoolColg($schoolColg)
    {
        $this->schoolColg = $schoolColg;

        return $this;
    }

    /**
     * Get schoolColg
     *
     * @return string
     */
    public function getSchoolColg()
    {
        return $this->schoolColg;
    }

    /**
     * Set passyear
     *
     * @param integer $passyear
     *
     * @return StudentDetails
     */
    public function setPassyear($passyear)
    {
        $this->passyear = $passyear;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getPassyear()
    {
        return $this->passyear;
    }

    /**
     * Set boardUniv
     *
     * @param string $boardUniv
     *
     * @return StudentDetails
     */
    public function setBoardUniv($boardUniv)
    {
        $this->boardUniv = $boardUniv;

        return $this;
    }

    /**
     * Get boardUniv
     *
     * @return string
     */
    public function getBoardUniv()
    {
        return $this->boardUniv;
    }

    /**
     * Set scmedium
     *
     * @param string $scmedium
     *
     * @return StudentDetails
     */
    public function setScmedium($scmedium)
    {
        $this->scmedium = $scmedium;

        return $this;
    }

    /**
     * Get scmedium
     *
     * @return string
     */
    public function getScmedium()
    {
        return $this->scmedium;
    }
    
    /**
     * Set paystatus
     *
     * @param boolean $paystatus
     *
     * @return StudentDetails
     */
    public function setPaystatus($paystatus)
    {
        $this->paystatus = $paystatus;

        return $this;
    }

    /**
    * Set strefcode
    * @param string $strefcode
    * @return StudentDetails
    */
    public function setStrefcode($strefcode){
        $this->strefcode = $strefcode;
        return $this;
    }

    /**
    * Get strefcode
    * @return string
    */
    public function getStrefcode(){
        return $this->strefcode;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getPaystatus()
    {
        return $this->paystatus;
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
     * Set status
     *
     * @param boolean $status
     *
     * @return StudentDetails
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Magic getter to expose protected properties.
     *
     * @param string $property
     * @return mixed
     */
    public function __get($property) 
    {
        return $this->$property;
    }
 
    /**
     * Magic setter to save protected properties.
     *
     * @param string $property
     * @param mixed $value
     */
    public function __set($property, $value) 
    {
        $this->$property = $value;
    }
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function getArrayCopy() 
    {
        return get_object_vars($this);
    }
 
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray() {
        $vars = get_object_vars($this);
        unset($vars['userId']);
        unset($vars['em']);
        return $vars;
    }

    public function exchangeArray($data = array()) 
    {
        foreach($data as $key => $value){
            $this->$key = $value;
        }
        return $this;
    }

    /** @ORM\PrePersist */
    public function prePersist() {
        $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
    }

    /** @ORM\PreUpdate */
    public function preUpdate() {
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
    }
}
