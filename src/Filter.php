<?php
/**
 * Filter class
 *
 * @author      Stanley Sie <swookon@gmail.com>
 * @access      public
 * @version     Release: 1.0
 */

namespace Stanleysie\HkFilter;

/**
 * This class applies filters based on the provided settings.
 */
class Filter
{
    /**
     * @var array The settings containing information about available functions.
     */
    private $settings;

    /**
     * Constructor to initialize the Filter object with settings.
     *
     * @param array $settings The settings containing function information.
     */
    public function __construct()
    {
        $this->settings = [
            'functions' => [
                'emailDataHandle' => [
                    'parameters' => ['$email', '$mode = 0'],
                    'description' => 'Handle email data',
                ],
                'mobileDataHandle' => [
                    'parameters' => ['$mobile', '$mode = 0'],
                    'description' => 'Handle mobile data',
                ],
                'numberDataHandle' => [
                    'parameters' => ['$number', '$mode = 0', '$len = 0'],
                    'description' => 'Handle number data',
                ],
                'textDataHandle' => [
                    'parameters' => ['$text', '$type1 = 0', '$type2 = 0', '$mode = 0'],
                    'description' => 'Handle text data',
                ],
                'urlDataHandle' => [
                    'parameters' => ['$url', '$mode = 0'],
                    'description' => 'Handle URL data',
                ],
                'fixedHtmlTags' => [
                    'parameters' => ['$content = \'\'', '$allowable_tags = \'\''],
                    'description' => 'Fix HTML tags',
                ],
            ],
        ];
    }

    /**
     * Applies the filter function based on the provided parameter.
     *
     * @param string $param The parameter indicating which filter function to apply.
     * @param int $mode The mode parameter for certain filter functions.
     * @return mixed The result of applying the filter function.
     * @throws Exception If the parameter is unsupported or if function arguments do not match.
     */
    public function applyFunction($param, $mode = 0)
    {
        switch ($param) {
            case 'email':
                return $this->callFunction('emailDataHandle', [$mode]);
            case 'mobile':
                return $this->callFunction('mobileDataHandle', [$mode]);
            case 'number':
                return $this->callFunction('numberDataHandle', [$mode]);
            case 'text':
                return $this->callFunction('textDataHandle', [$mode]);
            case 'url':
                return $this->callFunction('urlDataHandle', [$mode]);
            case 'html':
                return $this->callFunction('fixedHtmlTags');
            default:
                throw new Exception('Unsupported parameter: ' . $param);
        }
    }

    /**
     * Calls the specified filter function with provided arguments.
     *
     * @param string $functionName The name of the filter function to call.
     * @param array $arguments The arguments to pass to the filter function.
     * @return mixed The result of calling the filter function.
     * @throws Exception If the function is not found or if function arguments do not match.
     */
    private function callFunction($functionName, $arguments = [])
    {
        if (isset($this->settings['functions'][$functionName])) {
            $functionInfo = $this->settings['functions'][$functionName];
            $parameters = $functionInfo['parameters'];

            /*
            if (count($arguments) != count($parameters)) {
                throw new Exception('Number of arguments does not match for function: ' . $functionName);
            }
            */

            // Build the function call arguments list
            $args = [];
            foreach ($arguments as $arg) {
                $args[] = $arg;
            }

            $iv = new InputValidate();

            // Call the function
            return call_user_func_array(array($iv, $functionName), $args);
        } else {
            throw new Exception('Function not found: ' . $functionName);
        }
    }

}
