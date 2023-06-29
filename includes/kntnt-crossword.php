<script>
    window.addEventListener('load', function () {
        var CrosswordPuzzleData = "<?php echo $content;?>";
        jQuery("<?php echo "#$crossword_id" ?>").CrosswordCompiler(CrosswordPuzzleData, null, {
            ROOTIMAGES: "<?php echo $crossword_plugin_url?>/vendor/CrosswordCompilerApp/CrosswordImages/"
        });
    })
</script>
<div id="<?php echo $crossword_id ?>"></div>
