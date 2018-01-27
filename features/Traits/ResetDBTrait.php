<?php declare(strict_types=1);

namespace BehatTests\Traits;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;

/**
 * Trait ResetDBTrait
 *
 * @package BehatTests\Traits
 */
trait ResetDBTrait
{
    /** @var string */
    private static $dbTargetFile = './var/cache/behat/test.db';

    /** @var string */
    private static $dbPatternFile;

    /** @BeforeSuite */
    public static function beforeSuite()
    {
        self::buildDBPatternFilePath();

        if (self::isRequiredReloadFixtures()) {
            self::loadInitialFixtures();
        }
    }

    /**
     * Builds db pattern file path name
     */
    private static function buildDBPatternFilePath()
    {
        self::$dbPatternFile = self::$dbTargetFile . '.' . self::getFixturesCheckSum();
    }

    /**
     * @return bool
     */
    private static function isRequiredReloadFixtures()
    {
        return !file_exists(self::$dbTargetFile) || !file_exists(self::$dbPatternFile);
    }

    /**
     * @return mixed
     *
     * @throws \Exception
     */
    private static function getFixturesCheckSum()
    {
        $output = shell_exec('bin/phing behat:get-fixtures-checksum');
        if (!preg_match('/checksum:([\w]+)/', $output, $m)) {
            throw new \Exception('Fixtures checksum was not parsed: ' . $output);
        }

        return $m[1];
    }

    /**
     * Loads initial fixtures
     */
    private static function loadInitialFixtures()
    {
        echo shell_exec('bin/phing behat:prepare-db-for-tests');
        copy(self::$dbPatternFile, self::$dbTargetFile);
    }

    /**
     * Tag 'skipResetDB' will ignore DB reload from initial pattern file.
     *
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     *
     * @return bool
     */
    public function resetDB(BeforeScenarioScope $scope)
    {
        if ($scope->getScenario()->hasTag('skipResetDB')) {
            return false;
        }

        if (!file_exists(self::$dbPatternFile)) {
            echo 'Pattern file does not exists: ' . self::$dbPatternFile;
        }

        copy(self::$dbPatternFile, self::$dbTargetFile);

        return true;
    }

    /**
     * Ignoring if tag 'elasticaPopulate' not exist.
     *
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     *
     * @return bool
     */
    public function populateElasticaIndex(BeforeScenarioScope $scope)
    {
        if (!$scope->getScenario()->hasTag('elasticaPopulate')) {
            return false;
        }

        exec('bin/phing behat:elastica-populate-app  2> /dev/null');

        return true;
    }

    /**
     * Build current Fastest target DB file path
     *
     * @param int $number
     *
     * @return string
     */
    private static function getChannelDBName($number)
    {
        return './app/cache/behat/test_channel_' . $number . '.db';
    }
}
