<form method="post" action="#" class="addGrou">
    <h3>Úprava skupiny</h3>
    {tag:status /}
    <label>Název skupiny: <input name="name_grou" value="{tag:name_grou /}" /></label>
    <label>Zkratka skupiny: <input name="abbrev_grou" value="{tag:abbrev_grou /}" /></label>    
    <label>Označení skupiny: <input name="ident_grou" value="{tag:ident_grou /}" /></label>    
    <label style="display:none;">ID skupiny: <input name="id_grou" value="{tag:id_grou /}" /></label>    
    <input name="editGrou" class="savebutton" type="submit" value="Uložit" />
</form>      
