<div class="page_name">Pronájmy / přehled smluvních stran</div>   
{tag:message /}
<form action="{tag:homelink /}/Rents/showRent" method="post" >
     <div class="section">
            <div class="section_name">Vyhledávání</div>
        <label>Jméno: <input name="first_name_rent" value="{tag:first_name_rent /}" type="text" /></label>
        <label>Příjmení: <input name="surname_rent" value="{tag:surname_rent /}" type="text" /></label> 
        <label>Telefon: <input name="phone_rent" value="{tag:phone_rent /}" type="text" /></label>
        <div style="float:right">
            <input type="submit" class="filter" name="filter" value="Filtrovat" />
            <input type="submit" class="reset_filter" name="reset_filter" value="Reset filtru" />
        </div>
    </div>
</form>
<table id="showRooms" width="100%">
    <tr>
        <th>Jméno a příjmení</th>
        <th class="td10">Telefon</th>
        <th class="td15">Adresa</th>
        <th class="td10">Email</th>
        <th class="td20">Popis</th>
        <th class="td5">&nbsp;</th>
    </tr>
    {loop:rents}
    <tr>
        <td><a title="{tag:rents[].first_name_rent /} {tag:rents[].surname_rent /}">{tag:rents[].first_name_rent /} {tag:rents[].surname_rent /}</a></td>
        <td>{tag:rents[].phone_rent /}</td>
        <td>{tag:rents[].address_rent /}</td>
        <td>{tag:rents[].email_rent /}</td>
        <td>{tag:rents[].town_rent /}</td>
        <td>{tag:rents[].edit /}&nbsp;
            {tag:rents[].delete /}&nbsp;&nbsp;&nbsp;</a></td>
    </tr>
    {/loop:rents}
</table>
<form action="{tag:homelink /}/Rents/addRent" method="post">
    {if:addItembutton}
        <input class="addbutton" name="addItem" type="submit" value="Přidat nový"/>
    {else:addItembutton}
        &nbsp;
    {/if:addItembutton}
</form>
<div id="pagination">
    {tag:pagination /}
</div>