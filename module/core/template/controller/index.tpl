<h1>Hola mundo</h1>
<p><strong>{$var}</strong> Es grande! :D</p>

<a href="#" onclick="callAjax(); return false;">Click Me!!</a>

{literal}
<script type="text/javascript">
function callAjax()
{
    $.ajax({
        type : 'POST',
        url: '/ajax/blog/updateBlog',
        data: 'id=12&u=Jneutron',
        dataType: "script",
    });
}
</script>
{/literal}

<div id="main">Empty</div>