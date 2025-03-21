<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait TicTacToeValidator
{
    public array $playerCounts;

    private function setUpPlayerCounts(): void
    {
        $this->playerCounts = [
            1 => [
                'rows' => [0, 0, 0],
                'columns' => [0, 0, 0],
                'diags' => [0, 0]
            ],
            2 => [
                'rows' => [0, 0, 0],
                'columns' => [0, 0, 0],
                'diags' => [0, 0]
            ]
        ];
    }

    /**
     * @throws ValidationException
     */
    private function validateSelection($i, $j)
    {
        $errors = [];
        if ($this->gameOver) {
            $errors['error'] = "The game is over.";
        } elseif ($this->currentSelectionIsOutOfBounds($i, $j)) {
            $errors['error'] = "Your selection is out of bounds.";
        } elseif ($this->currentSelectionIsAlreadyPickedByOpponent($i, $j)) {
            $errors['error'] = "You can't pick this cell, it's already been picked by the other player.";
        } elseif ($this->currentSelectionIsAlreadyPickedByCurrentPlayer($i, $j)) {
            $errors['error'] = "You can't pick this cell, it's already yours.";
        }

        if (count($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function currentSelectionIsAlreadyPickedByOpponent($i, $j)
    {
        return !empty($this->ticTacToeMatrix[$i][$j]) && $this->ticTacToeMatrix[$i][$j] != $this->currentPlayer;
    }

    private function currentSelectionIsAlreadyPickedByCurrentPlayer($i, $j)
    {
        return !empty($this->ticTacToeMatrix[$i][$j]) && $this->ticTacToeMatrix[$i][$j] == $this->currentPlayer;
    }

    private function currentSelectionIsOutOfBounds($i, $j)
    {
        return $i < 0 || $j < 0 || $i > 2 || $j > 2;
    }

    private function updateCurrentPlayerCounts($i, $j): void
    {
        $currentCounts = &$this->playerCounts[$this->currentPlayer];

        $currentCounts['columns'][$i]++;
        $currentCounts['rows'][$j]++;
        if ($i + $j == 2) $currentCounts['diags'][1]++;
        if ($i == $j) $currentCounts['diags'][0]++;
    }

    private function currentPlayerHasWon($i, $j): bool
    {
        $currentCounts = $this->playerCounts[$this->currentPlayer];
        return $currentCounts['columns'][$i] == 3
            || $currentCounts['rows'][$j] == 3
            || $currentCounts['diags'][0] == 3 || $currentCounts['diags'][1] == 3;
    }

    private function noMoreAvailableMoves(): bool
    {
        return $this->currentTurn == 9;
    }
}
