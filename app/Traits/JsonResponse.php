<?php
/**
 * File JsonResponse.php
 *
 * @author Badis Guesmi
 * @package AES
 * @version 1.0
 */
namespace App\Traits;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Class JsonResponse
 * Simple response object for AES application
 * Response format:
 * {
 *   'success': true|false,
 *   'data': [],
 *   'error': '',
 *    'message: ''
 * }
 *
 * @package Laravue
 */
class JsonResponse implements \JsonSerializable
{
    const STATUS_SUCCESS = true;
    const STATUS_ERROR = false;

    /**
     * Data to be returned
     * @var mixed
     */
    private $data = [];

    /**
     * Error message in case process is not success. This will be a string.
     *
     * @var string
     */
    private $error = '';

    /**
     * Message in case process is success. This will be a string.
     *
     * @var string
     */
    private $message = '';


    /**
     * @var bool
     */
    private $success = false;

    /**
     * JsonResponse constructor.
     * @param mixed $data
     * @param string $error
     */
    public function __construct($data = [], $error = [],string $message = '')
    {
        if ($this->shouldBeJson($data)) {
            $this->data = $data;
        }

        $this->error = $error;
        $this->message = $message;
        $this->success = empty($error);
    }


    /**
     * Success with data
     *
     * @param array $data
     */
    public function success($data = [])
    {
        $this->success = true;
        $this->data = $data;
        $this->error = '';
        $this->message = '';
    }

    /**
     * Fail with error message
     * @param string $error
     */
    public function fail($error = '',$message='')
    {
        $this->success = false;
        $this->error = $error;
        $this->message = $message;
        $this->data = [];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'message' => $this->message,
            'error' => $this->error,
        ];
    }


    /**
     * Determine if the given content should be turned into JSON.
     *
     * @param  mixed  $content
     * @return bool
     */
    private function shouldBeJson($content): bool
    {
        return $content instanceof Arrayable ||
            $content instanceof Jsonable ||
            $content instanceof \ArrayObject ||
            $content instanceof \JsonSerializable ||
            is_array($content);
    }
}
