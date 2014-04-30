<div class="page_name">Pronájmy / nová místnost</div>   
<form method="post" action="#" class="addRoom">
    {loop:rooms}
    <div class="repeatdiv">
        <div class="section">
            <div class="section_name">Nová místnost</div>
            <input type="button" class="addRow" value="&nbsp;+&nbsp;" />
            <input type="button" class="delRow" value="&nbsp;-&nbsp;"/>
            <div class="formrow"><label for="name_room[]">Název místnosti: </label><input name="name_room[]" value="{tag:rooms[].name_room /}" />  </div>
            <div class="formrow"><label for="location_room[]">Umístění místnosti: </label><input name="location_room[]" value="{tag:rooms[].location_room /}" />  </div>
            <div class="formrow"><label for="description_room[]">Popis: </label><input name="description_room[]" value="{tag:rooms[].description_room /}" />  </div>
                {tag:rooms[].status /}
        </div>
    </div>
    {/loop:rooms}


    <input name="addRoom" class="savebutton" type="submit" value="Uložit" />


</form>      
