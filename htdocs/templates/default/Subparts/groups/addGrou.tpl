<form method="post" action="#" class="addGrou">
    <h3>Nová skupina</h3>
    {tag:status /}
    <label>Název skupiny: <input name="name_grou" value="{tag:name_grou /}" /></label>
    <label>Zkratka skupiny: <input name="abbrev_grou" value="{tag:abbrev_grou /}" /></label>    
    <label>Označení skupiny: <input name="ident_grou" value="{tag:ident_grou /}" /></label>    
    <input name="addGrou" class="savebutton" type="submit" value="Uložit" />
</form>      
