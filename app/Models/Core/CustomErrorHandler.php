<?php namespace Models\Core;

use Models\Exceptions\FormException;
use Zephyrus\Application\ErrorHandler;
use Zephyrus\Application\Flash;
use Zephyrus\Network\Response;

final class CustomErrorHandler
{
    /**
     * Traps FormException to display error and feedback automatically. Returns to the previous page to display the
     * errors.
     */
    public static function initializeFormExceptions(): void
    {
        ErrorHandler::getInstance()->exception(function (FormException $exception) {
            Flash::error($exception->getForm()?->getErrorMessages() ?? $exception->getMessage());
            $redirectPath = $exception->getRedirectPath() ?? Application::getInstance()->getRequest()->getReferer();
            Response::builder()->redirect($redirectPath)->send();
        });
    }
}
