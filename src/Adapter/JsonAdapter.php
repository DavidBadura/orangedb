<?php declare(strict_types=1);

namespace DavidBadura\OrangeDb\Adapter;

use Webmozart\Json\JsonDecoder;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class JsonAdapter extends AbstractAdapter
{
    private $decoder;

    public function __construct(string $directory)
    {
        parent::__construct($directory, 'json');

        $this->decoder = new JsonDecoder();
        $this->decoder->setObjectDecoding(JsonDecoder::ASSOC_ARRAY);
    }

    public function load(string $collection, string $identifier): array
    {
        return $this->decoder->decodeFile($this->findFile($collection, $identifier));
    }
}
