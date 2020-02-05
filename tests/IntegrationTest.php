<?php

class Project_Tests_IntegrationTest extends \Twig\Test\IntegrationTestCase
{
    public function getExtensions()
    {
        return [
            new \ShadeSoft\ImageBundle\Twig\FilterExtension(),
        ];
    }

    public function getFixturesDir()
    {
        return __DIR__.'/Fixtures/';
    }
}
