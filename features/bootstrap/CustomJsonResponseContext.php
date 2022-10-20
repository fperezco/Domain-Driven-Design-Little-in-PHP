<?php


use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\Json\Json;


class CustomJsonResponseContext extends BasicJsonContext implements Context
{

    /**
     * @Then /^the response body is a JSON array of (\d+) elements$/
     */
    public function theResponseBodyIsAJSONArrayOfLength($arg1)
    {
        $this->assertEquals($arg1, count($this->getJson()->getContent()));
    }

    /**
     * @Given /^the JSON is an array of structure:$/
     * @throws Exception
     */
    public function theJSONIsAnArrayOfStructure(TableNode $nodes)
    {
        $array = $this->getJson()->getContent();
        foreach ($array as $element) {
            foreach ($nodes->getRowsHash() as $node => $text) {
                if (!array_key_exists($node, $element)) {
                    throw new Exception("Wrong array structure", null);
                }
            }
        }
    }

    /**
     * @Given /^the JSON is an array that contains a first element like:$/
     * @throws Exception
     */
    public function theJSONIsAnArrayThatContainsOneElementLike(TableNode $nodes)
    {
        $array = $this->getJson()->getContent();
        $element = $array[0];
        foreach ($nodes->getRowsHash() as $node => $text) {
            if (get_object_vars($element)[$node] != $text) {
                throw new Exception("Wrong json one element content", null);
            }
        }
    }


    /**
     * @Given /^the JSON should be equal to with patterns:$/
     */
    public function theJSONShouldBeEqualToWithPatterns(PyStringNode $content)
    {
        $actual = $this->getJson();

        try {
            $expected = new Json($content);
        } catch (\Exception $e) {
            throw new \Exception('The expected JSON is not a valid');
        }

        $actualContent = clone $actual->getContent();
        $expectedContent = clone $expected->getContent();

        $this->setAsIgnoreLinesFromActualAndExpectedToIgnore($actualContent, $expectedContent);

        $this->assertEquals(
            $expectedContent,
            $actualContent,
            "The json is equal to:\n" . $actual->encode()
        );
    }


    /**
     * @Given /^the JSON should be equal to with patterns and without taking care order:$/
     */
    public function theJSONShouldBeEqualToWithPatternsAndWihoutTakingCareOrder(PyStringNode $content)
    {
        $actual = $this->getJson();

        try {
            $expected = new Json($content);
        } catch (\Exception $e) {
            throw new \Exception('The expected JSON is not a valid');
        }

        //delete ignore value due to pattern ##ignore##
        foreach ($expected->getContent() as $rootKey => $content) {
            foreach ($content as $key => $value) {
                if (is_string($value) && $value == '##ignore##') {
                    unset($expected->getContent()[$rootKey]->{$key});
                    unset($actual->getContent()[$rootKey]->{$key});
                }
            }
        }

        if (count($actual->getContent()) != count($expected->getContent())) {
            throw new Exception("actual and expected have different number of elements");
        }

        $equalsElements = 0;
        foreach ($expected->getContent() as $expectedContent) {
            foreach ($actual->getContent() as $actualContent) {
                if ($expectedContent == $actualContent) {
                    $equalsElements++;
                }
            }
        }

        $this->assertEquals($equalsElements, count($actual->getContent()));
    }

    protected function getJson()
    {
        return new Json($this->httpCallResultPool->getResult()->getValue());
    }

    private function setAsIgnoreLinesFromActualAndExpectedToIgnore($actualContents, $expectedContents)
    {
        //delete ignore value due to pattern ##ignore##
        if (is_array($expectedContents)) {
            foreach ($expectedContents as $key => $expectedContentValue) {
                if(!array_key_exists($key,$actualContents)){
                    throw new \PHPUnit\Util\Exception("invalid index in actual content, expected has different object level that returned content");
                }
                $this->setAsIgnoreLinesFromActualAndExpectedToIgnore($actualContents[$key], $expectedContents[$key]);
            }
        }
        else{
            foreach ($expectedContents as $rootKey => $value) {
                if (is_string($value) && $value == '##ignore##') {
                    $actualContents->{$rootKey} = $value;
                } elseif (is_array($value) || is_object($value)) {
                    $this->setAsIgnoreLinesFromActualAndExpectedToIgnore($actualContents->{$rootKey}, $expectedContents->{$rootKey});
                }
            }
        }


    }
}