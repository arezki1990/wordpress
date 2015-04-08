<?php 
		$Custom_CSS = get_option("EWD_UFAQ_Custom_CSS");
		$FAQ_Accordion = get_option("EWD_UFAQ_FAQ_Accordion");
		$Hide_Categories = get_option("EWD_UFAQ_Hide_Categories");
		$Reveal_Effect = get_option("EWD_UFAQ_Reveal_Effect");
		
		$Group_By_Category = get_option("EWD_UFAQ_Group_By_Category");
		$Group_By_Order_By = get_option("EWD_UFAQ_Group_By_Order_By");
		$Group_By_Order = get_option("EWD_UFAQ_Group_By_Order");
		$Order_By_Setting = get_option("EWD_UFAQ_Order_By");
		$Order_Setting = get_option("EWD_UFAQ_Order");
        $Socialmedia_String = get_option("EWD_UFAQ_Social_Media");
        $Socialmedia = explode(",", $Socialmedia_String);
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Settings</h2>

<form method="post" action="edit.php?post_type=ufaq&page=options&Action=EWD_UFAQ_UpdateOptions">
<table class="form-table">
<tr>
<th scope="row">Custom CSS</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
	<label title='Custom CSS'></label><textarea class='ewd-ufaq-textarea' name='custom_css'> <?php echo $Custom_CSS; ?></textarea><br />
	<p>You can add custom CSS styles for your FAQs in the box above.</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">FAQ Accordion</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>FAQ Accordion</span></legend>
	<label title='Yes'><input type='radio' name='faq_accordion' value='Yes' <?php if($FAQ_Accordion == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='faq_accordion' value='No' <?php if($FAQ_Accordion == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Should the FAQs accordion? (Close old open FAQ when a new one is clicked)</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">Hide Categories</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Hide Categories</span></legend>
	<label title='Yes'><input type='radio' name='hide_categories' value='Yes' <?php if($Hide_Categories == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='hide_categories' value='No' <?php if($Hide_Categories == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Should the categories for each FAQ be hidden?</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">Reveal Effect</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Reveal Effect</span></legend>
	<label title='Reveal Effect'></label> 

	<select name="reveal_effect">
  		<option value="none" <?php if($Reveal_Effect == "none") {echo "selected=selected";} ?> >None</option>
			<option value="blind" <?php if($Reveal_Effect == "blind") {echo "selected=selected";} ?> >Blind</option>
  		<option value="bounce" <?php if($Reveal_Effect == "bounce") {echo "selected=selected";} ?> >Bounce</option>
  		<option value="clip" <?php if($Reveal_Effect == "clip") {echo "selected=selected";} ?> >Clip</option>
  		<option value="drop" <?php if($Reveal_Effect == "drop") {echo "selected=selected";} ?> >Drop</option>
  		<option value="explode" <?php if($Reveal_Effect == "explode") {echo "selected=selected";} ?> >Explode</option>
  		<option value="fade" <?php if($Reveal_Effect == "fade") {echo "selected=selected";} ?> >Fade</option>
  		<option value="fold" <?php if($Reveal_Effect == "fold") {echo "selected=selected";} ?> >Fold</option>
  		<option value="highlight" <?php if($Reveal_Effect == "highlight") {echo "selected=selected";} ?> >Highlight</option>
  		<option value="puff" <?php if($Reveal_Effect == "puff") {echo "selected=selected";} ?> >Puff</option>
  		<option value="pulsate" <?php if($Reveal_Effect == "pulsate") {echo "selected=selected";} ?> >Pulsate</option>
  		<option value="shake" <?php if($Reveal_Effect == "shake") {echo "selected=selected";} ?> >Shake</option>
  		<option value="size" <?php if($Reveal_Effect == "size") {echo "selected=selected";} ?> >Size</option>
  		<option value="slide" <?php if($Reveal_Effect == "slide") {echo "selected=selected";} ?> >Slide</option>
	</select>
	
	<p>How should FAQ's be displayed when their titles are clicked?</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">Group FAQs by Category</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Group FAQs by Category</span></legend>
	<label title='Yes'><input type='radio' name='group_by_category' value='Yes' <?php if($Group_By_Category == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
	<label title='No'><input type='radio' name='group_by_category' value='No' <?php if($Group_By_Category == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
	<p>Should FAQs be grouped by category, or should all categories be mixed together?</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">Sort Categories</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Sort Categories</span></legend>
	<label title='Group By Order By'></label> 

	<select name="group_by_order_by">
  		<option value="name" <?php if($Group_By_Order_By == "name") {echo "selected=selected";} ?> >Name</option>
			<option value="count" <?php if($Group_By_Order_By == "count") {echo "selected=selected";} ?> >FAQ Count</option>
  		<option value="slug" <?php if($Group_By_Order_By == "slug") {echo "selected=selected";} ?> >Slug</option>
	</select>
	
	<p>How should FAQ categories be ordered? (Only used if "Group FAQs by Category" above is set to "Yes")</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">Sort Categories Ordering</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Sort Categories Ordering</span></legend>
	<label title='Ascending'><input type='radio' name='group_by_order' value='ASC' <?php if($Group_By_Order == "ASC") {echo "checked='checked'";} ?> /> <span>Ascending</span></label><br />
	<label title='Descending'><input type='radio' name='group_by_order' value='DESC' <?php if($Group_By_Category == "DESC") {echo "checked='checked'";} ?> /> <span>Descending</span></label><br />	
	<p>How should FAQ categories be ordered? (Only used if "Group FAQs by Category" above is set to "Yes")</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">FAQ Ordering</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>FAQ Ordering</span></legend>
	<label title='FAQ Ordering'></label> 

	<select name="order_by_setting">
  		<option value="date" <?php if($Order_By_Setting == "date") {echo "selected=selected";} ?> >Created Date</option>
			<option value="title" <?php if($Order_By_Setting == "title") {echo "selected=selected";} ?> >Title</option>
  		<option value="modified" <?php if($Order_By_Setting == "modified") {echo "selected=selected";} ?> >Modified Date</option>
	</select>
	
	<p>How should individual FAQs be ordered?</p>
	</fieldset>
</td>
</tr>

<tr>
<th scope="row">FAQ Order Setting</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Sort Categories Ordering</span></legend>
	<label title='Yes'><input type='radio' name='order_setting' value='ASC' <?php if($Order_Setting == "ASC") {echo "checked='checked'";} ?> /> <span>Ascending</span></label><br />
	<label title='No'><input type='radio' name='order_setting' value='DESC' <?php if($Order_Setting == "DESC") {echo "checked='checked'";} ?> /> <span>Descending</span></label><br />	
	<p>Should FAQ be ascending or descending order, based on the ordering criteria above?</p>
	</fieldset>
</td>
</tr>
<tr>
<th scope="row">Social Media Option</th>
<td>
    <fieldset><legend class="screen-reader-text"><span>Social Media Option</span></legend>
        <label title='Facebook'><input type='checkbox' name='Socialmedia[]' value='Facebook' <?php if(in_array("Facebook", $Socialmedia)) {echo "checked='checked'";} ?> /> <span>Facebook</span></label><br />
        <label title='Name'><input type='checkbox' name='Socialmedia[]' value='Google'  <?php if(in_array("Google", $Socialmedia)) {echo "checked='checked'";} ?>  /> <span>Google</span></label><br />
        <label title='Twitter'><input type='checkbox' name='Socialmedia[]' value='Twitter' <?php if(in_array("Twitter", $Socialmedia)) {echo "checked='checked'";} ?> /> <span>Twitter</span></label><br />
        <label title='Linkedin'><input type='checkbox' name='Socialmedia[]' value='Linkedin' <?php if(in_array("Linkedin", $Socialmedia)) {echo "checked='checked'";} ?> /> <span>Linkedin</span></label><br />
        <label title='Pinterest'><input type='checkbox' name='Socialmedia[]' value='Pinterest' <?php if(in_array("Pinterest", $Socialmedia)) {echo "checked='checked'";} ?> /> <span>Pinterest</span></label><br />
        <label title='Email'><input type='checkbox' name='Socialmedia[]' value='Email' <?php if(in_array("Email", $Socialmedia)) {echo "checked='checked'";} ?> /> <span>Email</span></label><br />
    </fieldset>
</td>
</tr>

</table>


<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>

