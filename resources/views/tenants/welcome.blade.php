<p>These tips will help you make the most of this app:</p>

<ol>
@if (!isset($user->tenant->property_id))
    <li><strong>Add where you live</strong></li>
    <li>Invite your estate agent or landlord</li>
</ol> 
<div class="submit">
	<button type="submit" class="btn btn-outline-info mt-2">Add where you live </button>
</div>
@else
    <li>Add a <a href="/profile/edit">profile picture</a></li>
</ol> 
<div class="submit">
	<button type="submit" class="btn btn-outline-info mt-2">Get you started </button>
</div>
@endif


