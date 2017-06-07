<?php

namespace Doppy\GoogleTagManagerBundle\Twig;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class GoogleTagManagerExtension extends \Twig_Extension
{
    /**
     * @var SerializerInterface|NormalizerInterface
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $tagId;

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var bool
     */
    protected $test = false;

    /**
     * GoogleTagManagerExtension constructor.
     *
     * @param SerializerInterface $serializer
     * @param string              $tagId
     * @param bool                $enabled
     * @param bool                $test
     */
    public function __construct(SerializerInterface $serializer, $tagId, $enabled, $test)
    {
        $this->serializer = $serializer;
        $this->tagId      = $tagId;
        $this->enabled    = $enabled;
        $this->test       = $test;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'doppy_gtm_push',
                array($this, 'renderDataLayerPush'),
                array(
                    'needs_environment' => true,
                    'is_safe'           => ['html']
                )
            ),
            new \Twig_SimpleFunction(
                'doppy_gtm_script',
                array($this, 'renderScript'),
                array(
                    'needs_environment' => true,
                    'is_safe'           => ['html']
                )
            ),
            new \Twig_SimpleFunction(
                'doppy_gtm_noscript',
                array($this, 'renderNoScript'),
                array(
                    'needs_environment' => true,
                    'is_safe'           => ['html']
                )
            ),
            new \Twig_SimpleFunction(
                'doppy_gtm_serialize',
                array($this, 'serialize')
            ),
            new \Twig_SimpleFunction(
                'doppy_gtm_normalize',
                array($this, 'normalize')
            ),

        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param array             $data
     *
     * @return string
     */
    public function renderDataLayerPush(\Twig_Environment $twig, $data = array())
    {
        // don't do anything when disabled
        if (!$this->enabled) {
            return '';
        }

        // no action when no data; not fatal as it might be easier to call this with empty data
        if (empty($data)) {
            return '';
        }

        // serialize data
        $serialized = $this->serializer->serialize($data, 'json');

        // now render
        $rendered = $twig->render(
            '@DoppyGoogleTagManager/TwigExtension/dataLayerPush.html.twig',
            array('serialized' => $serialized)
        );

        return trim($rendered);
    }

    /**
     * @param \Twig_Environment $twig
     * @param array             $data
     *
     * @return string
     */
    public function renderScript(\Twig_Environment $twig)
    {
        // don't do anything when disabled
        if (!$this->enabled) {
            return '';
        }

        // now render
        $rendered = $twig->render(
            '@DoppyGoogleTagManager/TwigExtension/script.html.twig',
            array(
                'tag_id' => $this->tagId,
                'test'   => $this->test
            )
        );

        return trim($rendered);
    }

    /**
     * @param \Twig_Environment $twig
     *
     * @return string
     */
    public function renderNoScript(\Twig_Environment $twig)
    {
        // don't do anything when disabled
        if (!$this->enabled) {
            return '';
        }

        // now render
        $rendered = $twig->render(
            '@DoppyGoogleTagManager/TwigExtension/noScript.html.twig',
            array(
                'tag_id' => $this->tagId,
                'test'   => $this->test
            )
        );

        return trim($rendered);
    }

    /**
     * @param mixed $data
     *
     * @return string
     */
    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    public function normalize($data)
    {
        return $this->serializer->normalize($data);
    }

    /**
     * @return array
     */
    public function getProfilerData()
    {
        return $this->profilerData;
    }

    public function getName()
    {
        return 'doppy_google_tag_manager';
    }
}
