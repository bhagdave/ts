<div class="list-group-item list-group-item-action w-100" style="display:none;" id="VueSearch">
    <search></search>
</div>
<div class="list-group-item list-group-item-action w-100" id="FakeVueSearch" onclick="$('#VueSearch').toggle();$('#FakeVueSearch').toggle(); $('#VueSearch input').focus();$('.active').removeClass('active');">
    <div class="input-group">
        <input type="search" class="form-control" placeholder="Search">  
    </div>
</div>
