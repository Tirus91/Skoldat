<div class="page_name">Pronájmy / nová smlouva</div>   
<form method="post" action="#" class="addRecu">
    {loop:refi}
    <div class="repeatdiv">
        <div class="section">
            <div class="section_name">Nová smlouva</div>
            <input type="button" class="addRow" value="&nbsp;+&nbsp;"  />
            <input type="button" class="delRow" value="&nbsp;-&nbsp;" />
            <div class="formrow"><label for="id_rent_refi[]">Smluvní strana: </label><select name="id_rent_refi[]">
                    {loop:refi[].id_rent_refi}
                    <option value="{tag:refi[].id_rent_refi[].id_rent /}" {tag:refi[].id_rent_refi[].sel /}>{tag:refi[].id_rent_refi[].first_name_rent /} {tag:refi[].id_rent_refi[].surname_rent /}</option>
                    {/loop:refi[].id_rent_refi}
                </select></div>
            <div class="formrow"><label for="id_room_refi[]">Pronajatá místnost: </label><select name="id_room_refi[]">
                    {loop:refi[].id_room_refi}
                    <option value="{tag:refi[].id_room_refi[].id_room /}" {tag:refi[].id_room_refi[].sel /}>{tag:refi[].id_room_refi[].fullname /}</option>
                    {/loop:refi[].id_room_refi}
                </select></div>
            <div class="formrow"><label for="description_refi[]">Popis: </label><input name="description_refi[]" value="{tag:refi[].description_refi /}" /></div>
            <b>{tag:refi[].status /}</b>
        </div>
    </div>
    {/loop:refi}

    <br />
    <input name="addRefi" class="savebutton" type="submit" value="Zpracovat" />

</form>      
