   {tag:message /}
   <form action="{tag:homelink /}/Rents/showRooms" method="post" >
   <fieldset>
   <legend>Vyhledávání</legend>
           <label>Název: <input name="name_room" value="{tag:name_room /}" type="text" /></label>
           <label>Lokace: <input name="location_room" value="{tag:location_room /}" type="text" /></label>
           <div style="float:right">
           <input type="submit" class="filter" name="filter" value="Filtrovat" />
           <input type="submit" class="reset_filter" name="reset_filter" value="Reset filtru" />
           </div>
          
   </fieldset>
   </form>
   <table id="showRooms" width="100%">
<tr>
    <th>Název místnosti</th>
    <th>Lokace místnosti</th>
    <th>Popis</th>
    <th>&nbsp;</th>
</tr>
{loop:rooms}
<tr>
    <td><a title="{tag:rooms[].name_room /}">{tag:rooms[].name_room /}</a></td>
    <td>{tag:rooms[].location_room /}</td>
    <td>{tag:rooms[].description_room /}</td>
    <td>{tag:rooms[].edit /}&nbsp;&nbsp;</a>&nbsp;
        {tag:rooms[].delete /}</td>
</tr>
{/loop:rooms}
</table>
<form action="{tag:homelink /}/Rents/addRoom" method="post">
 {if:addItembutton}
        <input class="addbutton" name="addItem" type="submit" value="Přidat nový"/>
    {else:addItembutton}
        &nbsp;
    {/if:addItembutton}
</form>
<div id="pagination">
{tag:pagination /}
</div>