<x-layout-app page-title="Home">

    <div class="w-100 p-4">

        <h3>Home</h3>

        <hr>

        <div class="d-flex">
            <x-info-title-value item-title="Total Colaborators" :item-value="$data['total_colaborators']"></x-info-title-value>
            <x-info-title-value item-title="Total Deleted Colaborators" :item-value="$data['total_colaborators_deleted']"></x-info-title-value>
            <x-info-title-value item-title="Total Salary" :item-value="$data['total_salary']"></x-info-title-value>
        </div>

        <div class="d-flex">
            <x-info-title-collection item-title="Colaborators By Department" :collection="$data['total_colaborators_per_department']"></x-info-title-collection>
            <x-info-title-collection item-title="Salary By Department" :collection="$data['total_salary_by_department']"></x-info-title-collection>
        </div>

    </div>

</x-layout-app>