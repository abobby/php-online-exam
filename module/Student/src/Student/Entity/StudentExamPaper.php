<?php

namespace Student\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * StudentExamPaper
 *
 * @ORM\Table(name="student_exam_paper")
 * @ORM\Entity
 */
class StudentExamPaper
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
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\ExamPaper")
     * @ORM\JoinColumn(name="exam_paper_id", referencedColumnName="id")
     **/ 

    private $examPaperId;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\ExamPaperSets")
     * @ORM\JoinColumn(name="exam_set_id", referencedColumnName="id")
     **/
    
    private $examSetId;

    /**
     * @var string
     *
     * @ORM\Column(name="exam_qstn_ids", type="text", length=65535, nullable=false)
     */
    private $examQstnIds;

    /**
     * @var string
     *
     * @ORM\Column(name="exam_qstn_status", type="text", length=65535, nullable=true)
     */
    private $examQstnStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="exam_qstn_options", type="text", length=65535, nullable=false)
     */
    private $examQstnOptions;

    /**
     * @var float
     *
     * @ORM\Column(name="total_score", type="float", precision=10, scale=0, nullable=false)
     */
    private $totalScore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = '1';
    
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
     * @return StudentExamPaper
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
     * Set examQstnIds
     *
     * @param string $examQstnIds
     *
     * @return StudentExamPaper
     */
    public function setExamQstnIds($examQstnIds)
    {
        $this->examQstnIds = $examQstnIds;

        return $this;
    }

    /**
     * Get examQstnIds
     *
     * @return string
     */
    public function getExamQstnIds()
    {
        return $this->examQstnIds;
    }

    /**
     * Set examQstnStatus
     *
     * @param string $examQstnStatus
     *
     * @return StudentExamPaper
     */
    public function setExamQstnStatus($examQstnStatus)
    {
        $this->examQstnStatus = $examQstnStatus;

        return $this;
    }

    /**
     * Get examQstnStatus
     *
     * @return string
     */
    public function getExamQstnStatus()
    {
        return $this->examQstnStatus;
    }

    /**
     * Set $examQstnOptions
     *
     * @param string $examQstnOptions
     *
     * @return StudentExamPaper
     */
    public function setExamQstnOptions($examQstnOptions)
    {
        $this->examQstnOptions = $examQstnOptions;

        return $this;
    }

    /**
     * Get examQstnStatus
     *
     * @return string
     */
    public function getExamQstnOptions()
    {
        return $this->examQstnOptions;
    }

    /**
     * Set $totalscore
     *
     * @param float $totalscore
     *
     * @return StudentExamPaper
     */
    public function setTotalscore($totalscore)
    {
        $this->totalscore = $totalscore;

        return $this;
    }

    /**
     * Get totalscore
     *
     * @return float
     */
    public function getTotalscore()
    {
        return $this->totalscore;
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
     * @return StudentExamPaper
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
     * @return StudentExamPaper
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

