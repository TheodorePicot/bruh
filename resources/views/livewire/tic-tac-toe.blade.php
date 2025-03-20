<div
    x-data="{
            showEndGame: false,
            player: $wire.entangle('currentPlayer'),
            text: null,
            reviewing: false,
            endGame: function () {
                this.showEndGame = true;
                this.reviewing = true;
            },
            newGame: function () {
                this.showEndGame = false;
                this.reviewing = false;
            }
         }"
    class="flex flex-col justify-center items-center">
    <h1 class="text-3xl mb-12">
        <span x-show="!reviewing" >
            Current player playing :
        </span>
        <span x-show="reviewing" x-cloak>
            Reviewing, player playing next round:
        </span>
        <span
            @class([
                'text-blue-500' => $this->currentPlayer == 1,
                'text-red-500' => $this->currentPlayer == 2
            ])
        >
            Player {{ $this->currentPlayer }} ({{ $this->currentPlayer == 1 ? 'O' : 'X' }})
        </span>
    </h1>

    @error('error')
        <div x-show="showError"
             x-data="{showError: true}"
             x-init="setTimeout(() => showError = false, 3000)"
             wire:key="error-{{ rand(0, 2) }}"
             class="flex absolute items-center p-3 gap-4 w-96 top-10 right-10 bg-white rounded-lg border-2 border-red-400 shadow-lg"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90">
            <div class="text-red-400">
                <x-icons.x-circle size="size-8" />
            </div>
            <div class="flex-1 line-clamp-2">
                {{ $message }}
            </div>
        </div>
    @enderror

        <div class="grid grid-cols-3 max-w-92 border border-red-400">
            @for($i = 0; $i < 3; $i++)
                @for($j = 0; $j < 3; $j++)
                    @if ($this->isSelectedByUserOne($i, $j, $this->reviewing))
                        <div class="p-6 border border-red-400 cursor-pointer hover:bg-blue-100 text-blue-500 flex justify-center items-center">
                            <x-icons.circle size="size-12" />
                        </div>
                    @elseif($this->isSelectedByUserTwo($i, $j, $this->reviewing))
                        <div class="p-6 border border-red-400 cursor-pointer hover:bg-red-100 text-red-500 flex justify-center items-center">
                            <x-icons.x-mark size="size-12"/>
                        </div>
                    @else
                        <div @class([
                                    'p-12 border border-red-400 cursor-pointer',
                                    'hover:bg-blue-100' => $this->currentPlayer == 1,
                                    'hover:bg-red-100' => $this->currentPlayer == 2,
                                ])
                            @if (!$this->reviewing)
                             wire:click="checkCell('{{ $i }}', '{{ $j }}')"
                            @endif>
                        </div>
                    @endif
                @endfor
            @endfor
        </div>
    <div
         @current-player-won.window="endGame(); text = 'Player ' + player + ' has won the game !!';"
         @no-more-moves.window="endGame(); text = 'Draw !! There a no more available moves';"
         @new-game.window="newGame()">
        <div x-show="showEndGame" class="absolute flex flex-col items-center justify-center gap-12 top-0 right-0 w-full h-full z-50 bg-black/20 backdrop-blur-md" x-transition>
            <div x-text="text" class="text-6xl font-bold">
            </div>
            <x-button class="text-2xl font-semibold p-6" @click="$wire.newGame; newGame()">
                Play Again
            </x-button>
            <x-button class="text-2xl font-semibold p-6" @click="showEndGame=false;$wire.set('reviewing', true)">
                Review Game
            </x-button>
        </div>

        <div class="flex flex-col items-center gap-4">
            <x-button class="text-2xl font-semibold p-6 mt-8" x-show="reviewing" @click="$wire.newGame; newGame()">
                Play Again
            </x-button>

            <div class="flex gap-3">
                <x-button variant="secondary" class="text-2xl font-semibold p-6 mt-8" x-show="reviewing" wire:click="previousRound" text="Go to next round in the game">
                    <x-icons.arrow-previous size="size-12" />
                </x-button>

                <x-button variant="secondary" class="text-2xl font-semibold p-6 mt-8" x-show="reviewing" wire:click="nextRound" text="Go to next round in the game">
                    <x-icons.arrow-next size="size-12" />
                </x-button>
            </div>
        </div>
    </div>
</div>
