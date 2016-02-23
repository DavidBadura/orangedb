<?php

namespace DavidBadura\OrangeDb\Adapter;

use Webmozart\Json\JsonDecoder;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class JsonAdapter extends AbstractAdapter
{
    /**
     * @var JsonDecoder
     */
    private $decoder;

    /**
     * @param string $directory
     */
    public function __construct($directory)
    {
        parent::__construct($directory);

        $this->decoder = new JsonDecoder();
        $this->decoder->setObjectDecoding(JsonDecoder::ASSOC_ARRAY);
    }

    /**
     * @param string $collection
     * @param string $identifier
     *
     * @return array
     */
    public function load($collection, $identifier)
    {
        return $this->decoder->decodeFile($this->findFile($collection, $identifier, 'json'));
    }
}
