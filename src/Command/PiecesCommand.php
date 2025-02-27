<?php

namespace ChessServer\Command;

use Chess\PGN\Symbol;

class PiecesCommand extends AbstractCommand
{
    public function __construct()
    {
        $this->name = '/pieces';
        $this->description = 'Gets the pieces on the board by color.';
        $this->params = [
            'color' => [
                Symbol::WHITE,
                Symbol::BLACK,
            ],
        ];
        $this->dependsOn = [
            StartCommand::class,
        ];
    }

    public function validate(array $argv)
    {
        return count($argv) - 1 === count($this->params) && in_array($argv[1], $this->params['color']);
    }
}
