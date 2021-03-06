<?php

namespace nvbooster\StarterBundle\Document;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishTimePeriodInterface;
use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Bundle\MenuBundle\Model\MenuOptionsInterface;
use Symfony\Cmf\Bundle\ContentBundle\Doctrine\Phpcr\StaticContentBase;
use Symfony\Cmf\Bundle\SeoBundle\SeoAwareInterface;
use Symfony\Cmf\Bundle\SeoBundle\Doctrine\Phpcr\SeoMetadata;
use Symfony\Cmf\Component\Routing\RouteReferrersInterface;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Menu\NodeInterface;
use Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalRouteReadInterface;
use nvbooster\StarterBundle\Sitemap\SitemapPropertiesInterface;

/**
 * OnePageItem
 *
 * @author nvb <nvb@aproxima.ru>
 */
class OnePageItem extends StaticContentBase implements
    SeoAwareInterface,
    PublishTimePeriodInterface,
    PublishableInterface,
    NodeInterface,
    MenuOptionsInterface,
    RouteReferrersInterface,
    OriginalRouteReadInterface,
    SitemapPropertiesInterface
{
    /**
     * @var SeoMetadata
     *
     */
    protected $seoMetadata;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var boolean whether this content is publishable
     */
    protected $publishable = true;

    /**
     * @var \DateTime|null publication start time
     */
    protected $publishStartDate;

    /**
     * @var \DateTime|null publication end time
     */
    protected $publishEndDate;

    /**
     * Hashmap for application data associated to this document. Both keys and
     * values must be strings.
     */
    protected $extras = array();

    /**
     * HTML attributes to add to the individual menu element.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * HTML attributes to add to items link.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
    */
    protected $linkAttributes = array();

    /**
     * HTML attributes to add to the items label.
     *
     * e.g. array('class' => 'foobar', 'style' => 'bar: foo')
     *
     * @var array
    */
    protected $labelAttributes = array();

    /**
     * Set to false to not render a menu item for this.
     *
     * @var boolean
    */
    protected $display = true;

    /**
     * @var RouteObjectInterface[]
     */
    protected $routes;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @var double
     */
    protected $pageWeight;

    /**
     * @var string
     */
    protected $updatePeriod;

    /**
     * @var boolean
     */
    protected $visibleInSitemap;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->seoMetadata = new SeoMetadata();
        $this->routes = new ArrayCollection();
    }

    /**
     * @param string $name the new name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * Return the attributes associated with this menu node
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set the attributes associated with this menu node
     *
     * @param array $attributes
     *
     * @return self
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Return the given attribute, optionally specifying a default value
     *
     * @param string $name    The name of the attribute to return
     * @param string $default The value to return if the attribute doesn't exist
     *
     * @return string
     */
    public function getAttribute($name, $default = null)
    {
        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $default;
    }

    /**
     * Set the named attribute
     *
     * @param string $name  attribute name
     * @param string $value attribute value
     *
     * @return self
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    /**
     * Return the children attributes
     *
     * @return array
     */
    public function getChildrenAttributes()
    {
        return array();
    }

    /**
     * @param array $childrenAttributes
     *
     * @return self
     */
    public function setChildrenAttributes(array $childrenAttributes)
    {
        return $this;
    }

    /**
     * Get the link HTML attributes.
     *
     * @return array
     */
    public function getLinkAttributes()
    {
        return $this->linkAttributes;
    }

    /**
     * Set the link HTML attributes as associative array.
     *
     * @param array $linkAttributes
     *
     * @return self
     */
    public function setLinkAttributes($linkAttributes)
    {
        $this->linkAttributes = $linkAttributes;

        return $this;
    }

    /**
     * Get the label HTML attributes.
     *
     * @return array
     */
    public function getLabelAttributes()
    {
        return $this->labelAttributes;
    }

    /**
     * Set the label HTML attributes as associative array.
     *
     * @param array $labelAttributes
     *
     * @return self
     */
    public function setLabelAttributes($labelAttributes)
    {
        $this->labelAttributes = $labelAttributes;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Whether a menu item for this page should be displayed if possible.
     *
     * @return boolean
     *
     * @see isDisplayableMenu
     */
    public function getDisplay()
    {
        return $this->display;
    }

    /**
     * Set whether to display the menu item for this.
     *
     * @param boolean $display
     *
     * @return self
     */
    public function setDisplay($display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * Whether to display the child menu items of this page.
     *
     * @return boolean
     */
    public function getDisplayChildren()
    {
        return false;
    }

    /**
     * @param boolean $dummy
     *
     * @return self
     */
    public function setDisplayChildren($dummy)
    {
        return $this;
    }

    /**
     * Whether this page can be displayed in the menu, meaning getDisplay is
     * true and there is a non-empty label.
     *
     * @return boolean
     */
    public function isDisplayableMenu()
    {
        return $this->getDisplay() && $this->getLabel();
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return array(
            'label' => $this->getLabel(),
            'attributes' => $this->getAttributes(),
            'childrenAttributes' => $this->getChildrenAttributes(),
            'display' => $this->isDisplayableMenu(),
            'displayChildren' => $this->getDisplayChildren(),
            'linkAttributes' => $this->getLinkAttributes(),
            'labelAttributes' => $this->getLabelAttributes(),
            'uri' => '#' . $this->getName()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setPublishable($publishable)
    {
        return $this->publishable = (bool) $publishable;
    }

    /**
     * {@inheritDoc}
     */
    public function isPublishable()
    {
        return $this->publishable;
    }

    /**
     * {@inheritDoc}
     */
    public function getPublishStartDate()
    {
        return $this->publishStartDate;
    }

    /**
     * {@inheritDoc}
     */
    public function setPublishStartDate(\DateTime $publishStartDate = null)
    {
        $this->publishStartDate = $publishStartDate;
    }

    /**
     * {@inheritDoc}
     */
    public function getPublishEndDate()
    {
        return $this->publishEndDate;
    }

    /**
     * {@inheritDoc}
     */
    public function setPublishEndDate(\DateTime $publishEndDate = null)
    {
        $this->publishEndDate = $publishEndDate;
    }

    /**
     * Get the application information associated with this document
     *
     * @return array
     */
    public function getExtras()
    {
        return $this->extras;
    }

    /**
     * Get a single application information value
     *
     * @param string      $name
     * @param string|null $default
     *
     * @return string|null the value at $name if set, null otherwise
     */
    public function getExtra($name, $default = null)
    {
        return isset($this->extras[$name]) ? $this->extras[$name] : $default;
    }

    /**
     * Set the application information
     *
     * @param array $extras
     *
     * @return StaticContent - this instance
     */
    public function setExtras(array $extras)
    {
        $this->extras = $extras;

        return $this;
    }

    /**
     * Set a single application information value.
     *
     * @param string $name  name
     * @param string $value the new value, null removes the entry
     *
     * @return StaticContent
     */
    public function setExtra($name, $value)
    {
        if (is_null($value)) {
            unset($this->extras[$name]);
        } else {
            $this->extras[$name] = $value;
        }

        return $this;
    }

    /**
     * Get children
     *
     * @return ArrayCollection|ChildrenCollection
     */
    public function getChildren()
    {
        return array();
    }

    /**
     * @return integer
     */
    public function hasChildren()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getSeoMetadata()
    {
        return $this->seoMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function setSeoMetadata($seoMetadata)
    {
        $this->seoMetadata = $seoMetadata;
    }

    /**
     * {@inheritDoc}
     */
    public function addRoute($route)
    {
        $this->routes->add($route);
    }

    /**
     * {@inheritDoc}
     */
    public function removeRoute($route)
    {
        $this->routes->removeElement($route);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return OnePageItem
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Cmf\Bundle\SeoBundle\Extractor\OriginalRouteReadInterface::getSeoOriginalRoute()
     */
    public function getSeoOriginalRoute()
    {
        list($route) = $this->routes;

        return $route ? $route->getId() : false;
    }


    /**
     * {@inheritdoc}
     * @see \nvbooster\StarterBundle\Sitemap\SitemapPropertiesReadInterface::getPageWeight()
     */
    public function getPageWeight()
    {
        return $this->pageWeight;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\StarterBundle\Sitemap\SitemapPropertiesInterface::setPageWeight()
     */
    public function setPageWeight($weight)
    {
        $this->pageWeight = $weight;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\StarterBundle\Sitemap\SitemapPropertiesReadInterface::getUpdatePeriod()
     */
    public function getUpdatePeriod()
    {
        return $this->updatePeriod;
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\StarterBundle\Sitemap\SitemapPropertiesInterface::setUpdatePeriod()
     */
    public function setUpdatePeriod($period)
    {
        $this->updatePeriod = $period;
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Cmf\Bundle\SeoBundle\SitemapAwareInterface::isVisibleInSitemap()
     */
    public function getVisibleInSitemap()
    {
        return $this->visibleInSitemap;
    }

    /**
     * {@inheritdoc}
     * @see \Symfony\Cmf\Bundle\SeoBundle\SitemapAwareInterface::isVisibleInSitemap()
     */
    public function isVisibleInSitemap($sitemap)
    {
        return (bool) $this->getVisibleInSitemap();
    }

    /**
     * {@inheritdoc}
     * @see \nvbooster\StarterBundle\Sitemap\SitemapPropertiesInterface::setVisibleInSitemap()
     */
    public function setVisibleInSitemap($visible)
    {
        $this->visibleInSitemap = $visible;
    }
}