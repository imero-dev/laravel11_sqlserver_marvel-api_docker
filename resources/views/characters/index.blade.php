<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Personajes de Marvel</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-4xl font-bold mb-6 text-center">Personajes de Marvel</h1>

        <div class="flex justify-center mb-4">
            <button id="toggleViewButton" class="bg-blue-500 text-white px-4 py-2 rounded">
                Cambiar a vista de tarjetas
            </button>
        </div>

        <form method="GET" action="{{ route('characters.index') }}" class="flex justify-center mb-8">
            <input type="text" name="search" class="border border-gray-300 rounded-l px-4 py-2 w-1/3" placeholder="Buscar..." value="{{ $search }}">
            <select name="sort" class="border-t border-b border-gray-300 px-4 py-2">
                <option value="">Ordenar por nombre</option>
                <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>Ascendente</option>
                <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Descendente</option>
            </select>
            <button type="submit" class="bg-blue-500 text-white rounded-r px-4 py-2">Aplicar</button>
        </form>

        @if(isset($error))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ $error }}
            </div>
        @endif

        <div id="charactersContainer" class="grid grid-cols-1 gap-6">
        @foreach($characters as $character)
            <div class="character-item flex items-center bg-white rounded shadow p-4">
                <img src="{{ $character['thumbnail']['path'] . '/standard_xlarge.' . $character['thumbnail']['extension'] }}" alt="{{ $character['name'] }}" class="character-image w-16 h-16 object-cover rounded-full cursor-pointer" onclick="showImage(this)">
                <div class="character-name ml-4">
                    <h2 class="text-xl font-semibold">{{ $character['name'] }}</h2>
                </div>
            </div>
        @endforeach
        </div>
    </div>

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden">
        <img id="modalImage" src="" alt="Imagen del personaje">
        <button id="closeButton" onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-white text-2xl">&times;</button>
    </div>

    <script>
        function showImage(imgElement) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const rect = imgElement.getBoundingClientRect();

            const highResImageUrl = imgElement.src.replace('/standard_xlarge', '');

            //preload image
            const preloadImage = new Image();
            preloadImage.src = highResImageUrl;
            preloadImage.onload = function() {
                modalImage.src = highResImageUrl;
                modalImage.style.position = 'fixed';
                modalImage.style.top = rect.top + 'px';
                modalImage.style.left = rect.left + 'px';
                modalImage.style.width = rect.width + 'px';
                modalImage.style.height = rect.height + 'px';
                modalImage.style.transition = 'all 0.3s ease-in-out';
                modalImage.style.zIndex = '1000';
                
                modal.classList.remove('hidden');
                
                modalImage.getBoundingClientRect();
                
                modalImage.style.top = '50%';
                modalImage.style.left = '50%';
                modalImage.style.transform = 'translate(-50%, -50%)';
                modalImage.style.width = '80vw';
                modalImage.style.height = 'auto';
                
                const closeButton = document.getElementById('closeButton');
                closeButton.classList.add('hidden');
                
                setTimeout(() => {
                    closeButton.classList.remove('hidden');
                }, 300);
            };
           }

        function closeModal() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const rect = modalImage.getBoundingClientRect();

            modalImage.style.transform = '';
            modalImage.style.top = rect.top + 'px';
            modalImage.style.left = rect.left + 'px';
            modalImage.style.width = rect.width + 'px';
            modalImage.style.height = rect.height + 'px';

            const closeButton = document.getElementById('closeButton');
            closeButton.classList.add('hidden');

            setTimeout(() => {
                modal.classList.add('hidden');
                modalImage.style.position = '';
                modalImage.style.zIndex = '';
                modalImage.src = '';
                modalImage.style.top = '';
                modalImage.style.left = '';
                modalImage.style.width = '';
                modalImage.style.height = '';
            }, 300);
        }

        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        const toggleButton = document.getElementById('toggleViewButton');
        const charactersContainer = document.getElementById('charactersContainer');

        let isListView = true;

        toggleButton.addEventListener('click', function() {
            if (isListView) {
                charactersContainer.classList.remove('grid-cols-1');
                charactersContainer.classList.add('grid-cols-1', 'md:grid-cols-3', 'lg:grid-cols-4');

                document.querySelectorAll('.character-item').forEach(function(item) {
                    item.classList.remove('flex', 'items-center');
                    item.classList.add('text-center');

                    const image = item.querySelector('.character-image');
                    image.classList.remove('w-16', 'h-16', 'rounded-full');
                    image.classList.add('w-full', 'h-auto', 'rounded', 'mb-4');

                    const name = item.querySelector('.character-name');
                    name.classList.remove('ml-4');
                });

                toggleButton.textContent = 'Cambiar a vista de lista';
                isListView = false;
            } else {
                charactersContainer.classList.remove('md:grid-cols-3', 'lg:grid-cols-4');
                charactersContainer.classList.add('grid-cols-1');

                document.querySelectorAll('.character-item').forEach(function(item) {
                    item.classList.add('flex', 'items-center');
                    item.classList.remove('text-center');

                    const image = item.querySelector('.character-image');
                    image.classList.add('w-16', 'h-16', 'rounded-full');
                    image.classList.remove('w-full', 'h-auto', 'rounded', 'mb-4');

                    const name = item.querySelector('.character-name');
                    name.classList.add('ml-4');
                });

                toggleButton.textContent = 'Cambiar a vista de tarjetas';
                isListView = true;
            }
        });
    </script>

    @vite('resources/js/app.js')
</body>
</html>
