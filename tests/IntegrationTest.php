<?php

class Project_Tests_IntegrationTest extends \Twig_Test_IntegrationTestCase {

    public function getExtensions() {
        return array(
            new \ShadeSoft\ImageBundle\Twig\FilterExtension(new \ShadeSoft\GDImage\Service\ImageSizer)
        );
    }

    public function getFixturesDir() {
        return __DIR__ . '/Fixtures/';
    }
}