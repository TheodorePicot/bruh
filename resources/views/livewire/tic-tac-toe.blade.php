<div class="flex flex-col justify-center items-center">
    <h1 class="text-3xl mb-12">
        Current player playing :
        <span
            @class([
                'text-blue-500' => $this->currentPlayer == 1,
                'text-red-500' => $this->currentPlayer == 2
            ])
        >
            Player {{ $this->currentPlayer }} ({{ $this->currentPlayer == 1 ? 'O' : 'X' }})
        </span>
    </h1>

    <div>@error('error') {{ $message }} @enderror</div>

    <div class="grid grid-cols-3 max-w-92 border border-red-400">
        @for($i = 0; $i < 3; $i++)
            @for($j = 0; $j < 3; $j++)
                @if ($this->isSelectedByUserOne($i, $j))
                    <div class="p-6 border border-red-400 cursor-pointer hover:bg-blue-100 text-blue-500 flex justify-center items-center">
                        <x-icons.circle size="size-12" />
                    </div>
                @elseif($this->isSelectedByUserTwo($i, $j))
                    <div class="p-6 border border-red-400 cursor-pointer hover:bg-red-100 text-red-500 flex justify-center items-center">
                        <x-icons.x-mark size="size-12"/>
                    </div>
                @else
                    <div @class([
                                'p-12 border border-red-400 cursor-pointer',
                                'hover:bg-blue-100' => $this->currentPlayer == 1,
                                'hover:bg-red-100' => $this->currentPlayer == 2,
                            ]) wire:click="checkCell('{{ $i }}', '{{ $j }}')">

                    </div>
                @endif
            @endfor
        @endfor
    </div>


    <div x-data="{show: false, player: $wire.entangle('currentPlayer')}" @current-player-won.window="show = true;" @new-game.window="show = false">
        <div x-show="show" class="absolute flex flex-col items-center justify-center gap-12 top-0 right-0 w-full h-full z-50 bg-black/20 backdrop-blur-md" x-transition>
            <div x-text="'Player ' + player + ' has won the game !!'" class="text-6xl font-bold">
            </div>
            <x-button class="text-2xl font-semibold p-6" wire:click="newGame">
                Play Again
            </x-button>
        </div>
    </div>
</div>
