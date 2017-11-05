<?php

namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

trait SluggableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * Sets the slug.
     *
     * @param string $slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Gets the slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}