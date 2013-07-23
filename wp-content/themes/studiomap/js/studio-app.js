/*
	AJAX Interface for Maps App.
*/

var jqxhr;

$(document).ready( function ( ) {
	console.log("Hello!");
	$("#studio-query").on( 'submit' , function( e ) {

		// Prevent default posting of form
		e.preventDefault();

		// Abort any pending request
		if (jqxhr) {
			jqxhr.abort();
		}

		// setup some local variables
		var $form = $(this);

		// let's select and cache all the fields
		var $inputs = $form.find("input, select, button, textarea");

		// serialize the data in the form
		var serializedData = $form.serialize();

		console.log(serializedData);

		// let's disable the inputs for the duration of the ajax request
		$inputs.prop("disabled", true);

		var jqxhr = $.post(
			// Endpoint from wp_enqueue_script
			Studios.endpoint,
			// Data from form, which has the action as a hidden input.
			serializedData
		);

		// callback handler that will be called on success
		jqxhr.done(function (response, textStatus, jqXHR){
			// log a message to the console
			console.log( response );
		});

		// callback handler that will be called on failure
		jqxhr.fail(function (jqXHR, textStatus, errorThrown){
			// log the error to the console
			console.error(
			    "The following error occured: " +
			    textStatus, errorThrown
			);
		});

		// callback handler that will be called regardless
		// if the request failed or succeeded
		jqxhr.always(function () {
			// reenable the inputs
			$inputs.prop("disabled", false);
		});

		return false;
	});
});