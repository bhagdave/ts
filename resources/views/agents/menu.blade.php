<div class="row">
    <div class="d-flex">
        <a class="btn btn-outline-primary text-center btn-block ml-3 mr-1 mt-2" href="/agency/admin/agents">
            <h3>{{ $counts['agents'] ?? 0 }} </h3>
            <small>Agents</small>
        </a>
        <a class="btn btn-outline-primary text-center btn-block mr-1" href="/property">
            <h3>{{ $counts['properties'] ?? 0 }} </h3>
            <small>Properties</small>
        </a>
        <a class="btn btn-outline-primary text-center btn-block mr-1" href="/landlords">
            <h3>{{ $counts['landlords'] ?? 0 }} </h3>
            <small>Landlords</small>
        </a>
        <a class="btn btn-outline-primary text-center btn-block mr-1" href="/tenants">
            <h3>{{ $counts['tenants'] ?? 0 }} </h3>
            <small>Tenants</small>
        </a>
        <a class="btn btn-outline-primary text-center btn-block mr-1" href="/agency/contractors">
            <h3>{{ $counts['contractors'] ?? 0 }} </h3>
            <small>Contractors</small>
        </a>
    </div>
</div>
