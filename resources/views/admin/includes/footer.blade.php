<footer class="footer text-center text-muted">
    @lang('custom_admin.message_copyright') &copy; {{date('Y')}} @lang('custom_admin.message_reserved')</a>.
</footer>

<input type="hidden" name="admin_url" id="admin_url" value="{{ url('/adminpanel') }}" />
<input type="hidden" name="admin_image_url" id="admin_image_url" value="{{asset('images/admin/')}}" />