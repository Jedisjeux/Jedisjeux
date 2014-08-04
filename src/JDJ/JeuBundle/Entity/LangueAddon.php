<?php

namespace JDJ\JeuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LangueAddon
 */
class LangueAddon
{
    /**
     * @var integer
     */
    private $langue_id;

    /**
     * @var integer
     */
    private $addon_id;

    /**
     * @var \JDJ\WebBundle\Entity\Langue
     */
    private $langue;

    /**
     * @var \JDJ\JeuBundle\Entity\Addon
     */
    private $addon;


    /**
     * Set langue_id
     *
     * @param integer $langueId
     * @return LangueAddon
     */
    public function setLangueId($langueId)
    {
        $this->langue_id = $langueId;

        return $this;
    }

    /**
     * Get langue_id
     *
     * @return integer 
     */
    public function getLangueId()
    {
        return $this->langue_id;
    }

    /**
     * Set addon_id
     *
     * @param integer $addonId
     * @return LangueAddon
     */
    public function setAddonId($addonId)
    {
        $this->addon_id = $addonId;

        return $this;
    }

    /**
     * Get addon_id
     *
     * @return integer 
     */
    public function getAddonId()
    {
        return $this->addon_id;
    }

    /**
     * Set langue
     *
     * @param \JDJ\WebBundle\Entity\Langue $langue
     * @return LangueAddon
     */
    public function setLangue(\JDJ\WebBundle\Entity\Langue $langue = null)
    {
        $this->langue = $langue;

        return $this;
    }

    /**
     * Get langue
     *
     * @return \JDJ\WebBundle\Entity\Langue 
     */
    public function getLangue()
    {
        return $this->langue;
    }

    /**
     * Set addon
     *
     * @param \JDJ\JeuBundle\Entity\Addon $addon
     * @return LangueAddon
     */
    public function setAddon(\JDJ\JeuBundle\Entity\Addon $addon = null)
    {
        $this->addon = $addon;

        return $this;
    }

    /**
     * Get addon
     *
     * @return \JDJ\JeuBundle\Entity\Addon 
     */
    public function getAddon()
    {
        return $this->addon;
    }
}
