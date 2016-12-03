<?php
namespace Student\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * StudentDetails
 *
 * @ORM\Table(name="student_referral_info")
 * @ORM\Entity
 */
class StudentReferralInfo
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
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/ 
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="strefcode", type="string", length=20, nullable=true)
     */
    private $strefcode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="refname", type="string", length=50, nullable=true)
     */
    private $refname;

    /**
     * @var string
     *
     * @ORM\Column(name="refmode", type="string", length=10, nullable=true)
     */
    private $refmode;

    /**
     * @var string
     *
     * @ORM\Column(name="refphone", type="string", length=12, nullable=true)
     */
    private $refphone;

    /**
     * @var string
     *
     * @ORM\Column(name="refemail", type="string", length=100, nullable=true)
     */
    private $refemail;

    /**
     * @var integer
     *
     * @ORM\Column(name="refstatus", type="integer", length=2, nullable=true)
     */
    private $refstatus;
    
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
     * @return StudentReferralInfo
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
    * Set strefcode
    * @param string $strefcode
    * @return StudentReferralInfo
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
    * Set refname
    * @param string $refname
    * @return StudentReferralInfo
    */
    public function setRefname($refname){
        $this->refname = $refname;
        return $this;
    }

    /**
    * Get refname
    * @return string
    */
    public function getRefname(){
        return $this->refname;
    }

    /**
    * Set refphone
    * @param string $refphone
    * @return StudentReferralInfo
    */
    public function setRefphone($refphone){
        $this->refphone = $refphone;
        return $this;
    }

    /**
    * Get refphone
    * @return string
    */
    public function getRefphone(){
        return $this->refphone;
    }

    /**
     * Set refemail
     *
     * @param string $refemail
     *
     * @return StudentReferralInfo
     */
    public function setRefemail($refemail)
    {
        $this->refemail = $refemail;

        return $this;
    }

    /**
     * Get refemail
     *
     * @return string
     */
    public function getRefemail()
    {
        return $this->refemail;
    }

    /**
     * Set refmode
     *
     * @param string $refmode
     *
     * @return StudentReferralInfo
     */
    public function setRefmode($refmode)
    {
        $this->refmode = $refmode;

        return $this;
    }

    /**
     * Get refmode
     *
     * @return string
     */
    public function getRefmode()
    {
        return $this->refmode;
    }

    /**
     * Set refstatus
     *
     * @param boolean $refstatus
     *
     * @return StudentReferralInfo
     */
    public function setRefstatus($refstatus)
    {
        $this->refstatus = $refstatus;

        return $this;
    }

    /**
     * Get refstatus
     *
     * @return boolean
     */
    public function getRefstatus()
    {
        return $this->refstatus;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return StudentReferralInfo
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
     * @return StudentReferralInfo
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
     * @return StudentReferralInfo
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
        unset($vars['referredId']);
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
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /** @ORM\PreUpdate */
    public function preUpdate() {
        $this->updatedAt = new \DateTime();
    }
}
