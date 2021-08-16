<?php


namespace toom1996\http;


use toom1996\base\Component;
use toom1996\base\InvalidCallException;
use toom1996\helpers\FileHelper;
use YiiS;

/**
 * Class View
 *
 * @author: TOOM <1023150697@qq.com>
 */
class View extends Component
{

    public $defaultExtension = 'php';


    private $_viewCache;

    /**
     *
     * @param  string  $view The view name.
     * @param  array  $params The parameters (name-value pairs) that will be extracted and made available in the view file.
     */
    public function render(string $view, array $params = [])
    {
        $viewFile = $this->findViewFile($view);
        return $this->renderFile($viewFile, $params);
    }


    public function findViewFile($view)
    {
        if (strncmp($view, '@', 1) === 0) {
            // e.g. "@app/views/main"
            $file = YiiS::getAlias($view);
        } elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
//            if (YiiS::$app->controller !== null) {
//                $file = Yii::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
//            } else {
//                throw new InvalidCallException("Unable to locate view file for view '$view': no active controller.");
//            }
        } else {
            throw new InvalidCallException("Unable to resolve view file for view '$view': no active view context.");
        }

        if (pathinfo($file, PATHINFO_EXTENSION) !== '') {
            return $file;
        }
        $path = $file . '.' . $this->defaultExtension;
        if ($this->defaultExtension !== 'php' && !is_file($path)) {
            $path = $file . '.php';
        }

        return $path;
    }

    /**
     *
     * @param $viewFile
     * @param $params
     * @param  null  $context
     */
    public function renderFile($viewFile, $params, $context = null)
    {
        $viewFile = $requestedFile = YiiS::getAlias($viewFile);
        if (!is_file($viewFile)) {
            throw new ViewNotFoundException("The view file does not exist: $viewFile");
        }

//        $oldContext = $this->context;
//        if ($context !== null) {
//            $this->context = $context;
//        }
        $output = '';
//        $this->_viewFiles[] = [
//            'resolved' => $viewFile,
//            'requested' => $requestedFile
//        ];

        $output = $this->renderPhpFile($viewFile, $params);
//        array_pop($this->_viewFiles);
//        $this->context = $oldContext;

        return $output;
    }

    /**
     *
     * @param $_file_
     * @param  array  $_params_
     *
     * @return false|string
     * @throws \Throwable
     */
    public function renderPhpFile($_file_, $_params_ = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        try {
            require $_file_;
            return ob_get_clean();
        }catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}