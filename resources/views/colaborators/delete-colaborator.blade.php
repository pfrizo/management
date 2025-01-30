<x-layout-app page-title="Delete Colaborator">

    <div class="w-25 p-4">

        <h3>Delete Colaborator</h3>
    
        <hr>
    
        <p>Are you sure you want to delete this colaborator?</p>
        
        <div class="text-center">
            <h3 class="my-5">{{ $colaborator->name }}</h3>
            <p>{{ $colaborator->email }}</p>
            <a href="{{ route('hr.management') }}" class="btn btn-secondary px-5">No</a>
            <a href="{{ route('hr.management.delete-colaborator-confirm', ['id' => $colaborator->id]) }}" class="btn btn-danger px-5">Yes</a>
        </div>
        
    </div>

</x-layout-app>