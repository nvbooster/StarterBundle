<?php

namespace nvbooster\StarterBundle\Block;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

/**
 * UnitBlockService
 *
 * @author nvb <nvb@aproxima.ru>
 */
class UnitBlockService extends BaseBlockService
{
    protected $template = '@NvboosterStarter/unitblock.html.twig';

    public function __construct($name, EngineInterface $templating, $template = null)
    {
        parent::__construct($name, $templating);

        if ($template) {
            $this->template = $template;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        //Override костыль позволяющий передавать любые настройки в блок
        /* @var $block UnitBlock */
        list(,$block) = func_get_args();
        $resolver->setDefaults(
            array_merge(
                $block ?
                    array_fill_keys(array_keys($block->getSettings()), null) :
                    array(),
                array(
                    'template' => $this->template
                )
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $form, BlockInterface $block)
    {
        throw new \RuntimeException('Not used at the moment, editing using a frontend or backend UI could be changed here');
    }

    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        throw new \RuntimeException('Not used at the moment, validation for editing using a frontend or backend UI could be changed here');
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        if (!$response) {
            $response = new Response();
        }

        if ($blockContext->getBlock()->getEnabled()) {
            return $this->renderResponse($blockContext->getTemplate(), array(
                'block' => $blockContext->getBlock(),
            ), $response);
        }

        return $response;
    }
}