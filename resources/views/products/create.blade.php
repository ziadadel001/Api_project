<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Products / Create 
                        </h2>
            <a href="{{ route('product.index') }}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-2">Back</a>
          </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('product.store') }}" method="post">
                    @csrf
                        <div>
                            <label for="" class="text-lg font-medium">Name</label>
                            <div class="my-3">
                            <input value="{{ old('name') }}" name="name" placeholder="Enter product Name" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('name')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                            </div>
                            <label for="" class="text-lg font-medium">description</label>
                            <div class="my-3">
                            <input value="{{ old('description') }}" name="description" placeholder="Enter product description" type="text" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('description')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                            </div>
                            <label for="" class="text-lg font-medium">price</label>
                            <div class="my-3">
                            <input value="{{ old('price') }}" name="price" placeholder="Enter product price" type="number" class="border-gray-300 shadow-sm w-1/2 rounded-lg">
                            @error('price')
                                <p class="text-red-400 font-medium">{{ $message }}</p>
                            @enderror
                            </div>
                          
                        
                            <button class="bg-slate-700 hover:bg-slate-500 text-sm rounded-md text-white px-5 py-3">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
