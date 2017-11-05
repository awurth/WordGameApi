<?php

namespace App\GameBundle\Entity;

use App\CoreBundle\Entity\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="game_round")
 * @ORM\Entity(repositoryClass="App\GameBundle\Repository\RoundRepository")
 */
class Round
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
     * @ORM\Column(name="letter", type="string", length=255)
     */
    protected $letter;

    /**
     * @var bool
     *
     * @ORM\Column(name="finished", type="boolean")
     */
    protected $finished = false;

    /**
     * @var Game
     *
     * @ORM\ManyToOne(targetEntity="App\GameBundle\Entity\Game", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $game;

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
     * Sets the letter.
     *
     * @param string $letter
     *
     * @return self
     */
    public function setLetter($letter)
    {
        $this->letter = $letter;

        return $this;
    }

    /**
     * Gets the letter.
     *
     * @return string
     */
    public function getLetter()
    {
        return $this->letter;
    }

    /**
     * Sets whether the round is finished.
     *
     * @param bool $finished
     *
     * @return self
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Returns whether the round is finished.
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->finished;
    }

    /**
     * Sets the game.
     *
     * @param Game $game
     *
     * @return self
     */
    public function setGame(Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Gets the game.
     *
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }
}

