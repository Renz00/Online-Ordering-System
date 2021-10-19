<div>
    @if (count($fav) > 0)
    <button style="position:absolute; top: 8px; right: 16px;" class="btn btn-outline-danger" wire:click="removeFav()"><i class="bi bi-suit-heart-fill"></i> Added to Favorites</button>
    @else
    <button style="position:absolute; top: 8px; right: 16px;" class="btn btn-outline-danger" wire:click="addFav()"><i class="bi bi-suit-heart"></i> Favorite</button>
    @endif
</div>
