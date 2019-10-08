<!-- REQUIRED JS SCRIPTS -->

<!-- JQuery and bootstrap are required by Laravel 5.3 in resources/assets/js/bootstrap.js-->
<!-- Laravel App -->
<script src="{{ url (mix('/js/app.js')) }}" type="text/javascript"></script>
<script src="{{ asset('/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/jquery.orgchart.min.js') }}"></script>
<script src="{{ asset('/plugins/select2/select2.min.js') }}"></script>
<!-- <script src=" https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js "></script> -->
<script src=" https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js "></script>
<script src=" https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js "></script>
<script src=" https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js "></script>
<script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js "></script>
<script src=" https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js "></script>
<script src=" https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js "></script>
<script src=" https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js "></script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->
<script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script>
