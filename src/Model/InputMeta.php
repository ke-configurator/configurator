<?php

namespace App\Model;

use Exception;

use App\Exception\MissingInputSheetException;

class InputMeta
{
    private $fields = [
        'variable',
        'label',
        'reference',
        'default',
        'unit',
        'range_min',
        'range_max',
        'help'
    ];

    /** @var array $data */
    private $data = [];

    /**
     * @param array $headers
     * @param array $data
     * @throws MissingInputSheetException
     */
    public function __construct($headers, $data)
    {
        $missingHeaders     = array_diff($headers, $this->fields);
        $unsupportedHeaders = array_diff($this->fields, $headers);
        if (count($missingHeaders)) {
            throw new MissingInputSheetException('missing fields');
        }
        if (count($unsupportedHeaders)) {
            throw new MissingInputSheetException('unsupported fields');
        }

        foreach ($this->fields as $header) {
            $this->data[$header] = isset($data[$header]) ? $data[$header] : null;
        }
    }

    public function getName()
    {
        return 'ok';
    }

    /**
     * @param string $method
     * @param null|array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        if ('get' === substr($method, 0, 3)) {
            $property = substr($method, 3);
            if ($property[0] === strtoupper($property[0])) {
                $property = lcfirst($property);
            }
        } else {
            $property = $method;
        }
        if (in_array($property, $this->fields)) {
            return isset($this->data[$property]) ? $this->data[$property] : null;
        }

        throw new Exception(sprintf('Method %s::%s does not exist', $method, self::class));
    }

    public function getAsArray()
    {
        return $this->data;
    }
}
