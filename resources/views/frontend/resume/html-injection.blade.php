<link rel="stylesheet" href="/assets/frontend/css/lordoftherings.css">
<div class="lordoftherings">
    <ul>
        <li>
            <a href="{{ route('front_index') }}" title="{{ _t('theme.download.home') }}"><i class="fa fa-home"></i></a>
        </li>
        <li>
            <a href="{{ route('front_settings') }}" title="{{ _t('theme.download.settings') }}"><i class="fa fa-cog"></i></a>
        </li>
        <li>
            <a href="{{ route('front_theme_download', ['slug' => $slug, 'height' => 'TMP_HEIGHT']) }}" title="{{ _t('theme.download.downpdf') }}" id="downloadPdfLink"><i class="fa fa-download"></i></a>
        </li>
    </ul>
</div>
<script src="/assets/frontend/js/jquery_v1.11.1.js"></script>
<script>

    function themeHeight() {
        var pdfWrapperClass = "{{ config('frontend.pdfWrapperClass') }}",
            htmlHeight      = $('#' + pdfWrapperClass).outerHeight(),
            downloadPdfLink = $('#downloadPdfLink'),
            downloadHref    = downloadPdfLink.attr('href');

        downloadPdfLink.attr('href', downloadHref.replace('TMP_HEIGHT', htmlHeight));
    }

    window.addEventListener( 'resize', themeHeight, false );
    $(window).load(function() {
        themeHeight()
    });
</script>