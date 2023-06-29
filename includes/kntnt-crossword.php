<script>
    var crosswordData = '<?php echo $crossword_data;?>';
    window.addEventListener('load', function () {
        jQuery("<?php echo "#$crossword_id" ?>").CrosswordCompiler(crosswordData, null, {
            ROOTIMAGES: "<?php echo $crossword_plugin_url?>/vendor/CrosswordCompilerApp/CrosswordImages/"
        });
    })
</script>
<div id="<?php echo $crossword_id ?>"></div>
