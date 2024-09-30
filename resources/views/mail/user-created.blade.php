<x-mail::message>
    # Introduction

    The body of your message.

    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
{{-- $existingProductKey = collect($this->products)->search(function ($item) use ($product) {
                return $item['barcode'] == $product->barcode;
            });

            if ($existingProductKey !== false) {
                $count = $this->products[$existingProductKey]['count'];
                $price = $this->products[$existingProductKey]['price'];
                $resetTotal = $price * $count;
                $this->total -= $resetTotal;
                $this->products[$existingProductKey]['count']++;
                $count = $this->products[$existingProductKey]['count'];
                $total = $price * $count;
                $this->total += $total;
            } else {
                $this->products[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'barcode' => $product->barcode,
                    'count' => 1,
                ];
                $total = $product->price * 1;
                $this->total += $total;
            } --}}
