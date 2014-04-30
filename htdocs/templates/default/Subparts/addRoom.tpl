<form method="post" action="#" class="addRoom">
    <input type="button" class="addRow" value="&nbsp;+&nbsp;" />
    <input type="button" class="delRow" value="&nbsp;-&nbsp;"/>
    {loop:rooms}
    <div class="repeatdiv">
        <h3>Nová místnost</h3>
        <label>Název místnosti: <input name="name_room[]" value="{tag:rooms[].name_room /}" /></label>
        <label>Umístění místnosti: <input name="location_room[]" value="{tag:rooms[].location_room /}" /></label>
        <label>Popis: <input name="description_room[]" value="{tag:rooms[].description_room /}" /></label>
            {tag:rooms[].status /}
    </div>
    {/loop:rooms}


    <input name="addRoom" class="savebutton" type="submit" value="Uložit" />


</form>      
