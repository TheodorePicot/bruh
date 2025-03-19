<?php

namespace App\Livewire;

use App\Traits\TicTacToeValidator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class TicTacToe extends Component
{
    use TicTacToeValidator;

    public array $ticTacToeMatrix;

    public int $currentPlayer = 1;

    public bool $gameOver = false;

    public function mount()
    {
        $this->ticTacToeMatrix = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];
    }

    /**
     * @throws ValidationException
     */
    public function checkBox($i, $j): void
    {
        if ($this->gameOver) {
            return;
        }

        $this->validateSelection($i, $j);

        $this->ticTacToeMatrix[$i][$j] = $this->currentPlayer;
        $this->updateCurrentPlayerCounts($i, $j);

        if ($this->currentPlayerHasWon($i, $j)) {
            $this->dispatch('current-player-won');
            $this->gameOver = true;
            return;
        }

        $this->changePlayer();
    }

    private function changePlayer(): void
    {
        if ($this->currentPlayer == 1) $this->currentPlayer = 2;
        else $this->currentPlayer = 1;
    }

    public function isSelectedByUserOne($i, $j): bool
    {
        return $this->ticTacToeMatrix[$i][$j] === 1;
    }

    public function isSelectedByUserTwo($i, $j): bool
    {
        return $this->ticTacToeMatrix[$i][$j] === 2;
    }
}
