<?php

namespace App\Livewire;

use App\Traits\TicTacToeValidator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TicTacToe extends Component
{
    use TicTacToeValidator;

    public array $ticTacToeMatrix;

    public array $reviewMatrix;

    public array $moves;

    public int $currentPlayer;

    public bool $gameOver;

    public int $currentTurn;

    public int $reviewTurn;

    public bool $reviewing;

    public function mount()
    {
        $this->ticTacToeMatrix = [
            [0, 0, 0],
            [0, 0, 0],
            [0, 0, 0],
        ];

        $this->reviewMatrix = $this->ticTacToeMatrix;

        $this->moves = [];

        $this->currentPlayer = 1;
        $this->currentTurn = 0;
        $this->gameOver = false;
        $this->reviewing = false;
        $this->setUpPlayerCounts();
    }

    /**
     * @param $i int the column index
     * @param $j int the line index
     * @return void
     * @throws ValidationException
     */
    public function checkCell(int $i, int $j): void
    {
        $this->validateSelection($i, $j);

        $this->ticTacToeMatrix[$i][$j] = $this->currentPlayer;
        $this->incrementTurn();
        $this->updateCurrentPlayerCounts($i, $j);
        $this->moves[] = [$i, $j];

        if ($this->currentPlayerHasWon($i, $j)) {
            $this->endGame('current-player-won');
        } elseif ($this->noMoreAvailableMoves()) {
            $this->endGame('no-more-moves');
        }

        $this->changePlayer();
    }

    private function endGame($dispatchName): void
    {
        $this->dispatch($dispatchName);
        $this->gameOver = true;
        $this->reviewTurn = $this->currentTurn;
        $this->reviewMatrix = $this->ticTacToeMatrix;
        // save Game in db
    }
    /**
     * Changes the current player to the other player when his round is done.
     * @return void
     */
    private function changePlayer(): void
    {
        if ($this->currentPlayer == 1) $this->currentPlayer = 2;
        else $this->currentPlayer = 1;
    }

    private function incrementTurn(): void
    {
        $this->currentTurn++;
    }

    /**
     * Returns true if the current selection is already picked by the player 1
     *
     * @param $i int the column index
     * @param $j int the line index
     * @return bool
     */
    public function isSelectedByUserOne(int $i, int $j, bool $reviewing = false): bool
    {
        if ($reviewing) return $this->reviewMatrix[$i][$j] === 1;
        return $this->ticTacToeMatrix[$i][$j] === 1;
    }


    /**
     * Returns true if the current selection is already picked by the player 2
     *
     * @param $i int the column index
     * @param $j int the line index
     * @return bool
     */
    public function isSelectedByUserTwo(int $i, int $j, bool $reviewing = false): bool
    {
        if ($reviewing) return $this->reviewMatrix[$i][$j] === 2;
        return $this->ticTacToeMatrix[$i][$j] === 2;
    }

    public function newGame(): void
    {
        $this->mount();
    }

    public function previousRound(): void
    {
        if ($this->reviewTurn == 0) return;
        $this->reviewTurn--;
        $move = $this->moves[$this->reviewTurn];
        $this->reviewMatrix[$move[0]][$move[1]] = 0;
    }

    public function nextRound(): void
    {
        if ($this->reviewTurn == $this->currentTurn) return;
        $move = $this->moves[$this->reviewTurn];
        if ($this->reviewTurn % 2 == 0) $this->reviewMatrix[$move[0]][$move[1]] = 1;
        else $this->reviewMatrix[$move[0]][$move[1]] = 2;
        $this->reviewTurn++;
    }

    public function resetReview(): void
    {
        $this->reviewTurn = $this->currentTurn;
        $this->reviewMatrix = $this->ticTacToeMatrix;
    }
}
