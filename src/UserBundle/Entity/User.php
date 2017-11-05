<?php

namespace App\UserBundle\Entity;

use App\CoreBundle\Entity\TimestampableTrait;
use App\GameBundle\Entity\Game;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as JMS;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="`user`")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "get_user",
 *         parameters = { "id" = "expr(object.getId())" }
 *     )
 * )
 */
class User extends BaseUser
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\GameBundle\Entity\Game", mappedBy="users")
     *
     * @JMS\Exclude
     */
    protected $games;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->games = new ArrayCollection();
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
