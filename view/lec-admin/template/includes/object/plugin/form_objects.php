<hr/><br/>
Example Field Structure<br/>
<code>
	
<?php echo str_replace('	','&nbsp;&nbsp;&nbsp;&nbsp;',nl2br('[
	{
		"name":"Field",				//label of the input
		"field":"field",			//db table
		"placeholder":"something",	//input placeholder
		"form_type":"text",			//input type (text, textarea, select, select_yesno, date etc)
		"select_table":"",			//table for selects
		"select_field":"",			//which field for select option labels
		"mandatory":"yes",			//is the field mandatory?
		"edit_type":"text",			//how is the data stored? (text, html, number etc)
		"class_inj":"",				//any classes for inputs to be injected into that input?
		"help_text":"",				//forms-desc text under input
		"highlight_in_list":"yes",	//is a yes/no field, make it highlight?
		"read_only":"no",			//is input read only?
		"sortable":"no",			//can you sort on this field in the object list?
		"half_width":"no"			//is the input half width on the form?
	}
]'));?>
	
</code>
