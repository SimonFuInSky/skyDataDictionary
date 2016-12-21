var ajaxbg = $("#datagrid-mask-msg , #background");
$(document).ajaxStart(function (){
	ajaxbg.show();
}).ajaxStop(function () {
	ajaxbg.hide();
});
