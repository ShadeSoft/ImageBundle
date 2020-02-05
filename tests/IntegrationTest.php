<?php

class IntegrationTest extends \Twig\Test\IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new \ShadeSoft\ImageBundle\Twig\FilterExtension(new \ShadeSoft\GDImage\Service\ImageSizer()),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }
}
