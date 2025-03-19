<div>
    <h1 class="text-2xl">
        Current player playing : Player{{ $this->currentPlayer }}
    </h1>

    <div class="grid grid-cols-3 max-w-72">
        @for($i = 0; $i < 3; $i++)
            @for($j = 0; $j < 3; $j++)
                @if ($this->isSelectedByUserOne($i, $j))
                    <div class="p-2 border border-red-400 cursor-pointer hover:bg-blue-100 text-blue-500 flex justify-center items-center">
                        <x-icons.circle size="size-12" />
                    </div>
                @elseif($this->isSelectedByUserTwo($i, $j))
                    <div class="p-2 border border-red-400 cursor-pointer hover:bg-red-100 text-red-500 flex justify-center items-center">
                        <x-icons.x-mark size="size-12"/>
                    </div>
                @else
                    <div class="p-8 border border-red-400 cursor-pointer hover:bg-red-100" wire:click="checkBox('{{ $i }}', '{{ $j }}')">

                    </div>
                @endif
            @endfor
        @endfor
    </div>

    <div>@error('error') {{ $message }} @enderror</div>

    <div x-data="{show: false, player: $wire.entangle('currentPlayer')}" @current-player-won.window="show = true;">
        <div x-show="show" x-text="'The Player' + player + ' has won the game !!'">
        </div>
    </div>
</div>
