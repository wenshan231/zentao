<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
css::import($defaultTheme . 'autosuggest.css');
js::import($jsRoot . 'jquery/autosuggest/min.js');
?>
<script language='javascript'>
$(function() {
    var data = {items: [
    {value: "21", name: "Mick Jagger"},
    {value: "43", name: "Johnny Storm"},
    {value: "46", name: "Richard Hatch"},
    {value: "54", name: "Kelly Slater"},
    {value: "55", name: "Rudy Hamilton"},
    {value: "79", name: "Michael Jordan"}
    ]};
    $("input[type=text]").autoSuggest(data.items, {selectedItem: "name", searchObj: "name"});
});
</script>
