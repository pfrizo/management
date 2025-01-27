<x-layout-app page-title="Delete HR Colaborator">

    <div class="w-25 p-4">

        <h3>Delete HR Colaborator</h3>
    
        <hr>
    
        <p>Are you sure you want to delete this HR Colaborator?</p>
        
        <div class="text-center">
            <h3 class="my-5">{{ $colaborator->name }}</h3>
            <a href="{{ route('hr-users') }}" class="btn btn-secondary px-5">No</a>
            <a href="{{ route('hr.delete-colaborator-confirm', ['id' => Crypt::encrypt($colaborator->id)]) }}" class="btn btn-danger px-5">Yes</a>
        </div>
        
    </div>

</x-layout-app>