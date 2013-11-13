<?php

namespace Acme\JobeetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Acme\JobeetBundle\Utils\Jobeet as Jobeet;
/**
 * Category
 */
class Category
{
   
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $Job;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $Affiliate;

    private $active_jobs;
    private $more_jobs;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Job = new \Doctrine\Common\Collections\ArrayCollection();
        $this->Affiliate = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * @return Category
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
     * Add Job
     *
     * @param \Acme\JobeetBundle\Entity\Job $job
     * @return Category
     */
    public function addJob(\Acme\JobeetBundle\Entity\Job $job)
    {
        $this->Job[] = $job;
    
        return $this;
    }

    /**
     * Remove Job
     *
     * @param \Acme\JobeetBundle\Entity\Job $job
     */
    public function removeJob(\Acme\JobeetBundle\Entity\Job $job)
    {
        $this->Job->removeElement($job);
    }

    /**
     * Get Job
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getJob()
    {
        return $this->Job;
    }

    /**
     * Add Affiliate
     *
     * @param \Acme\JobeetBundle\Entity\Affiliate $affiliate
     * @return Category
     */
    public function addAffiliate(\Acme\JobeetBundle\Entity\Affiliate $affiliate)
    {
        $this->Affiliate[] = $affiliate;
    
        return $this;
    }

    /**
     * Remove Affiliate
     *
     * @param \Acme\JobeetBundle\Entity\Affiliate $affiliate
     */
    public function removeAffiliate(\Acme\JobeetBundle\Entity\Affiliate $affiliate)
    {
        $this->Affiliate->removeElement($affiliate);
    }

    /**
     * Get Affiliate
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffiliate()
    {
        return $this->Affiliate;
    }

    public function __toString() {
        return $this->getName();
    }

    
    public function setActiveJobs($jobs)
    {
        $this->active_jobs = $jobs;
    }

    public function getActiveJobs() {
        return $this->active_jobs;
    }

    public function setMoreJobs($jobs)
    {
        $this->more_jobs = $jobs >= 0 ? $jobs : 0;
    }

    public function getMoreJobs()
    {
        return $this->more_jobs;
    }
    /**
     * @var string
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * @ORM\PrePersist
     */
    public function setSlugValue()
    {
        $this->slug = Jobeet::slugify($this->getName());
    }
}