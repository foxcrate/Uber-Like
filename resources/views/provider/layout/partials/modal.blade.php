<div class="modal fade" id="modal-incoming" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center incoming-tit" id="myModalLabel">@lang('user.Incoming Request')</h4>
            </div>
            <div class="modal-body">
                <div class="incoming-img bg-img" id="user-image" style="background-image: url({{asset('img/img1.png')}});"></div>
                <div class="text-center">
                    <h3 id="usser-name">@lang('user.Paul Walker')</h3>
                </div>
            </div>
            <div class="modal-footer row no-margin">
                <button type="button" class="btn btn-primary incoming-btn">@lang('user.Accept')</button>
                <button type="button" class="btn btn-default incoming-btn" data-dismiss="modal">@lang('user.Cancel')</button>
            </div>
        </div>
    </div>
</div>
