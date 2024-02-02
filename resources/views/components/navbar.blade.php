<nav class="bg-gray-800 p-4">
    <ul class="flex space-x-4">
        <li><a href="{{ route('dashboard') }}" class="text-white hover:text-gray-300">Home</a></li>
        <li><a href="{{ route('categories.index') }}" class="text-white hover:text-gray-300">Catégories</a></li>
        <li><a href="{{ route('tasks.index') }}" class="text-white hover:text-gray-300">Taches</a></li>
        <li><a href="{{ route('planning.index') }}" class="text-white hover:text-gray-300">Planning</a></li>
    </ul>
</nav>
