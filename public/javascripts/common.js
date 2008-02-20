
function highlightMenuItem() {
	var map = new Object();
	var number = new RegExp('[0-9]+');
	var url = window.location.href.split('/');
	var activeView = url.pop();

	while (number.test(activeView)) {
		activeView = url.pop();
	}

	/* voter */
	map['vote'] = "VOTE";
	map['confirm_vote'] = "CONFIRM VOTE";
	map['logout'] = "LOG OUT";
	map['votes'] = "VOTES";
	/* admin */
	map['home'] = "HOME";
	map['candidates'] = "CANDIDATES";
	map['candidate'] = map['candidates'];
	map['parties'] = "PARTIES";
	map['party'] = map['parties'];
	map['positions'] = "POSITIONS";
	map['position'] = map['positions'];
	map['voters'] = "VOTERS";
	map['voter'] = map['voters'];
	map['import'] = map['voters'];
	map['export'] = map['voters'];

	var li = $("#menu ul li").filter(function() {return ($(this).text() == map[activeView]);});
	li.children().andSelf().css("background", "#4983ff");
}

/* jQuery event handlers */
function toggle_details() {
	/*
		<tr> parent
		<td> parent
		<img /> this
		</td>
		</tr>
		<tr> next
		<td> children(0)
		<div></div> children(0)
		</td>
		</tr>
	*/
	$(this).parents("tr").next().find("div.details").slideToggle('normal');
	return false;
}
