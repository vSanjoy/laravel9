<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            @lang('custom_admin.message_copyright') &copy; {{ date('Y') }} @lang('custom_admin.message_reserved')
        </div>
    </div>
</footer>

<input type="hidden" name="admin_url" id="admin_url" value="{{ url('/adminpanel') }}" />
<input type="hidden" name="admin_image_url" id="admin_image_url" value="{{ asset('images/admin/') }}" />
