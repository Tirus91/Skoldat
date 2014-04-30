<div class="page_name">Pronájmy / přehled smluv</div>   
{tag:message /}
<form action="{tag:homelink /}/Rents/showRefi" method="post" >
    <div class="section">
        <div class="section_name">Vyhledávání</div>
        <label>Smluvní strana: </label><select name="first_name_rent">
                {loop:first_name_rent}
                <option value="{tag:first_name_rent[].id_rent /}" {tag:first_name_rent[].sel /}>{tag:first_name_rent[].first_name_rent /} {tag:first_name_rent[].surname_rent /}</option>
                {/loop:first_name_rent}
            </select>
        <div style="float:right">
            <input type="submit" class="filter" name="filter" value="Filtrovat" />
            <input type="submit" class="reset_filter" name="reset_filter" value="Reset filtru" />
        </div>
    </div>
</form>
<table id="showRooms" width="100%">
    <tr>
        <th>Jméno a příjmení</th>
        <th class="td10">Popis smlouvy</th>
        <th class="td10">Místnost</th>
        <th class="td5">&nbsp;</th>
    </tr>
    {loop:refi}
    <tr>
        <td><a title="{tag:refi[].first_name_rent /} {tag:refi[].surname_rent /}">{tag:refi[].first_name_rent /} {tag:refi[].surname_rent /}</a></td>
        <td>{tag:refi[].description_refi /}</td>
        <td>{tag:refi[].name_room /}</td>
        <td>{tag:refi[].delete /}&nbsp;{tag:refi[].lock /}&nbsp;</a></td>
    </tr>
    {/loop:refi}
</table>
<div id="pagination">
    {tag:pagination /}
</div>