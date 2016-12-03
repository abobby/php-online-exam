<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamPaper
 *
 * @ORM\Table(name="exam_paper")
 * @ORM\Entity
 */
class ExamPaper
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="details", type="text", length=65535, nullable=false)
     */
    private $details;

    /**
     * @var integer
     *
     * @ORM\Column(name="paper_time", type="integer", nullable=false)
     */
    private $paperTime;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="total_marks", type="integer", nullable=false)
     */
    private $totalMarks;

    /**
     * @var string
     *
     * @ORM\Column(name="paper_sets", type="string", length=255, nullable=false)
     */
    private $paperSets;

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
     * @ORM\OneToOne(targetEntity="\Student\Entity\StudentExamPaper", mappedBy="examPaperId", cascade={"persist","remove"})
     * */
    private $studentexampaper;
    
    /**
     * @ORM\OneToOne(targetEntity="\Student\Entity\StudentExamStarted", mappedBy="examPaperId", cascade={"persist","remove"})
     * */
    private $studentexamstartedexp;
    

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
     * Set name
     *
     * @param string $name
     *
     * @return ExamPaper
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set details
     *
     * @param string $details
     *
     * @return ExamPaper
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set details
     *
     * @param integer $paperTime
     *
     * @return ExamPaper
     */
    public function setPaperTime($paperTime)
    {
        $this->paperTime = $paperTime;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getPaperTime()
    {
        return $this->paperTime;
    }
    
    /**
     * Set totalMarks
     *
     * @param integer $totalMarks
     *
     * @return ExamPaper
     */
    public function setTotalMarks($totalMarks)
    {
        $this->totalMarks = $totalMarks;

        return $this;
    }

    /**
     * Get totalMarks
     *
     * @return integer
     */
    public function getTotalMarks()
    {
        return $this->totalMarks;
    }

    /**
     * Set paperSets
     *
     * @param string $paperSets
     *
     * @return ExamPaper
     */
    public function setPaperSets($paperSets)
    {
        $this->paperSets = $paperSets;

        return $this;
    }

    /**
     * Get paperSets
     *
     * @return string
     */
    public function getPaperSets()
    {
        return $this->paperSets;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ExamPaper
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
     * @return ExamPaper
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
     * @return ExamPaper
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
        $this->createdAt = new \DateTime(date('Y-m-d H:i:s'));
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
    }

    /** @ORM\PreUpdate */
    public function preUpdate() {
        $this->updatedAt = new \DateTime(date('Y-m-d H:i:s'));
    }
}
