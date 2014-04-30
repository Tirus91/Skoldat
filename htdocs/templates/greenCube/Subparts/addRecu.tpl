<div class="page_name">Pronájmy / nový pronájem </div>   
<form method="post" action="#" class="addRecu">
    {loop:recu}
    <div class="repeatdiv">
        <div class="section">
            <div class="section_name">Nový pronájem</div>
            <input type="button" class="addRow" value="&nbsp;+&nbsp;"  />
            <input type="button" class="delRow" value="&nbsp;-&nbsp;" />
            <div class="formrow"><label for="id_refi_recz[]">Smlouva: </label><select name="id_refi_recu[]">
                    {tag:recu[].id_refi_recu /}
                </select><span class="hint">Smlouvu přidáte kliknutím <a href="/Rents/addRefi">zde</a></span></div>
        </div>
        <div class="section">
            <div class="section_name">Datumy</div>
            <div class="formrow"><label for="date_from_recu[]">Od: </label><input class="datepicker_from_{tag:recu[].row_count /}" name="date_from_recu[]" value="{tag:recu[].date_from_recu /}" type="text" /></div>
            <div class="formrow"><label for="date_to_recu[]">Do: </label><input class="datepicker_to_{tag:recu[].row_count /}" name="date_to_recu[]" value="{tag:recu[].date_to_recu /}" type="text" /></div>
            <div class="formrow"><label for="date_recu[]">Typ zápisu: </label><select name="date_recu[]" title="V celém daném rozmězí => Pronájem bude zapsán každý den v zadaném rozmezí
                                                                                      Jednorázově => pouze v den kdy pronájem začíná. 
                                                                                      Pondělí až Neděle => znamená že se pronájem bude pravidelně opakovat v tento den v týdnu v zadaném rozmezí">
                    {loop:recu[].date_recu}
                    <option value="{tag:recu[].date_recu[].key /}" {tag:recu[].date_recu[].sel /}>{tag:recu[].date_recu[].value /}</option>
                    {/loop:recu[].date_recu}
                </select></div>
        </div>
        <div class="section">
            <div class="section_name">Rozmezí hodin</div>
            <div class="formrow"><label for="time_from_recu[]">Od: </label><input class="timepicker_from_{tag:recu[].row_count /}" name="time_from_recu[]" value="{tag:recu[].time_from_recu /}" type="text" /></div>
            <div class="formrow"><label for="time_to_recu[]">Do: </label><input class="timepicker_to_{tag:recu[].row_count /}"  name="time_to_recu[]" value="{tag:recu[].time_to_recu /}" type="text" /></div>
        </div>
        <b>{tag:recu[].status /}</b>
    </div>
    {/loop:recu}

    <br />
    <input name="addRecu" class="savebutton" type="submit" value="Zpracovat" disabled="disabled" />

</form>      
