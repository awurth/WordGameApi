<?php

namespace App\GameBundle\Entity;

use App\CoreBundle\Entity\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\GameBundle\Repository\RoundRepository")
 * @ORM\Table(name="game_round")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "get_round",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 * @Hateoas\Relation(
 *     "game",
 *     href = @Hateoas\Route(
 *         "get_game",
 *         parameters = { "id" = "expr(object.getGame().getId())" }
 *     )
 * )
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
     * @Assert\NotBlank
     * @Assert\Length(max="1")
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
     * @Assert\NotNull
     *
     * @ORM\ManyToOne(targetEntity="App\GameBundle\Entity\Game", cascade={"persist"}, inversedBy="rounds")
     * @ORM\JoinColumn(nullable=false)
     *
     * @JMS\Exclude
     */
    protected $game;

    /**
     * @Assert\Callback(groups={"post_round"})
     */
    public function validate(ExecutionContextInterface $context)
    {
        foreach ($this->game->getRounds() as $round) {
            if (!$round->isFinished()) {
                $context->addViolation('All rounds must be finished to create a new one.');
                break;
            }
        }
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

