jQuery(document).ready(function() {
	/** Change the input type according to the object/operator selection */
	var placeholder = jQuery("#value").attr("placeholder");
	jQuery(".flexible-blogtitle .add-rule select").change(function() {
		var object_value = jQuery("#object").val();
		var operator_value = jQuery("#operator").val();
		var value_selector = jQuery("#value");
		var value_category_selector = jQuery("#value-category");
		var value_taxonomy_selector = jQuery("#value-taxonomy");
		var value_term_selector = jQuery("#value-term");
		var value_element = jQuery(".flexible-blogtitle .add-rule select[name=value], .flexible-blogtitle .add-rule input[name=value]");

		value_category_selector.hide();
		value_taxonomy_selector.hide();
		value_term_selector.hide();
		value_selector.show();
		value_selector.attr("type", "text");
		//value_selector.attr("name", "value");
		value_selector.attr("placeholder", placeholder);
		if(object_value == "post_id" || object_value == "page_id" || object_value == "category_id") {
			if(operator_value == "is" || operator_value == "is_not") {
				value_selector.removeAttr("placeholder");
				value_selector.attr("type", "number");
				value_element.removeAttr("name");
				value_selector.attr("name", "value");
			}
		}
		if(object_value == "category") {
			if(operator_value == "is" || operator_value == "is_not") {
				value_selector.hide();
				value_category_selector.show();
				value_element.removeAttr("name");
				value_category_selector.attr("name", "value");
			}
		}
		if(object_value == "taxonomy") {
			if(operator_value == "is" || operator_value == "is_not") {
				value_selector.hide();
				value_taxonomy_selector.show();
				value_element.removeAttr("name");
				value_taxonomy_selector.attr("name", "value");
			}
		}
		if(object_value == "term") {
			if(operator_value == "is" || operator_value == "is_not") {
				value_selector.hide();
				value_term_selector.show();
				value_element.removeAttr("name");
				value_term_selector.attr("name", "value");
			}
		}
	});
	/** Define "select all" functionality */
	var all_selected = false;
	jQuery(".flexible-blogtitle .active-rules #select-all").click(function() {
		var selector_checkbox = jQuery(".flexible-blogtitle .active-rules td input[type=checkbox]");
		if(all_selected) {
			selector_checkbox.removeAttr("checked");
			all_selected = false;
		}
		else {
			selector_checkbox.attr("checked", "checked");
			all_selected = true;
		}
	});
	/** Check if "add rule" form has been filled out */
	jQuery(".flexible-blogtitle .add-rule input[type=submit]").click(function() {
		var fields = jQuery(".flexible-blogtitle .add-rule input[type=text], .flexible-blogtitle .add-rule select");
		var returned = true;
		fields.each(function() {
			/** Don't check hidden fields */
			if(jQuery(this).val() == "" && jQuery(this).attr("hidden") != "") {
				/** Highlight field if empty */
				jQuery(this).css("background", "#FFFBCC");
				/** Don't submit the form if there is an empty field */
				returned = false;
			}
			else {
				/** Don't check hidden fields */
				if(jQuery(this).attr("hidden") != "") {
					jQuery(this).css("background", "white");
					returned = true;
				}
			}
		});
		return returned;
	});
	/** Check if any checkbox is selected when using bulk edit */
	var all_unchecked = true;
	var returned;
	jQuery(".flexible-blogtitle .bulk-edit input[type=submit]").click(function() {
		var entries = jQuery(".flexible-blogtitle .active-rules .entry input[type=checkbox]");
		entries.each(function() {
			var entry = jQuery(this);
			if(entry.attr("checked")) {
				all_unchecked = false;
			}
		});
		/** If all checkbox are unchecked, stop form and highlight checkboxes */
		if(all_unchecked) {
			entries.css("background", "#FFFBCC");
			returned = false;
		}
		else {
			entries.css("background", "#FFFFFF");
			returned = true;
		}
		return returned;
	});
});