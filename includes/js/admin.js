/**
 * Displays graphs on stat pages
 *
 */
( function( $ ) {
console.log(als);
var resultsVsNoresultsconfig = {
type: 'pie',
data: {
datasets: [{
data: als.resultsVsNoresults,
backgroundColor: [
"#F7464A",
"#46BFBD",
],
}],
labels: [
"Queries Without Results",
"Queries With Results",
]
},
options: {
responsive: true
}
};

var SearchesPerMonthconfig = {
type: 'bar',
data: {
datasets: [{
data: als.als_searches_per_month_values,
backgroundColor: "#F7464A",
label: 'Searches Per Month',
}],
labels: als.als_searches_per_month_labels,
},
options: {
responsive: true
}
};

var SearchesPerWeekconfig = {
type: 'bar',
data: {
datasets: [{
data: als.als_searches_this_week_values,
backgroundColor: "#F7464A",
label: 'Searches This Week',
}],
labels: als.als_searches_this_week_labels,
},
options: {
responsive: true
}
};

var SearchesTodayconfig = {
type: 'bar',
data: {
datasets: [{
data: als.als_searches_today_values,
backgroundColor: "#F7464A",
label: 'Searches Today',
}],
labels: als.als_searches_today_labels,
},
options: {
responsive: true
}
};



window.onload = function() {
if(document.getElementById("resultsVsNoresults")) {
	var resultsVsNoresults = document.getElementById("resultsVsNoresults").getContext("2d");
	window.resultsVsNoresults = new Chart(resultsVsNoresults, resultsVsNoresultsconfig);

	var SearchesPerMonth = document.getElementById("SearchesPerMonth").getContext("2d");
	window.SearchesPerMonth = new Chart(SearchesPerMonth, SearchesPerMonthconfig);

	var SearchesPerWeek = document.getElementById("SearchesPerWeek").getContext("2d");
	window.SearchesPerWeek = new Chart(SearchesPerWeek, SearchesPerWeekconfig);

	var SearchesToday = document.getElementById("SearchesToday").getContext("2d");
	window.SearchesToday = new Chart(SearchesToday, SearchesTodayconfig);
}
};

//csv strings
$('.alscsv').click(function(e){
		e.preventDefault();
		$('.alscsv').parent().html('<span class="alscsv">Preparing CSV</span>');
		$.get(als.ajaxurl, 
		 { 
		 action: 'alscsv',
		 nextNonce: als.nextNonce
		 }, 
		 function(data,status,xhr) {
		 
		var download = 'Done! <a href="data:text/csv;charset=utf-8,' + escape(data) + '" download="stats.csv"> Download</a>';
		
		$('.alscsv').parent().html(download);

	});

});
} )( jQuery );