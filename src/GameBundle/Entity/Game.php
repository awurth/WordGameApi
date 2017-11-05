<?php

namespace App\GameBundle\Entity;

use App\CoreBundle\Entity\TimestampableTrait;
use App\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="game_game")
 * @ORM\Entity(repositoryClass="App\GameBundle\Repository\GameRepository")
 */
class Game
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     *
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $creator;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\GameBundle\Entity\Subject", inversedBy="games")
     * @ORM\JoinTable(name="game_game_subject")
     */
    protected $subjects;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\UserBundle\Entity\User", inversedBy="games")
     * @ORM\JoinTable(name="game_game_user")
     */
    protected $users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subjects = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the creator.
     *
     * @param User $creator
     *
     * @return self
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Gets the creator.
     *
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Adds a subject.
     *
     * @param Subject $subject
     *
     * @return self
     */
    public function addSubject(Subject $subject)
    {
        $this->subjects->add($subject);

        return $this;
    }

    /**
     * Removes a subject.
     *
     * @param Subject $subject
     *
     * @return self
     */
    public function removeSubject(Subject $subject)
    {
        $this->subjects->removeElement($subject);

        return $this;
    }

    /**
     * Gets the subjects.
     *
     * @return ArrayCollection
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Adds a user.
     *
     * @param User $user
     *
     * @return self
     */
    public function addUser(User $user)
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * Removes a user.
     *
     * @param User $user
     *
     * @return self
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * Gets the users.
     *
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }
}

