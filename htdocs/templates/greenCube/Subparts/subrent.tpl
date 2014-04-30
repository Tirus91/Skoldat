<optgroup label="{tag:optgroup /}">
    {loop:list}
<option value="{tag:list[].id_refi /}" {tag:list[].sel /}>{tag:list[].description_refi /}</option>
{/loop:list}
</optgroup>
