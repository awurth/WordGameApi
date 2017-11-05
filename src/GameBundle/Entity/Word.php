<?php

namespace App\GameBundle\Entity;

use App\CoreBundle\Entity\TimestampableTrait;
use App\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="game_word")
 * @ORM\Entity(repositoryClass="App\GameBundle\Repository\WordRepository")
 */
class Word
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
     * @var string
     *
     * @Assert\Length(max=50)
     *
     * @ORM\Column(name="value", type="string", length=50)
     */
    protected $value;

    /**
     * @var Round
     *
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="App\GameBundle\Entity\Round", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $round;

    /**
     * @var Subject
     *
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="App\GameBundle\Entity\Subject", cascade={"persist"})
     */
    protected $subject;

    /**
     * @var User
     *
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="App\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

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
     * Sets the round.
     *
     * @param Round $round
     *
     * @return self
     */
    public function setRound(Round $round)
    {
        $this->round = $round;

        return $this;
    }

    /**
     * Gets the round.
     *
     * @return Round
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * Sets the subject.
     *
     * @param Subject $subject
     *
     * @return self
     */
    public function setSubject(Subject $subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Gets the subject.
     *
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets the user.
     *
     * @param User $user
     *
     * @return self
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}

