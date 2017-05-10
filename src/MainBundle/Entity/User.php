<?php
namespace MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->tasks = new ArrayCollection();
        $this->categories = new ArrayCollection();

    }

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="user")
     *
     */

    private $tasks;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="users")
     * @ORM\JoinTable(name="users_categories")
     */

    private $categories;

    /**
     * Add tasks
     *
     * @param \MainBundle\Entity\task $tasks
     * @return User
     */
    public function addTask(\MainBundle\Entity\task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \MainBundle\Entity\task $tasks
     */
    public function removeTask(\MainBundle\Entity\task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add categories
     *
     * @param \MainBundle\Entity\Category $categories
     * @return User
     */
    public function addCategory(\MainBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;

        return $this;
    }

    /**
     * Remove categories
     *
     * @param \MainBundle\Entity\Category $categories
     */
    public function removeCategory(\MainBundle\Entity\Category $categories)
    {
        $this->categories->removeElement($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }
}
