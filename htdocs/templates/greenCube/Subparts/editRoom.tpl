<div class="page_name">Pronájmy / </div>   
<form method="post" action="#" class="editRoom">
    <div class="editRoomField">
        <div class="section">
            <div class="section_name">Úprava místnosti</div>
            <div class="formrow"><label for="name_room">Název místnosti: </label><input name="name_room" value="{tag:name_room /}" /></div>
            <div class="formrow"><label for="location_room">Umístění místnosti: </label><input name="location_room" value="{tag:location_room /}" /></div>
            <div class="formrow"><label for="description_room">Popis: </label><input name="description_room" value="{tag:description_room /}" /></div>
                {tag:status /}
        </div>

    </div>



    <input name="editRoom" class="savebutton" type="submit" value="Zpracovat" />
</form>    