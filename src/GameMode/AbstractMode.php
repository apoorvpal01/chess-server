<?php

namespace ChessServer\GameMode;

use Chess\Game;
use ChessServer\Command\AsciiCommand;
use ChessServer\Command\CastlingCommand;
use ChessServer\Command\CapturesCommand;
use ChessServer\Command\EventsCommand;
use ChessServer\Command\FenCommand;
use ChessServer\Command\HeuristicPictureCommand;
use ChessServer\Command\HistoryCommand;
use ChessServer\Command\IsCheckCommand;
use ChessServer\Command\IsMateCommand;
use ChessServer\Command\PieceCommand;
use ChessServer\Command\PiecesCommand;
use ChessServer\Command\PlayFenCommand;
use ChessServer\Command\StatusCommand;
use ChessServer\Command\UndoMoveCommand;

abstract class AbstractMode
{
    protected $game;

    protected $resourceIds;

    protected $hash;

    public function __construct(Game $game, array $resourceIds)
    {
        $this->game = $game;
        $this->resourceIds = $resourceIds;
    }

    public function getGame()
    {
        return $this->game;
    }

    public function setGame(Game $game)
    {
        $this->game = $game;

        return $this;
    }

    public function getResourceIds(): array
    {
        return $this->resourceIds;
    }

    public function setResourceIds(array $resourceIds)
    {
        $this->resourceIds = $resourceIds;

        return $this;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function res($argv, $cmd)
    {
        try {
            switch (get_class($cmd)) {
                case AsciiCommand::class:
                    return [
                        $cmd->name => $this->game->ascii(),
                    ];
                case CastlingCommand::class:
                    return [
                        $cmd->name => $this->game->castling(),
                    ];
                case CapturesCommand::class:
                    return [
                        $cmd->name => $this->game->captures(),
                    ];
                case EventsCommand::class:
                    return [
                        $cmd->name => $this->game->events(),
                    ];
                case FenCommand::class:
                    return [
                        $cmd->name => $this->game->fen(),
                    ];
                case HeuristicPictureCommand::class:
                    return [
                        $cmd->name => [
                            'dimensions' => (new \Chess\HeuristicPicture(''))->getDimensions(),
                            'balance' => $this->game->heuristicPicture(true),
                        ],
                    ];
                case HistoryCommand::class:
                    return [
                        $cmd->name => $this->game->history(),
                    ];
                case IsCheckCommand::class:
                    return [
                        $cmd->name => $this->game->isCheck(),
                    ];
                case IsMateCommand::class:
                    return [
                        $cmd->name => $this->game->isCheck(),
                    ];
                case PieceCommand::class:
                    return [
                        $cmd->name => $this->game->piece($argv[1]),
                    ];
                case PiecesCommand::class:
                    return [
                        $cmd->name => $this->game->pieces($argv[1]),
                    ];
                case PlayFenCommand::class:
                    return [
                        $cmd->name => [
                            'turn' => $this->game->status()->turn,
                            'legal' => $this->game->playFen($argv[1]),
                            'check' => $this->game->isCheck(),
                            'mate' => $this->game->isMate(),
                            'movetext' => $this->game->movetext(),
                            'fen' => $this->game->fen(),
                        ],
                    ];
                case StatusCommand::class:
                    return [
                        $cmd->name => $this->game->status(),
                    ];
                case UndoMoveCommand::class:
                    return [
                        $cmd->name => $this->game->undoMove(),
                    ];
                default:
                    return null;
            }
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
    }
}
