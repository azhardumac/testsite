@if(\App\Extension::where('act', 'custom-captcha')->where('status', 1)->first())
    <div class="form-group row ">
        <div class="col-md-12 recapture">
            @php echo  getCustomCaptcha() @endphp
        </div>
        <div class="col-md-12 mt-4">
            <input type="text" name="captcha" placeholder="@lang('Enter Code')" class="form-control">
        </div>
    </div>
@endif


<style>
    .recapture link ~  div {
        width: 100% !important;
    }
</style>
