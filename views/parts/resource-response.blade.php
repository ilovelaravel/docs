<?php $unique = (string)uniqid(); ?>
<h6 class="font-weight-bold">
    Response
    <span class="request request-200" style="margin-left: 5px; float: none; padding-right: 5px; padding-left: 5px">200 OK</span>
</h6>
<pre class="{{$unique}}">

</pre>

<script>
    (function() {
        window.output(window.syntaxHighlight(<?php echo json_encode($item['example']['response'], true) ?>), '<?php echo $unique; ?>');
    })();
</script>