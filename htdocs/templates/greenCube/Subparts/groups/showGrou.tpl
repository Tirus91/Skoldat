<div class="page_name">Administrace / přehled skupin</div>
{tag:message /}

<table id="showRooms" width="100%">
    <tr>
        <th>Název skupiny</th>
        <th class="td10">Zkratka</th>
        <th class="td10">Identifikační číslo</th>
        <th class="td10">Počet členů</th>
        <th class="td5"></th>
    </tr>
    {loop:groups}
    <tr>
        <td>{tag:groups[].name_grou /}</td>
        <td>{tag:groups[].abbrev_grou /}</td>
        <td>{tag:groups[].ident_grou /}</td>
        <td>{tag:groups[].count_user /}</td>
        <td><a class="face" href="/Admin/setUsgr/{tag:groups[].id_grou /}">&nbsp;&nbsp;&nbsp;</a>&nbsp;{tag:groups[].del_grou /}&nbsp;{tag:groups[].edi_grou /}</td>
    </tr>
    {/loop:groups}
</table>
<div id="pagination">
    {tag:pagination /}
</div>