<form method="post" action="#" class="addRecu">
<input type="button" class="addRow" value="&nbsp;+&nbsp;"  />
<input type="button" class="delRow" value="&nbsp;-&nbsp;" />
    {loop:recu}
    <div class="repeatdiv">
        <h3>Nový pronájem</h3>
        <label>Smlouva: <select name="id_refi_recu[]">
                                    {tag:recu[].id_refi_recu /}
                                </select></label><p>Smlouvu přidáte kliknutím <a href="/Rents/addRefi">zde</a></p>
                                
        <fieldset><legend>Datumy</legend>
        <label>Od: <input class="datepicker_from_{tag:recu[].row_count /}" name="date_from_recu[]" value="{tag:recu[].date_from_recu /}" type="text" /></label>
        <label>Do: <input class="datepicker_to_{tag:recu[].row_count /}" name="date_to_recu[]" value="{tag:recu[].date_to_recu /}" type="text" /></label>
        <label>Typ zápisu: <select name="date_recu[]" title="V celém daném rozmězí => Pronájem bude zapsán každý den v zadaném rozmezí
Jednorázově => pouze v den kdy pronájem začíná. 
Pondělí až Neděle => znamená že se pronájem bude pravidelně opakovat v tento den v týdnu v zadaném rozmezí">
            {loop:recu[].date_recu}
            <option value="{tag:recu[].date_recu[].key /}" {tag:recu[].date_recu[].sel /}>{tag:recu[].date_recu[].value /}</option>
            {/loop:recu[].date_recu}
        </select></label>
        </fieldset>
        
        <fieldset><legend>Rozmezí hodin</legend>
        <label>Od: <input class="timepicker_from_{tag:recu[].row_count /}" name="time_from_recu[]" value="{tag:recu[].time_from_recu /}" type="text" /></label>
        <label>Do: <input class="timepicker_to_{tag:recu[].row_count /}"  name="time_to_recu[]" value="{tag:recu[].time_to_recu /}" type="text" /></label>
        </fieldset>
    <b>{tag:recu[].status /}</b>
    </div>
    {/loop:recu}

     <br />
     <input name="addRecu" class="savebutton" type="submit" value="Zpracovat" disabled="disabled" />

</form>      
 