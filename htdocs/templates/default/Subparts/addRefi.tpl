<form method="post" action="#" class="addRecu">
<input type="button" class="addRow" value="&nbsp;+&nbsp;"  />
<input type="button" class="delRow" value="&nbsp;-&nbsp;" />
    {loop:refi}
    <div class="repeatdiv">
        <h3>Nová smlouva</h3>
        <label>Smluvní strana: <select name="id_rent_refi[]">
                                    {loop:refi[].id_rent_refi}
                                    <option value="{tag:refi[].id_rent_refi[].id_rent /}" {tag:refi[].id_rent_refi[].sel /}>{tag:refi[].id_rent_refi[].first_name_rent /} {tag:refi[].id_rent_refi[].surname_rent /}</option>
                                    {/loop:refi[].id_rent_refi}
                                </select></label>
        <label>Pronajatá místnost: <select name="id_room_refi[]">
                                   {loop:refi[].id_room_refi}
                                    <option value="{tag:refi[].id_room_refi[].id_room /}" {tag:refi[].id_room_refi[].sel /}>{tag:refi[].id_room_refi[].fullname /}</option>
                                   {/loop:refi[].id_room_refi}
                                </select></label>
        <label>Popis: <input name="description_refi[]" value="{tag:refi[].description_refi /}" /></label>
    <b>{tag:refi[].status /}</b>
    </div>
    {/loop:refi}

     <br />
     <input name="addRefi" class="savebutton" type="submit" value="Zpracovat" />

</form>      
 