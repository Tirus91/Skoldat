<div class="agenda_head">{tag:name_meli /}</div>
<div class="agenda_body"> 
    <ul class="agenda_list">
        {loop:meit}
        <li><a href="{tag:meit[].link_meit /}" class="{tag:meit[].selected /}" title="{tag:meit[].name_meit /}">{tag:meit[].name_small_meit /}</a></li>
        {/loop:meit}
    </ul>
</div>