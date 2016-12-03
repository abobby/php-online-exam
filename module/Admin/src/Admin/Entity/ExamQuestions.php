<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamQuestions
 *
 * @ORM\Table(name="exam_questions")
 * @ORM\Entity
 */
class ExamQuestions
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
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\ExamClasses")
     * @ORM\JoinColumn(name="exclass", referencedColumnName="id")
     **/
    private $exclass;

    /**
     * @ORM\ManyToOne(targetEntity="\Admin\Entity\ExamSubjects")
     * @ORM\JoinColumn(name="exsubject", referencedColumnName="eid")
     **/ 
    private $exsubject;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="exmode", type="integer", nullable=false)
     */
    private $exmode;


    /**
     * @var string
     *
     * @ORM\Column(name="exquest", type="text", length=65535, nullable=false)
     */
    private $exquest;

    /**
     * @var string
     *
     * @ORM\Column(name="exchoice1", type="string", length=255, nullable=true)
     */
    private $exchoice1;

    /**
     * @var string
     *
     * @ORM\Column(name="exchoice2", type="string", length=255, nullable=true)
     */
    private $exchoice2;

    /**
     * @var string
     *
     * @ORM\Column(name="exchoice3", type="string", length=255, nullable=true)
     */
    private $exchoice3;

    /**
     * @var string
     *
     * @ORM\Column(name="exchoice4", type="string", length=255, nullable=true)
     */
    private $exchoice4;

    /**
     * @var integer
     *
     * @ORM\Column(name="excrchoice", type="integer", nullable=true)
     */
    private $excrchoice;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     **/ 
    private $createdBy;

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
     * Set exclass
     *
     * @param integer $exclass
     *
     * @return ExamQuestions
     */
    public function setExclass($exclass)
    {
        $this->exclass = $exclass;

        return $this;
    }

    /**
     * Get exclass
     *
     * @return integer
     */
    public function getExclass()
    {
        return $this->exclass;
    }

    /**
     * Set exsubject
     *
     * @param integer $exsubject
     *
     * @return ExamQuestions
     */
    public function setExsubject($exsubject)
    {
        $this->exsubject = $exsubject;

        return $this;
    }

    /**
     * Get exsubject
     *
     * @return integer
     */
    public function getExsubject()
    {
        return $this->exsubject;
    }
    
    /**
     * Set exmode
     *
     * @param integer $exmode
     *
     * @return ExamQuestions
     */
    public function setExmode($exmode)
    {
        $this->exmode = $exmode;

        return $this;
    }

    /**
     * Get exmode
     *
     * @return integer
     */
    public function getExmode()
    {
        return $this->exmode;
    }

    /**
     * Set exquest
     *
     * @param string $exquest
     *
     * @return ExamQuestions
     */
    public function setExquest($exquest)
    {
        $this->exquest = $exquest;

        return $this;
    }

    /**
     * Get exquest
     *
     * @return string
     */
    public function getExquest()
    {
        return $this->exquest;
    }

    /**
     * Set exchoice1
     *
     * @param string $exchoice1
     *
     * @return ExamQuestions
     */
    public function setExchoice1($exchoice1)
    {
        $this->exchoice1 = $exchoice1;

        return $this;
    }

    /**
     * Get exchoice1
     *
     * @return string
     */
    public function getExchoice1()
    {
        return $this->exchoice1;
    }

    /**
     * Set exchoice2
     *
     * @param string $exchoice2
     *
     * @return ExamQuestions
     */
    public function setExchoice2($exchoice2)
    {
        $this->exchoice2 = $exchoice2;

        return $this;
    }

    /**
     * Get exchoice2
     *
     * @return string
     */
    public function getExchoice2()
    {
        return $this->exchoice2;
    }

    /**
     * Set exchoice3
     *
     * @param string $exchoice3
     *
     * @return ExamQuestions
     */
    public function setExchoice3($exchoice3)
    {
        $this->exchoice3 = $exchoice3;

        return $this;
    }

    /**
     * Get exchoice3
     *
     * @return string
     */
    public function getExchoice3()
    {
        return $this->exchoice3;
    }

    /**
     * Set exchoice4
     *
     * @param string $exchoice4
     *
     * @return ExamQuestions
     */
    public function setExchoice4($exchoice4)
    {
        $this->exchoice4 = $exchoice4;

        return $this;
    }

    /**
     * Get exchoice4
     *
     * @return string
     */
    public function getExchoice4()
    {
        return $this->exchoice4;
    }

    /**
     * Set excrchoice
     *
     * @param integer $excrchoice
     *
     * @return ExamQuestions
     */
    public function setExcrchoice($excrchoice)
    {
        $this->excrchoice = $excrchoice;

        return $this;
    }

    /**
     * Get excrchoice
     *
     * @return integer
     */
    public function getExcrchoice()
    {
        return $this->excrchoice;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     *
     * @return ExamQuestions
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ExamQuestions
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
     * @return ExamQuestions
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
     * @return ExamQuestions
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
