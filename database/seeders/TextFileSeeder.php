<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use SplFileObject;

abstract class TextFileSeeder extends Seeder
{
    private const MAX_CHUNK_SIZE = 8192;

    /**
     *
     * @param string $filepath A filepath to a text file with seeding lines.
     * @param class-string<Model> $model A model class FQN to seed.
     */
    public function __construct(final private string $filepath, final private string $model)
    {
        //
    }

    /**
     * Maps a line from the file into model attributes array.
     *
     * @return associative-array The attributes for a new model
     * represented by the line of the text file
     */
    abstract public function mapToAttributes(string $line): array;

    /**
     * Stores a chunk of attributes into the array
     *
     * @param array<int,mixed> $chunk
     */
    private function storeChunk(array $chunk): void
    {
        $this->model::query()->withoutGlobalScopes()->insert($chunk);
    }

    /**
     * Seeds the model.
     */
    public function run(): void
    {
        $file = new SplFileObject($this->filepath, 'r');
        $file->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);

        $chunk = [];
        while (! $file->eof()) {
            $line = $file->fgets();
            if ($line === '') {
                continue;
            }

            $chunk[] = $this->mapToAttributes($line);
            if (count($chunk) >= self::MAX_CHUNK_SIZE) {
                $this->storeChunk($chunk);
                $chunk = [];
            }
        }

        if (count($chunk) !== 0) {
            $this->storeChunk($chunk);
        }
    }
}
