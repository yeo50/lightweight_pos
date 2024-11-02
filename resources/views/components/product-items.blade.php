 @props(['products' => [], 'notFound'])

 @if ($notFound)
     <tr>
         <td colspan="6" class="text-center py-2">Not Found Products</td>
     </tr>
 @endif

 @foreach ($products as $key => $item)
     <tr :key={{ $key }}>
         <td class="ps-3 w-8">
             <input type="checkbox" wire:model="ids" value="{{ $item->id }}" class="bg-gray-300"
                 wire:click="selectedItem">
         </td>

         <td class=" text-start px-3 py-2 ">{{ $key + 1 }}</td>
         <td class=" text-start px-3 py-2">{{ $item->name }}</td>
         <td class=" text-start px-3 py-2">{{ $item->price }} $</td>
         <td class=" text-start px-3 py-2">{{ $item->quantity }}</td>
         <td class=" text-start px-3 py-2">{{ $item->barcode }}</td>
         <td class=" text-start px-3 py-2">

             <img src="{{ asset('storage/' . $item->photo) }}" alt="{{ $item->name }}"
                 class="w-20 h-20 object-contain">


         </td>
         @can('edit-product', $item)
             <td><a href="{{ route('products.edit', $item->id) }}" class="font-semibold text-violet-600">Edit</a></td>
         @endcan

     </tr>
 @endforeach
