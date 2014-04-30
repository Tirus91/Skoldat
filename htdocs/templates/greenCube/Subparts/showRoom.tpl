<div class="page_name">Pronájmy / přehled místností</div>   

{tag:message /}
   <form action="{tag:homelink /}/Rents/showRooms" method="post" >
    <div class="section">
            <div class="section_name">Vyhledávání</div>
           <label>Název: <input name="name_room" value="{tag:name_room /}" type="text" /></label>
           <label>Lokace: <input name="location_room" value="{tag:location_room /}" type="text" /></label>
           <div style="float:right">
           <input type="submit" class="filter" name="filter" value="Filtrovat" />
           <input type="submit" class="reset_filter" name="reset_filter" value="Reset filtru" />
           </div>
          
   </div>
   </form>
   <table id="showRooms" width="100%">
<tr>
    <th>Název místnosti</th>
    <th class="td10">Lokace místnosti</th>
    <th class="td20">Popis</th>
    <th class="td5">&nbsp;</th>
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