<?php declare(strict_types=1);

namespace BehatTests\Context;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use BehatTests\Storage\FeatureSharedStorage;
use BehatTests\Traits\DoctrineTrait;
use BehatTests\Traits\JsonHolderTrait;
use BehatTests\Traits\JsonTrait;
use BehatTests\Traits\PropertyAccessorTrait;
use BehatTests\Utils\Contains;
use Exception;
use JsonSpec\Behat\Context\JsonHolderAware;
use PHPUnit\Framework\Assert as Assertions;
use RuntimeException;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TypeError;

/**
 * Class FeatureContext
 *
 * @package BehatTests\Context
 */
class FeatureContext implements KernelAwareContext, JsonHolderAware
{
    use DoctrineTrait, PropertyAccessorTrait, JsonTrait, JsonHolderTrait;

    public const HOST_BASE = 'base_host';

    /** @var Request */
    private $request;

    /** @var Response */
    private $response;

    /** @var string */
    private $currentHost = self::HOST_BASE;

    /**
     * @BeforeScenario
     */
    public function beforeScenario(): void
    {
        $this->currentHost = self::HOST_BASE;

        $session = $this->getContainer()->get('session');
        $session->clear();
        $session->save();

        $this->request = Request::create('/');
        $this->request->cookies->set($session->getName(), $session->getId());
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function printResponseForFailedStep(AfterStepScope $scope): void
    {
        if ($this->response && !$scope->getTestResult()->isPassed()) {
            $this->printResponse();
        }
    }

    /**
     * @return string
     */
    private function getBaseHost(): string
    {
        return $this->getContainer()->getParameter($this->currentHost);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function preparePath(string $path): string
    {
        $path = FeatureSharedStorage::replacePlaceholder($path);

        if (parse_url($path, PHP_URL_HOST)) {
            return $path;
        }

        $scheme = $this->getContainer()->getParameter('uri.scheme');
        $host = $this->getBaseHost();

        $path = sprintf('%s://%s%s', $scheme, $host, $path);

        return $path;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @Given /^I set header "([^"]*)" with value "([^"]*)"$/
     */
    public function iSetHeaderWithValue(string $name, string $value): void
    {
        $value = FeatureSharedStorage::replacePlaceholder($value);

        $this->request->headers->set($name, [$value]);
    }

    /**
     * @param string $method request method
     * @param string $url relative url
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)"$/
     */
    public function iSendRequest(string $method, string $url): void
    {
        $this->sendRequest($url, $method);
    }

    /**
     * @param string $method
     * @param string $url
     * @param PyStringNode $jsonString
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with json:/
     */
    public function iSendRequestWithJson(string $method, string $url, PyStringNode $jsonString): void
    {
        $this->request->headers->set('Content-Type', 'application/json');

        $this->sendRequest($url, $method, FeatureSharedStorage::replacePlaceholder($jsonString->getRaw()));
    }

    /**
     * @param string $method
     * @param string $url
     * @param PyStringNode $body
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with form data:/
     */
    public function iSendRequestWithFormData(string $method, string $url, PyStringNode $body): void
    {
        $body = FeatureSharedStorage::replacePlaceholder($body->getRaw());
        parse_str(implode('&', explode("\n", $body)), $fields);

        foreach ($this->request->request->keys() as $key) {
            $this->request->request->remove($key);
        }
        $this->request->request->add($fields);

        $this->sendRequest($url, $method);
    }

    /**
     * @param string $method
     * @param string $url
     * @param PyStringNode $body
     *
     * @throws Exception
     *
     * @When /^(?:I )?send a ([A-Z]+) request to "([^"]+)" with query parameters:/
     */
    public function iSendRequestWithQueryParameters(string $method, string $url, PyStringNode $body): void
    {
        $body = FeatureSharedStorage::replacePlaceholder($body->getRaw());

        parse_str(implode('&', explode("\n", $body)), $fields);

        $this->sendRequest(sprintf('%s?%s', $url, http_build_query($fields)), $method);
    }

    /**
     * @param int $code
     *
     * @Then /^(?:the )?response (?:status )?code should be (\d+)$/
     */
    public function theResponseCodeShouldBe(int $code): void
    {
        Assertions::assertSame($code, $this->response->getStatusCode());
    }

    /**
     * @param PyStringNode $jsonString
     *
     * @Then /^(?:the )?response should contain json:$/
     */
    public function theResponseShouldContainJson(PyStringNode $jsonString): void
    {
        $expected = $this->jsonDecode(FeatureSharedStorage::replacePlaceholder($jsonString->getRaw()));
        $actual = $this->jsonDecode($this->response->getContent());

        Assertions::assertGreaterThanOrEqual(count($expected), count($actual));
        Contains::assertContains($expected, $actual);
    }

    /**
     * @param PyStringNode $string
     *
     * @Then /^(?:the )?response body should be:$/
     */
    public function theResponseBodyShouldBeEqualToString(PyStringNode $string): void
    {
        Assertions::assertEquals($string->getRaw(), $this->response->getContent());
    }

    /**
     * Prints last response body.
     *
     * @Then print last response
     */
    public function printResponse(): void
    {
        $body = json_decode($this->response->getContent(), true);
        $body = ($body && is_array($body)) ? $this->prettyJsonEncode($body) : mb_substr($this->response->getContent(), 0, 3000);

        echo sprintf(
            "%s %s => %d\n%s\n%s",
            $this->request->getMethod(),
            $this->request->getUri(),
            $this->response->getStatusCode(),
            $this->request->attributes->get('_controller', ''),
            $body
        );
    }

    /**
     * @param string $text
     *
     * @Then /^(?:the )?response should contain "(.*)"$/
     */
    public function theResponseShouldContain(string $text): void
    {
        Assertions::assertContains($text, $this->response->getContent());
    }

    /**
     * @param string $text
     *
     * @Then /^(?:the )?response should not contain "(.*)"$/
     */
    public function theResponseShouldNotContain(string $text): void
    {
        Assertions::assertNotContains($text, $this->response->getContent());
    }

    /**
     * @param string $header
     * @param string $value
     *
     * @Then /^(?:the )?response header "([^"]+)" should be "([^"]+)"$/
     */
    public function theResponseHeaderShouldBe($header, $value): void
    {
        $value = FeatureSharedStorage::replacePlaceholder($value);

        Assertions::assertEquals($value, $this->response->headers->get($header));
    }

    /**
     * @param string $header
     * @param string $value
     *
     * @Then /^(?:the )?response header "([^"]+)" should contains "([^"]+)"$/
     */
    public function theResponseHeaderShouldContains($header, $value): void
    {
        $value = FeatureSharedStorage::replacePlaceholder($value);

        Assertions::assertContains($value, $this->response->headers->get($header));
    }

    /**
     * @param null|string $isNegative
     * @param string $text
     *
     * @Then /^(?:the)? (?:JSON|json|response) should( not)? contains '([^']+)'$/
     */
    public function theResponseShouldContain2(?string $isNegative, string $text): void
    {
        $text = FeatureSharedStorage::replacePlaceholder($text);
        $actual = $this->response->getContent();

        $expectedRegexp = '|' . preg_quote($text) . '|is';
        if ($isNegative && trim($isNegative) == 'not') {
            Assertions::assertNotRegExp($expectedRegexp, $actual);
        } else {
            Assertions::assertRegExp($expectedRegexp, $actual);
        }
    }

    /**
     * For get field used PropertyAccessor
     * If you want get "par" from {"foo": {"bar": {"par": "12"}}} - use "[foo][bar][par]"
     *
     * @param string $fieldPath
     * @param string $storageKey
     *
     * @Then /^I get "([^"]*)" from response and remember as "([^"]*)"$/
     */
    public function iGetFieldFromResponseAndRememberAsStorageKey(string $fieldPath, string $storageKey): void
    {
        $data = $this->jsonDecode($this->response->getContent());

        if (!$this->getAccessor()->isReadable($data, $fieldPath)) {
            if (mb_strpos('[', $fieldPath) === false) {
                throw new RuntimeException(
                    sprintf('Wrong fieldPath "%s". If you want get "par" from {"foo": {"bar": {"par": "12"}}} - use "[foo][bar][par]"', $fieldPath)
                );
            }

            throw new RuntimeException(sprintf('Can not fetch data from "%s".', $fieldPath));
        }

        FeatureSharedStorage::set($storageKey, $this->getAccessor()->getValue($data, $fieldPath));
    }

    /**
     * @param string $string
     * @param string $storageKey
     *
     * @Then /^I get hash from "([^"]*)" and remember as "([^"]*)"$/
     */
    public function iSaveValueFromParameter(string $string, string $storageKey): void
    {
        $string = FeatureSharedStorage::replacePlaceholder($string);
        $parts = explode('/', $string);

        foreach ($parts as $part) {
            if (preg_match('/^[a-f0-9]{32}$/i', $part, $matches)) {
                FeatureSharedStorage::set($storageKey, $matches[0]);

                return;
            }
        }

        throw new RuntimeException('Can\'t find hash in string: ' . $string);
    }

    /**
     * @Then /^I get response body and remember as "([^"]*)"$/
     *
     * @param string $storageKey
     */
    public function iRememberEverythingFromLastResponseAs(string $storageKey): void
    {
        FeatureSharedStorage::set($storageKey, $this->response->getContent());
    }

    /**
     * @Then print target url
     */
    public function printTargetUrl(): void
    {
        if (!$this->response instanceof RedirectResponse) {
            throw new RuntimeException('Last response not RedirectResponse');
        }

        echo $this->response->getTargetUrl();
    }

    /**
     * @param string $targetUrl
     *
     * @Then /^I am redirected to "(?P<page>[^"]+)"$/
     */
    public function iFollowTheRedirection(string $targetUrl): void
    {
        $targetUrl = FeatureSharedStorage::replacePlaceholder($targetUrl);
        /** @var RedirectResponse $response */
        $response = $this->response;

        Assertions::assertInstanceOf(RedirectResponse::class, $response);
        Contains::assertContains($targetUrl, str_replace($this->getBaseHost(), '', $response->getTargetUrl()));
    }

    /**
     * @param string $url
     * @param string $method
     * @param mixed $content
     *
     * @throws Exception
     * @throws TypeError
     */
    protected function sendRequest(string $url, string $method, $content = null): void
    {
        $request = $this->request;

        $this->request = Request::create(
            $this->preparePath($url),
            $method,
            [],
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $content
        );

        $this->request->request->add($request->request->all());
        $this->request->headers->add(array_merge($request->headers->all(), ['host' => $this->getBaseHost()]));

        // catch StreamedResponse
        ob_start();
        $this->response = $this->getKernel()->handle($this->request);
        ob_end_clean();

        /** @var Cookie $cookie */
        foreach ($this->response->headers->getCookies() as $cookie) {
            if ($cookie->getExpiresTime()!== 0 && $cookie->getExpiresTime() < time() + 10) {
                $this->request->cookies->remove($cookie->getName());
            } else {
                $this->request->cookies->set($cookie->getName(), $cookie->getValue());
            }
        }

        $this->jsonHolder->setJson($this->response->getContent());
    }

    /**
     * @Given /^the JSON array contains (\d+) elements$/
     */
    public function theJSONArrayContainsElement($arg1)
    {
        $actual = $this->jsonDecode($this->response->getContent());
        Assertions::assertTrue(is_array($actual));
        Assertions::assertCount((int) $arg1, $actual);
    }
}
