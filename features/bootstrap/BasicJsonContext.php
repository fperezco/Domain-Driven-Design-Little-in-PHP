<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\HttpCall\HttpCallResultPool;
use Behatch\Json\Json;
use Behatch\Json\JsonInspector;

/**
 * Defines application features from the specific context.
 */
abstract class BasicJsonContext extends \Behatch\Context\BaseContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    protected $inspector;

    protected $httpCallResultPool;

    public function __construct(HttpCallResultPool $httpCallResultPool, $evaluationMode = 'javascript')
    {
        $this->inspector = new JsonInspector($evaluationMode);
        $this->httpCallResultPool = $httpCallResultPool;
    }


    public function theJsonNodeShouldContain($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        $this->assertContains($text, (string)$actual);
    }

    protected function getJson()
    {
        return new Json($this->httpCallResultPool->getResult()->getValue());
    }

    /**
     * @Then /^I am going to:(.*)$/
     */
    public function iAmGoingTo(string $text)
    {
        return;
    }
}