<div class="page_name">Pronájmy / přehled pronájmů </div>   
{tag:message /}
<div>

    <form action="{tag:homelink /}/Rents/showRecu" method="post">
        <div class="section">
            <div class="section_name">Vyhledávání</div>
            <label for="">Od: </label><input class="datepicker_from_1" name="showRecuFrom" value="{tag:from_date /}" type="text" />
            <label for="">Do: </label><input class="datepicker_to_1" name="showRecuTo" value="{tag:to_date /}" type="text" />
            <label for="">Místnost: </label><select name="id_room_recu">
                <option value="all" {tag:rooms[].sel /}>Zobrazit všechny místnosti</option>
                {loop:rooms}
                <option value="{tag:rooms[].id_room /}" {tag:rooms[].sel /}>{tag:rooms[].fullname /}</option>
                {/loop:rooms}
            </select>
            <div style="float:right">
                <input type="submit" class="export_csv" name="export_csv" value="Exportovat do CSV" />
                <input type="submit" class="filter" name="filter" value="Filtrovat" />
                <input type="submit" class="reset_filter" name="reset_filter" value="Reset filtru" />
            </div>
        </div>
    </form>
    <br />
</div>
<form action="{tag:homelink /}/Rents/massDeleteRecu" method="post" name="recu">
    <table id="showRooms" width="100%">
        <tr>
            <th class="td1"><input type="checkbox" value="check_all" class="check_all_mass" name="check_all_mass" {tag:massdeletechb /}/></th>
            <th>Jméno a příjmení</th>
            <th class="td10">Místnost</th>
            <th class="td10">Od -> do</th>
            <th class="td10">Datum</th>

            <th class="td5">&nbsp;</th>
        </tr>
        {loop:recu}
        <tr class="{tag:recu[].sel /}">
            <td class="td5"><input type="checkbox" class="for_mass_delete" value="{tag:recu[].id_recu /}" name="mass_delete[]" {tag:massdeletechb /} /></td>
            <td><span title="{tag:recu[].first_name_rent /} {tag:recu[].surname_rent /}">{tag:recu[].first_name_rent /} {tag:recu[].surname_rent /}</span></td>
            <td>{tag:recu[].name_room /} ({tag:recu[].description_room /})</td>
            <td>{tag:recu[].time_from_recu /} -> {tag:recu[].time_to_recu /}</td>
            <td>{tag:recu[].day_recu /}</td>
            <td>&nbsp;{tag:recu[].delete /}{tag:recu[].status_recu /}</td>
        </tr>
        {/loop:recu}
    </table>
    {if:massdeletebutton}
        <input class="delbutton" name="massdeletebutton" type="submit" value="Smazat označené" style="float:right;" data-confirm="Opravdu si přejete smazat označené?" />
    {else:massdeletebutton}
        &nbsp;
    {/if:massdeletebutton}
</form> 

<form action="{tag:homelink /}/Rents/addRecu" method="post">
    {if:addItembutton}
        <input class="addbutton" name="addItem" type="submit" value="Přidat nový"/>
    {else:addItembutton}
        &nbsp;
    {/if:addItembutton}

</form>
<div id="pagination">
    {tag:pagination /}
</div>