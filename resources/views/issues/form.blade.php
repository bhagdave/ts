@if ($user->isAn('agent') || $user->isAn('admin'))
    <div class="form-check">
        <input class="form-check-input" type="radio" name="confidential" id="confidential" value="true" @if(isset($issue) and ($issue->extra_attributes->confidential == "true")) checked @endif>
        <label class="form-check-label" for="confidential">
            This issue is confidential and updates should only be seen by myself and the contractor
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="confidential" id="confidential" value="false" @if(isset($issue) and ($issue->extra_attributes->confidential !== "true")) checked @endif>
        <label class="form-check-label" for="public">
           This issue is public. All parties in the stream will see all past and future activity on this issue.
        </label>
    </div>
@endif
@if ($user->isAn('tenant'))
    <div class="form-check">
        <input class="form-check-input" type="radio" name="private" id="private" value="true" >
        <label class="form-check-label" for="private">
            This issue is private and updates should only be seen by myself and the agent/landlord
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="private" id="private" value="false" checked>
        <label class="form-check-label" for="public">
           This issue is public. All parties who can access the property will see activity on this issue.
        </label>
    </div>
    <input type="hidden" name="creator_id" value="{{ $user->sub }}">
@endif

@if ($user->isAn('agent') || $user->isAn('admin') || $user->isAn('contractor') || $user->isAn('landlord'))
    <hr>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="duedate">Due Date</label>
            <input type="date" name="duedate" class="form-control date @error('duedate') is-invalid @enderror" id="duedate" placeholder="" value='{{ $issue->extra_attributes->duedate ?? "No Date Set"}}'>
        </div>
        <div class="form-group col-md-6">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                <option value="Open" @if(isset($issue) and ($issue->extra_attributes->status == "Open")) selected @endif>Open</option>
                <option value="Pending Landlord" @if(isset($issue) and ($issue->extra_attributes->status == "Pending Landlord")) selected @endif>Pending Landlord</option>
                <option value="Pending Contractor" @if(isset($issue) and ($issue->extra_attributes->status == "Pending Contractor")) selected @endif>Pending Contractor</option>
                <option value="On-Going" @if(isset($issue) and ($issue->extra_attributes->status == "On-Going")) selected @endif>On-Going</option>
                <option value="Closed" @if(isset($issue) and ($issue->extra_attributes->status == "Closed")) selected @endif>Closed</option>
            </select>
        </div>

    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputEmail4">Weight/Priority</label>
            <select id="priority" name="priority" class="form-control @error('priority') is-invalid @enderror">
                <option value="1" @if(isset($issue) and ($issue->extra_attributes->priority == 1)) selected @endif>1</option>
                <option value="2" @if(isset($issue) and ($issue->extra_attributes->priority == 2)) selected @endif>2</option>
                <option value="3" @if(isset($issue) and ($issue->extra_attributes->priority == 3)) selected @endif>3</option>
                <option value="4" @if(isset($issue) and ($issue->extra_attributes->priority == 4)) selected @endif>4</option>
                <option value="5" @if(isset($issue) and ($issue->extra_attributes->priority == 5)) selected @endif>5</option>
            </select>
        </div>
    </div>
    @if ($user->isAn('agent') || $user->isAn('landlord'))
        <hr>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="category">Type of contractor required</label>
                <select id="category" name="categories_id" class="form-control @error('category') is-invalid @enderror">
                    <option value="" >None</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(isset($issue) and ($issue->categories_id == $category->id)) selected @endif>{{ $category->name  }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assignee">Contractor</label>
                <select id="assignee" name="assignee" class="form-control @error('assignee') is-invalid @enderror" @if((!isset($contractors)) || (count($contractors) == 0)) disabled @endif>
                    @if (isset($contractors) && (count($contractors) > 0))
                        <option value="" >None</option>
                        @foreach($contractors as $contractor)
                            <option value="{{ $contractor->id }}" @if(isset($issue) and ($issue->contractors_id == $contractor->id)) selected @endif>{{ $contractor->name  }}</option>
                        @endforeach
                    @else
                        <option value="">No contractors invited yet</option>
                    @endif
                </select>
            </div>
            @if(!isset($issue->contractors_id))
                <div class="form-group col-md-6">
                    <label for="invite">Invite Contractor</label>
                    <input type="text" name="invite" class="form-control @error('invite') is-invalid @enderror" id="invite" placeholder="contractor@contractor.com" value="{{ $issue->invite ?? ""}}">
                    <small id="inviteHelp" class="form-text text-muted">
                        Enter an email address for a contractor to invite them to work on the issue.
                    </small>
                </div>
            @endif

        </div>
    @endif
@endif
