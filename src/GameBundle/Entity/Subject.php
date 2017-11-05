<?php

namespace App\GameBundle\Entity;

use App\CoreBundle\Entity\SluggableTrait;
use App\CoreBundle\Entity\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\GameBundle\Repository\SubjectRepository")
 * @ORM\Table(name="game_subject")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "get_subject",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 */
class Subject
{
    use SluggableTrait;
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @Assert\Length(max="50")
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\GameBundle\Entity\Game", mappedBy="subjects")
     *
     * @JMS\Exclude
     */
    protected $games;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->games = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return self
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
     * Adds a game.
     *
     * @param Game $game
     *
     * @return self
     */
    public function addGame(Game $game)
    {
        $this->games->add($game);

        return $this;
    }

    /**
     * Removes a game.
     *
     * @param Game $game
     *
     * @return self
     */
    public function removeGame(Game $game)
    {
        $this->games->removeElement($game);

        return $this;
    }

    /**
     * Gets the games.
     *
     * @return ArrayCollection
     */
    public function getGames()
    {
        return $this->games;
    }
}

