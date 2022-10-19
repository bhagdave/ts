
@isset($property)
<p>You have been invited to your online portal by your tenant at {{ $property->inputAddress }}, so you can communicate about any issues.</p>
<p>Just follow the simple instructions within the app and reach out to us if you have any questions!</p>
<div class="submit">
	<button type="submit" class="btn btn-outline-info mt-2">View your property </button>
</div>
@else
<p>Your agent has invited you to your online portal, so you can see all your properties in one place.</p>
<p>Just follow the simple instructions within the app and reach out to your agent if you have any questions!</p>
<div class="submit">
	<button type="submit" class="btn btn-outline-info mt-2">Add a property </button>
</div>
@endisset


