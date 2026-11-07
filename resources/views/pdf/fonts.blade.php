{{--
    Font Roboto untuk semua export PDF (DomPDF).

    Dimuat di setiap template PDF lewat: @include('pdf.fonts')

    DomPDF mendaftarkan font @font-face ini ke cache di storage/fonts
    (font_dir, volume persisten) saat dokumen pertama dirender, lalu
    memakainya untuk lookup family 'Roboto' pada semua weight/style:
    normal (400), Medium (500), SemiBold (600), bold (700), italic,
    dan bold_italic (fallback ke Roboto-Bold karena tidak ada file
    Roboto-BoldItalic):
--}}
<style>
    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: normal;
        src: url('file://{{ public_path('fonts/Roboto/Roboto-Regular.ttf') }}');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 500;
        src: url('file://{{ public_path('fonts/Roboto/Roboto-Medium.ttf') }}');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 600;
        src: url('file://{{ public_path('fonts/Roboto/Roboto-SemiBold.ttf') }}');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: bold;
        src: url('file://{{ public_path('fonts/Roboto/Roboto-Bold.ttf') }}');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: italic;
        font-weight: normal;
        src: url('file://{{ public_path('fonts/Roboto/Roboto-Italic.ttf') }}');
    }

    @font-face {
        font-family: 'Roboto';
        font-style: italic;
        font-weight: bold;
        src: url('file://{{ public_path('fonts/Roboto/Roboto-Bold.ttf') }}');
    }
</style>
