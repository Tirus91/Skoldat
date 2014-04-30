<form method="post" action="#" class="editRoom">
     <div class="editRoomField">
    <label>Název místnosti: <input name="name_room" value="{tag:name_room /}" /></label>
    <label>Umístění místnosti: <input name="location_room" value="{tag:location_room /}" /></label>
    <label>Popis: <input name="description_room" value="{tag:description_room /}" /></label>
    {tag:status /}
    </div>


     
    <input name="editRoom" class="savebutton" type="submit" value="Zpracovat" />
            </form>    