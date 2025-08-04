<?php namespace Models\Core;

use Zephyrus\Application\Configuration;
use Zephyrus\Core\Application as BaseApplication;
use Zephyrus\Database\Core\Database;
use Zephyrus\Network\Request;

final class Application extends BaseApplication
{
    public function __construct(Request $request)
    {
        $this->initializeHelpers();
        DatabaseSession::initiate(Configuration::getDatabase());
        DatabaseSession::getInstance()->start();
        parent::__construct($request);
        CustomErrorHandler::initializeFormExceptions();
    }

    public function getDatabase(): Database
    {
        return DatabaseSession::getInstance()->getDatabase();
    }

    private function initializeHelpers(): void
    {
        require_once(realpath(ROOT_DIR . '/app/formats.php'));
        require_once(realpath(ROOT_DIR . '/app/functions.php'));
    }
}
